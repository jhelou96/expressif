<?php
namespace app\modules\pages;

use config\Exception;

use vendor\editor\BBCode;

use app\modules\Module;

use app\modules\pages\models\Page;
use app\modules\pages\models\PagesManager;

/**
 * Class PagesBackController
 * Pages module controller for the backend interface
 * @package app\modules\pages
 */
class PagesBackController extends Module {
    /**
     * @var PagesManager
     * DB manager object for the page entity
     */
    private $_pagesManager;

    /**
     * @var BBCode
     * BBCode object used to parse BBCode
     */
    private $_bbcode;

    /**
     * PagesBackController constructor.
     */
    public function __construct() {
        //We run the module
        $this->run();

        //We check if the user is an administrator
        if (!$this->_isAdministrator)
            throw new Exception("accessError_restricted");

        //We load the managers related to this module
        $this->_pagesManager = new PagesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Displays the list of pages
     */
    public function listPages() {
        $pages = $this->_pagesManager->getListPages();

        $this->_smarty->assign(array( "pages" => $pages));

        $this->_smarty->display("app/modules/pages/views/backend/tpl/listPages.tpl");
    }

    /**
     * Allows the user(administrator) to create a new page
     */
    public function newPage() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $page = new Page();
                $page->setContent($_POST['page_content']);
                $page->setName($_POST['page_name']);

                //We check if a page with the same name already exists
                if(!is_null($this->_pagesManager->get($page)))
                    throw new Exception("pageAlreadyExists");

                $this->_pagesManager->add($page);

                setcookie('module_successMsg', "pageCreated", time() + 365*24*3600, '/', null, false, true);
                header("location:".  $this->_path . "/backend/modules/manage/pages");
                exit();
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array( "module_errorMsg" => $e->getMessage()));
        } finally {
            //We save the inputs that have been submitted so the user doesn't have to type them again
            if(isset($_POST['page_name']) AND !empty($_POST['page_name']))
                $this->_smarty->assign(array( "page_name" => htmlspecialchars($_POST['page_name'])));
            if(isset($_POST['page_content']) AND !empty($_POST['page_content']))
                $this->_smarty->assign(array( "page_content" => htmlspecialchars($_POST['page_content'])));

            $this->_smarty->display("app/modules/pages/views/backend/tpl/newPage.tpl");
        }
    }

    /**
     * Allows the user(administrator) to edit a specific page
     * @param array $args Saves page ID from the URL
     */
    public function editPage($args)
    {
        try {
            $page = new Page();
            $page->setId($args['id']);
            $page = $this->_pagesManager->get($page);
            if(is_null($page))
                throw new Exception("accessError_404");

            $this->_smarty->assign(array(
                "page_id" => $page->getId(),
                "page_name" => $page->getName(),
                "page_content" => $page->getContent()
            ));

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $page->setContent($_POST['page_content']);
                $page->setName($_POST['page_name']);

                $this->_pagesManager->update($page);

                setcookie('module_successMsg', "pageEdited", time() + 365*24*3600, '/', null, false, true);
                header("location:".  $this->_path . "/backend/modules/manage/pages");
                exit();
            }
        } catch (Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            //We save the inputs that have been submitted so the user doesn't have to type them again
            if (isset($_POST['page_name']))
                $this->_smarty->assign(array("page_name" => htmlspecialchars($_POST['page_name'])));
            if (isset($_POST['page_content']))
                $this->_smarty->assign(array("page_content" => htmlspecialchars($_POST['page_content'])));

            $this->_smarty->display("app/modules/pages/views/backend/tpl/editPage.tpl");
        }
    }

    /**
     * Allows the user(administrator) to set a specific page as the homepage
     * @param array $args Saves page ID from the URL
     */
    public function changeHomepage($args) {
        try {
            //We check first if the page exists
            $page = new Page();
            $page->setId($args['id']);
            $page = $this->_pagesManager->get($page);
            if(is_null($page))
                throw new Exception("accessError_404");

            $this->_pagesManager->changeHomepage($page);

            setcookie('module_successMsg', "homepageHasBeenChanged", time() + 365*24*3600, '/', null, false, true);
            header("location:".  $this->_path . "/backend/modules/manage/pages");
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
            header("location:".  $this->_path . "/backend/modules/manage/pages");
            exit();
        }
    }

    /**
     * Allows the user(administrator) to remove a specific page
     * @param array $args Saves page ID from the URL
     */
    public function removePage($args) {
        try {
            //We check first if the page exists
            $page = new Page();
            $page->setId($args['id']);
            $page = $this->_pagesManager->get($page);
            if(is_null($page))
                throw new Exception("accessError_404");

            //If the page is the homepage, it can't be removed
            if($page->getIsHomepage())
                throw new Exception("homepageCannotBeRemoved");

            $this->_pagesManager->remove($page);

            setcookie('module_successMsg', "pageHasBeenRemoved", time() + 365*24*3600, '/', null, false, true);
            header("location:".  $this->_path . "/backend/modules/manage/pages");
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true, '/');
            header("location:".  $this->_path . "/backend/modules/manage/pages");
            exit();
        }
    }
}