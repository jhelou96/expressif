<?php
namespace app\modules\articles;

use \Exception;
use vendor\Pagination;
use vendor\editor\BBCode;

use app\modules\articles\models\Category;
use app\modules\articles\models\Article;

use app\modules\articles\models\CategoriesManager;
use app\modules\articles\models\ArticlesManager;
use app\modules\members\models\MembersManager;

use app\modules\Module;

/**
 * Class ArticlesBackController
 * Articles module controller for the backend/admin interface
 * @package app\modules\articles
 */
class ArticlesBackController extends Module {
    /**
     * @var categoriesManager
     * DB manager object for the article categories entity
     */
    private $_categoriesManager;
    /**
     * @var articlesManager
     * DB manager object for the article entity
     */
    private $_articlesManager;
    /**
     * @var membersManager
     * DB manager object for the member entity
     */
    private $_membersManager;

    /**
     * @var BBCode
     * BBCode object used to parse BBCode
     */
    private $_bbcode;

    /**
     * ArticlesBackController constructor.
     */
    public function __construct() {
        //Module is instantiated using the parent class
        $this->run();

        //We check if the user is a moderator
        if(!$this->_isModerator)
            throw new Exception("accessError_restricted");

        //We load the managers related to this module
        $this->_membersManager = new MembersManager();
        $this->_categoriesManager = new CategoriesManager();
        $this->_articlesManager = new ArticlesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));

    }

    /**
     * Displays the list of published articles
     * @param array $args Saves page number from the URL
     */
    public function articles($args) {
        $pagination = new Pagination();
        $pagination->setnbRecords($this->_articlesManager->getNbArticles());

        if (isset($args['page']))
            $pagination->setActualPage($args['page']);

        $pagination->execute();

        //List of articles published
        $articles = $this->_articlesManager->getListValidatedArticles($pagination->sqlLimit());

        $this->_smarty->assign(array(
            "articles" => $articles,
            "pagination" => $pagination->parse()
        ));

        $this->_smarty->display("app/modules/articles/views/backend/tpl/articles.tpl");
    }

    /**
     * Displays the list of pending articles
     */
    public function pendingArticles() {
        //List of articles that need to be validated
        $articles = $this->_articlesManager->getListPendingArticles();

        $this->_smarty->assign(array("articles" => $articles));

        $this->_smarty->display("app/modules/articles/views/backend/tpl/pendingArticles.tpl");
    }

    /**
     * Allows the user(moderator or administrator) to edit an article
     * @param array $args Saves article ID from the URL
     */
    public function editArticle($args) {
        try {
            //We check if article exists
            $article = new Article();
            $article->setId($args['id']);
            $article = $this->_articlesManager->get($article);
            if(is_null($article))
                throw new Exception("articleNotFound");

            //Article can't be edited if it hasn't been submitted by user or if it hasn't already been published
            if ($article->getStatus() != 1 AND $article->getStatus() != 2) {
                header('Location: ' . $this->_path . '/backend/modules/manage/articles');
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                //If the user requests a preview
                if(isset($_POST['article_preview'])) {
                    //Article attributes are specified just to check if they are valid
                    $article->setId($args['id']);
                    $article->setContent($_POST['article_content']);
                    $article->setTitle($_POST['article_title']);
                    $article->setDescription($_POST['article_description']);
                    $article->setIdCategory($_POST['article_category']);
                    $article->setStatus(2);

                    //We check if an article with the same title already exists
                    if(!is_null($this->_articlesManager->get($article)) AND $this->_articlesManager->get($article)->getId() != $article->getId())
                        throw new Exception("articleWithSameTitleAlreadyExists");

                    $this->_smarty->assign(array("article_preview" => $this->_bbcode->parsebbcode($_POST['article_content'])));
                } elseif(isset($_POST['article_validate'])) { //If the user validates the article
                    $article->setId($args['id']);
                    $article->setContent($_POST['article_content']);
                    $article->setTitle($_POST['article_title']);
                    $article->setDescription($_POST['article_description']);
                    $article->setIdCategory($_POST['article_category']);
                    $article->setStatus(2);

                    //We check if an article with the same title already exists
                    if(!is_null($this->_articlesManager->get($article)) AND $this->_articlesManager->get($article)->getId() != $article->getId())
                        throw new Exception("articleWithSameTitleAlreadyExists");

                    $this->_articlesManager->update($article);

                    setcookie('module_successMsg', 'articleHasBeenValidated', time() + 365 * 24 * 3600, '/', null, false, true);

                    header('Location: ' . $this->_path . '/backend/modules/manage/articles');
                    exit();
                } else {
                    header('Location: ' . $this->_path . '/backend/modules/manage/articles');
                    exit();
                }
            }
        } catch(Exception $e) {
             $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            //We save temporarily the content of the fields so the user doesn't have to fill them again
            if(isset($_POST['article_content']) AND !empty($_POST['article_content']))
                $this->_smarty->assign(array("article_content" => $_POST['article_content']));
            if(isset($_POST['article_title']) AND !empty($_POST['article_title']))
                $this->_smarty->assign(array("article_title" => $_POST['article_title']));
            if(isset($_POST['article_description']) AND !empty($_POST['article_description']))
                $this->_smarty->assign(array("article_description" => $_POST['article_description']));
            if(isset($_POST['article_category']) AND !empty($_POST['article_category']))
                $this->_smarty->assign(array("article_category" => $_POST['article_category']));

            $categories = $this->_categoriesManager->getListCategories();

            $this->_smarty->assign(array(
                "article_id" => $article->getId(),
                "article_content" => $article->getContent(),
                "article_title" => $article->getTitle(),
                "article_description" => $article->getDescription(),
                "article_category" => $article->getIdCategory(),
                "categories" => $categories
            ));

            $this->_smarty->display("app/modules/articles/views/backend/tpl/editArticle.tpl");
        }
    }

    /**
     * Allows the user(moderator or administrator) to refuse an article
     * @param array $args Saves article ID from the URL
     */
    public function refuseArticle($args) {
        try {
            //We check if article exists
            $article = new Article();
            $article->setId($args['id']);
            $article = $this->_articlesManager->get($article);
            if(is_null($article))
                throw new Exception("articleNotFound");

            //Article can't be edited if it hasn't been submitted by user or if it hasn't already been published
            if ($article->getStatus() != 1 AND $article->getStatus() != 2)
                throw new Exception("invalidArticleStatus");

            $article->setStatus(-1); //Status -1 --> Article refused

            $this->_articlesManager->update($article);

            setcookie('module_successMsg', 'articleHasBeenRefused', time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/articles');
            exit();
        } catch(Exception $e) {
            header('Location: ' . $this->_path . '/backend/modules/manage/articles');
            exit();
        }
    }

    /**
     * Allows the user(administrator) to manage articles categories
     */
    public function categories() {
        try {
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                 //If the user is creating a new category
                if(isset($_POST['category_create'])) {
                    //We check if a category with the same name exists
                    $category = new Category();
                    $category->setName($_POST['category_name']);
                    if(!is_null($this->_categoriesManager->get($category)))
                        throw new Exception("categoryAlreadyExists");

                    //THUMBNAIL
                    if(isset($_FILES['category_thumbnail']) && $_FILES['category_thumbnail']['size'] > 0) {
                        $folder = 'web/upload/';

                        //We verify if the format of the file is valid
                        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                        $extension = strrchr($_FILES['category_thumbnail']['name'], '.');
                        if(!in_array($extension, $extensions)) //if extensions not in array
                            throw new Exception("incorrectFileFormat");

                        //We rename the file using the timestamp function so we are assured to have a unique name
                        $fileWithoutExtension = pathinfo($_FILES['category_thumbnail']['name'], PATHINFO_FILENAME);
                        $file = $fileWithoutExtension . time() . $extension;

                        if(move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], $folder . $file))
                            $category->setThumbnail('web/upload/' . $file);
                    }

                    $this->_categoriesManager->add($category);
                    $this->_smarty->assign(array("module_successMsg" => "categoryHasBeenCreated"));

                } elseif(isset($_POST['category_edit'])) { //If the user is editing a category
                    //We check if category exists
                    $category = new Category();
                    $category->setId($_POST['category_id']);
                    if(is_null($this->_categoriesManager->get($category)))
                        throw new Exception("categoryDoesNotExist");

                    $category->setName($_POST['category_name']);

                    //THUMBNAIL
                    if(isset($_FILES['category_thumbnail']) && $_FILES['category_thumbnail']['size'] > 0) {
                        $folder = 'web/upload/';

                        //We verify if the format of the file is valid
                        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                        $extension = strrchr($_FILES['category_thumbnail']['name'], '.');
                        if(!in_array($extension, $extensions)) //if extensions not in array
                            throw new Exception("incorrectFileFormat");

                        //We rename the file using the timestamp function so we are assured to have a unique name
                        $fileWithoutExtension = pathinfo($_FILES['category_thumbnail']['name'], PATHINFO_FILENAME);
                        $file = $fileWithoutExtension . time() . $extension;

                        if(move_uploaded_file($_FILES['category_thumbnail']['tmp_name'], $folder . $file))
                            $category->setThumbnail('web/upload/' . $file);
                    }

                    $this->_categoriesManager->update($category);
                    $this->_smarty->assign(array("module_successMsg" => "categoryHasBeenEdited"));
                }
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $categories = $this->_categoriesManager->getListCategories();
            $this->_smarty->assign(array("categories" => $categories));

            $this->_smarty->display("app/modules/articles/views/backend/tpl/categories.tpl");
        }
    }

    /**
     * Allows the user(administrator) to remove a category of articles
     * @param array $args Saves article category ID from the URL
     */
    public function removeCategory($args) {
        try {
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            //We check if category exists
            $category = new Category();
            $category->setId($args['id']);
            if(is_null($this->_categoriesManager->get($category)))
                throw new Exception("categoryNotFound");

            $this->_categoriesManager->remove($category);

            setcookie('module_successMsg', 'categoryHasBeenRemoved', time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/articles/categories');
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/articles/categories');
            exit();
        }
    }
}