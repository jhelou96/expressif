<?php
namespace app\modules\forum;

use app\modules\forum\models\CategoriesManager;
use app\modules\forum\models\LikesManager;
use app\modules\forum\models\MessagesManager;
use app\modules\forum\models\SubCategoriesManager;
use app\modules\forum\models\TopicsManager;

use vendor\editor\BBCode;
use config\Exception;

use app\modules\forum\models\Category;
use app\modules\forum\models\SubCategory;

use app\modules\Module;

/**
 * Class ForumBackController
 * Forum module controller for the backend/admin interface
 * @package app\modules\forum
 */
class ForumBackController extends Module
{
    /**
     * @var BBCode
     * BBCode object used to parse BBCode
     */
    private $_bbcode;

    /**
     * @var CategoriesManager
     * DB manager object for the forum category entity
     */
    private $_categoriesManager;
    /**
     * @var SubCategoriesManager
     * DB manager object for the forum subcategory entity
     */
    private $_subCategoriesManager;
    /**
     * @var TopicsManager
     * DB manager object for the forum topic entity
     */
    private $_topicsManager;
    /**
     * @var MessagesManager
     * DB manager object for the forum message entity
     */
    private $_messagesManager;
    /**
     * @var LikesManager
     * DB manager object for the forum like entity
     */
    private $_likesManager;

    /**
     * ForumBackController constructor.
     */
    public function __construct()
    {
        $this->run();

        //We check if the user is an administrator
        if (!$this->_isAdministrator)
            throw new Exception("accessError_restricted");

        //We load the managers related to this module
        $this->_categoriesManager    = new CategoriesManager();
        $this->_subCategoriesManager = new SubCategoriesManager();
        $this->_topicsManager        = new TopicsManager();
        $this->_messagesManager      = new MessagesManager();
        $this->_likesManager         = new LikesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Allows user to manage, create and edit categories
     */
    public function categories() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $category = new Category();
                $category->setName($_POST['category_name']);

                if($this->_categoriesManager->checkIfCategoryExists($category))
                    throw new Exception("categoryAlreadyExists");

                //If the user is creating a new category
                if(isset($_POST['category_create'])) {
                    $this->_categoriesManager->add($category);
                    $this->_smarty->assign(array("module_successMsg" => "categoryHasBeenCreated"));
                } elseif(isset($_POST['category_edit'])) { //If the user is editing a category
                    $category->setId($_POST['category_id']);
                    if(!$this->_categoriesManager->checkIfCategoryExists($category))
                        throw new Exception("categoryDoesNotExist");

                    $this->_categoriesManager->update($category);
                    $this->_smarty->assign(array("module_successMsg" => "categoryHasBeenEdited"));
                }
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $categories = $this->_categoriesManager->get();

            $this->_smarty->assign(array("categories" => $categories));

            $this->_smarty->display("app/modules/forum/views/backend/tpl/categories.tpl");
        }
    }

    /**
     * Allows the user to remove a category
     * @param array $args Gets category id from the URL
     */
    public function removeCategory($args) {
        try {
            $category = new Category();
            $category->setId($args['id']);

            if (!$this->_categoriesManager->checkIfCategoryExists($category))
                throw new Exception("categoryNotFound");

            $this->_categoriesManager->remove($category);

            setcookie('module_successMsg', 'categoryHasBeenRemoved', time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/forum');
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/forum');
            exit();
        }
    }

    /**
     * Allows the user to manage, create and edit subcategories
     */
    public function subcategories() {
        try {
            //We check if any category has been created
            if($this->_categoriesManager->getNbCategories() == 0)
                throw new Exception("categoriesMustBeCreatedFirst");

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $subCategory = new SubCategory();
                $subCategory->setName($_POST['subcategory_name']);
                $subCategory->setDescription($_POST['subcategory_description']);
                $subCategory->setIdCategory($_POST['subcategory_idCategory']);

                //If the user is creating a new subcategory
                if(isset($_POST['subcategory_create'])) {
                    //We check if a subcategory with the same name already exists
                    if(!is_null($this->_subCategoriesManager->get($subCategory)))
                        throw new Exception("subCategoryAlreadyExists");

                    $this->_subCategoriesManager->add($subCategory);
                    $this->_smarty->assign(array("module_successMsg" => "subCategoryHasBeenCreated"));

                } elseif(isset($_POST['subcategory_edit'])) { //If the user is editing a subcategory
                    //We check if subcategory exists
                    $subCategory->setId($_POST['subCategory_id']);
                    if(is_null($this->_subCategoriesManager->get($subCategory)))
                        throw new Exception("subCategoryDoesNotExist");

                    //We check if there is any other subcategory with the same name
                    if(!is_null($this->_subCategoriesManager->get($subCategory)) AND $this->_subCategoriesManager->get($subCategory)->getId() != $subCategory->getId())
                        throw new Exception("subCategoryAlreadyExists");

                    $this->_subCategoriesManager->update($subCategory);
                    $this->_smarty->assign(array("module_successMsg" => "subCategoryHasBeenEdited"));
                }
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            //We save temporarily the values of the fields submitted so the user doesn't have to fill them again
            if(isset($_POST['subcategory_add'])) {
                if (isset($_POST['subcategory_name']) AND !empty($_POST['subcategory_name']))
                    $this->_smarty->assign(array("subcategory_name" => $_POST['subcategory_name']));
                if (isset($_POST['subcategory_description']) AND !empty($_POST['subcategory_description']))
                    $this->_smarty->assign(array("subcategory_description" => $_POST['subcategory_description']));
                if (isset($_POST['subcategory_idCategory']) AND !empty($_POST['subcategory_idCategory']))
                    $this->_smarty->assign(array("subcategory_idCategory" => $_POST['subcategory_idCategory']));
            }

            $categories = $this->_categoriesManager->get();
            $subCategories = $this->_subCategoriesManager->getListSubcategories();

            $this->_smarty->assign(array(
                "categories" => $categories,
                "subCategories" => $subCategories
            ));

            $this->_smarty->display("app/modules/forum/views/backend/tpl/subCategories.tpl");
        }
    }

    /**
     * Allows the user to remove a subcategory
     * @param array $args Gets subcategory id from the URL
     */
    public function removeSubCategory($args) {
        try {
            //We check if subcategory exists
            $subCategory = new SubCategory();
            $subCategory->setId($args['id']);
            if (is_null($this->_subCategoriesManager->get($subCategory)))
                throw new Exception("subCategoryNotFound");

            $this->_subCategoriesManager->remove($subCategory);

            setcookie('module_successMsg', 'subCategoryHasBeenRemoved', time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/forum/subcategories');
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/forum/subcategories');
            exit();
        }
    }
}
