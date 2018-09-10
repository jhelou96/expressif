<?php
namespace app\modules\pages;

use config\Exception;

use vendor\editor\BBCode;

use app\modules\Module;

use app\modules\pages\models\Page;
use app\modules\pages\models\PagesManager;

/**
 * Class PagesFrontController
 * Pages module controller for the frontend interface
 * @package app\modules\pages
 */
class PagesFrontController extends Module {
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
     * PagesFrontController constructor.
     */
    public function __construct() {
        //We run the module
        $this->run();

        //We load the managers related to this module
        $this->_pagesManager = new PagesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Displays application frontend homepage
     */
    public function home() {
        //We first check if the page exists
        $page = $this->_pagesManager->getHomepage();
        if(is_null($page))
            throw new Exception("accessError_404");

        $this->_smarty->assign(array(
            "page_id" => $page->getId(),
            "page_name" => $page->getName(),
            "page_content" => $this->_bbcode->parsebbcode($page->getContent()),
            "page_isHomepage" => $page->getIsHomepage()
        ));

        $this->_smarty->display("app/modules/pages/views/frontend/tpl/page.tpl");
	}

    /**
     * Displays a specific page
     * @param array $args Saves page name from the URL
     */
    public function showPage($args) {
        //We check if the page exists
        $page = new Page();
        $page->setName($args['page']);
        $page = $this->_pagesManager->get($page);
        if(is_null($page))
            throw new Exception("accessError_404");

        $this->_smarty->assign(array(
            "page_id" => $page->getId(),
            "page_name" => $page->getName(),
            "page_content" => $this->_bbcode->parsebbcode($page->getContent()),
            "page_isHomepage" => $page->getIsHomepage()
        ));

        $this->_smarty->display("app/modules/pages/views/frontend/tpl/page.tpl");
    }
}