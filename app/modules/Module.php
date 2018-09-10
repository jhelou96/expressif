<?php
namespace app\modules;

use \Smarty;
use \ReflectionClass;

use config\Exception;
use config\Config;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class Module
 * Parent class for all the modules backend and frontend controllers
 * Used to instantiate a module
 * @package app\modules
 */
class Module
{
    /**
     * @var Smarty
     * Template engine
     */
    protected $_smarty;



    /**
     * @var string
     * Application name
     */
    protected $_name;

    /**
     * @var string
     * Application path
     */
    protected $_path;

    /**
     * @var string
     * Application lang
     */
    protected $_lang;

    /**
     * @var string
     * Folder of frontend template used
     */
    protected $_frontendTemplate;

    /**
     * @var string
     * Folder of backend template used
     */
    protected $_backendTemplate;

    /**
     * @var Member
     */
    protected $_member;

    /**
     * @var bool
     * Specifies if member is connected
     */
    protected $_isConnected;

    /**
     * @var bool
     * Specified if member is moderator
     */
    protected $_isModerator;

    /**
     * @var bool
     * Specifies if member is administrator
     */
    protected $_isAdministrator;

    /**
     * Method used in every module controller constructor to initialize the module
     */
    public function run() {
        $this->_path = Config::getPath();
        $this->_lang = Config::getLang();
        $this->_name = Config::getName();
        $this->_frontendTemplate = Config::getFrontendTheme();
        $this->_backendTemplate = Config::getBackendTheme();

        $this->_smarty = new Smarty();
        $this->_smarty->setCompileDir('web/templates_c');
        $this->_smarty->assign(array(
            "website_lang" => $this->_lang,
            "website_path" => $this->_path,
            "website_name" => $this->_name,
            "website_frontendTemplate" => $this->_frontendTemplate,
            "website_backendTemplate" => $this->_backendTemplate
        ));

        //We check if the user is connected
        if(isset($_SESSION['idUser'])) {
            $this->_member = new Member();
            $this->_member->setId($_SESSION['idUser']);
            $membersManager = new MembersManager();
            $this->_member = $membersManager->get($this->_member);

            $this->_isConnected = ((!empty($this->_member)) ? true : false);
            $this->_isModerator = ((!empty($this->_member->getLevel() > 1)) ? true : false);
            $this->_isAdministrator = ((!empty($this->_member->getLevel() == 3)) ? true : false);
        } else {
            $this->_isConnected = false;
            $this->_isModerator = false;
            $this->_isAdministrator = false;
        }
        $this->_smarty->assign(array(
            "isConnected" => $this->_isConnected,
            "isModerator" => $this->_isModerator,
            "isAdministrator" => $this->_isAdministrator
        ));

        //We check if the module is activated
        $rc = new ReflectionClass(get_class($this)); //Used to find some informations about the child class that called the parent class
		$moduleFolder = dirname($rc->getFileName()); //Directory of the module that called the parent class
        if(!Config::checkIfModuleActive($moduleFolder) AND !$this->_isAdministrator)
            throw new Exception("accessError_404");

        //We check if the website is in maintenance
        if(Config::checkIfSiteInMaintenance() AND !$this->_isModerator)
            throw new Exception("accessError_maintenance");

        //We check if there is any error/success message that travels through a cookie from a page to another to display
        if(isset($_COOKIE['module_successMsg'])) {
            $this->_smarty->assign(array("module_successMsg" => $_COOKIE['module_successMsg']));
            setcookie('module_successMsg', null, -1, '/');
        }
        if(isset($_COOKIE['module_errorMsg'])) {
            $this->_smarty->assign(array("module_errorMsg" => $_COOKIE['module_errorMsg']));
            setcookie('module_errorMsg', null, -1, '/');
        }
    }
}