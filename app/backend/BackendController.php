<?php
namespace app\backend;

use \ZipArchive;
use \DateTimeZone;
use \DateTime;
use config\Exception;
use \Smarty;
use \PDO;

use config\Config;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class BackendController
 * Main controller of the backend interface
 * @package app\backend
 */
class BackendController {
    /**
     * @var PDO
     * PDO object used to communicate with the DB
     */
    private $_db;

    /**
     * @var string
     * Application path
     */
    private $_path;

    /**
     * @var string
     * Application lang
     */
    private $_lang;

    /**
     * @var string
     * Application name
     */
    private $_name;

    /**
     * @var string
     * Folder of backend template used
     */
    private $_backendTemplate;

    /**
     * @var Smarty
     * Template engine
     */
    private $_smarty;

    /**
     * @var Member
     */
    private $_member;

    /**
     * @var bool
     * Specifies if member is connected
     */
    private $_isConnected;

    /**
     * @var bool
     * Specified if member is moderator
     */
    private $_isModerator;

    /**
     * @var bool
     * Specifies if member is administrator
     */
    private $_isAdministrator;

    /**
     * BackendController constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_path = Config::getPath();
        $this->_lang = Config::getLang();
        $this->_name = Config::getName();
        $this->_backendTemplate = Config::getBackendTheme();

        $this->_smarty = new Smarty();
        $this->_smarty->setCompileDir('web/templates_c');
        $this->_smarty->assign(array(
            "website_lang" => $this->_lang,
            "website_path" => $this->_path,
            "website_name" => $this->_name,
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

        //We check if the user has the rights to access the backend interface
        if(!$this->_isModerator) {
            header('Location: ' . $this->_path . '/');
            exit();
        }

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

    /**
     * Displays backend homepage
     */
    public function dashboard() {
        //If the user has submitted a note
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $note = htmlspecialchars($_POST['note']);

            //We save the note in a file
            $file = fopen('app/backend/note.txt', 'w+') or die("Can't open file.");
            fwrite($file, $note);
            fclose($file);
        }

        //We check if any note has been saved
        if(file_exists('app/backend/note.txt')) {
            $file = fopen('app/backend/note.txt', 'r');
            $note = fread($file, filesize('app/backend/note.txt'));
            fclose($file);

            $this->_smarty->assign(array("note" => $note));
        }

        //UPDATE CHECK SCRIPT - CODE TAKEN FROM THE UPDATE LIBRARY
        $updateAvailable = false;
        if(file_exists('version.txt'))
            $currentVersion = file_get_contents('version.txt');
        else
            $currentVersion = '1.0.0';
        $getVersions = @file_get_contents('http://expressif.net/zirkon/current-release-versions.txt');
        $versionList = explode("\n", $getVersions);
        sort($versionList);
        foreach($versionList as $actualVersion) {
            $actualVersion = trim($actualVersion);
            if ($actualVersion > $currentVersion)
                $updateAvailable = true;
        }


        //System informations
        $this->_smarty->assign(array(
            "system_os" => PHP_OS,
            "system_php" => PHP_VERSION,
            "system_db" => $this->_db->getAttribute(constant("PDO::ATTR_SERVER_VERSION")),
            "system_webserver" => strtok($_SERVER["SERVER_SOFTWARE"], '/'),
            "system_softwareVersion" => $currentVersion,
            "system_updateAvailable" => $updateAvailable
        ));


        $this->_smarty->display("app/backend/views/dashboard.tpl");
    }

    /**
     * Allows the user(administrator) to configure the application
     */
    public function configuration()
    {
        try {
            //We check if the user is an administrator
            if (!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            //We check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!empty($_POST['website_name']))
                    $website_name = htmlspecialchars($_POST['website_name']);
                else
                    throw new Exception("websiteNameCannotBeEmpty");

                if (!empty($_POST['website_url']))
                    $website_url = htmlspecialchars($_POST['website_url']);
                else
                    throw new Exception("websiteURLCannotBeEmpty");

				 if (!empty($_POST['website_email']))
                    $website_email = htmlspecialchars($_POST['website_email']);
                else
                    throw new Exception("websiteEmailCannotBeEmpty");
				
                if (!empty($_POST['website_path']))
                    $website_path = htmlspecialchars($_POST['website_path']);
                else
                    throw new Exception("websitePathCannotBeEmpty");

                if (!empty($_POST['website_lang']))
                    $website_lang = htmlspecialchars($_POST['website_lang']);
                else
                    throw new Exception("websiteLangCannotBeEmpty");

                if (!empty($_POST['website_frontTheme']))
                    $website_frontTheme = htmlspecialchars($_POST['website_frontTheme']);
                else
                    throw new Exception("websiteFrontendThemeCannotBeEmpty");

                if (!empty($_POST['website_backTheme']))
                    $website_backTheme = htmlspecialchars($_POST['website_backTheme']);
                else
                    throw new Exception("websiteBackendThemeCannotBeEmpty");

                if (!empty($_POST['website_timezone']))
                    $website_timezone = htmlspecialchars($_POST['website_timezone']);
                else
                    throw new Exception("websiteTimezoneCannotBeEmpty");

                if (isset($_POST['website_maintenance']))
                    $website_maintenance = 1;
                else
                    $website_maintenance = 0;

                //We save the configurations in JSON format
                $configuration = '{';
                $configuration .= '"website_name":"' . $website_name . '",';
                $configuration .= '"website_url":"' . $website_url . '",';
				$configuration .= '"website_email":"' . $website_email . '",';
                $configuration .= '"website_path":"' . $website_path . '",';
                $configuration .= '"website_lang":"' . $website_lang . '",';
                $configuration .= '"website_frontTheme":"' . $website_frontTheme . '",';
                $configuration .= '"website_backTheme":"' . $website_backTheme . '",';
                $configuration .= '"website_timezone":"' . $website_timezone . '",';
                $configuration .= '"website_maintenance":"' . $website_maintenance . '"';
                $configuration .= '}';
                file_put_contents('config/config.json', $configuration);

                $this->_smarty->assign(array("module_successMsg" => "settingsHaveBeenChanged"));
            }
        } catch (Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            //List of frontend themes
            $directory   = "web/templates/frontend/";
            $files       = glob($directory . "*");
            $frontThemes = array();
            $i           = 0;
            foreach ($files as $file) {
                if (is_dir($file)) {
                    $frontThemes[$i] = basename($file);
                    $i++;
                }
            }

            //List of backend themes
            $directory  = "web/templates/backend/";
            $files      = glob($directory . "*");
            $backThemes = array();
            $i          = 0;
            foreach ($files as $file) {
                if (is_dir($file)) {
                    $backThemes[$i] = basename($file);
                    $i++;
                }
            }

            //List of timezones
            $regions   = array(
                'Africa' => DateTimeZone::AFRICA,
                'America' => DateTimeZone::AMERICA,
                'Antarctica' => DateTimeZone::ANTARCTICA,
                'Asia' => DateTimeZone::ASIA,
                'Atlantic' => DateTimeZone::ATLANTIC,
                'Europe' => DateTimeZone::EUROPE,
                'Indian' => DateTimeZone::INDIAN,
                'Pacific' => DateTimeZone::PACIFIC
            );
            $timezones = array();
            foreach ($regions as $name => $mask) {
                $zones = DateTimeZone::listIdentifiers($mask);
                foreach ($zones as $timezone) {
                    // Lets sample the time there right now
                    $time = new DateTime(NULL, new DateTimeZone($timezone));
                    // Us dumb Americans can't handle millitary time
                    $ampm = $time->format('H') > 12 ? ' (' . $time->format('g:i a') . ')' : '';
                    // Remove region name and add a sample time
                    $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' - ' . $time->format('H:i') . $ampm;
                }
            }

            //Current configurations
            $json      = file_get_contents('config/config.json'); // Read JSON file
            $json_data = json_decode($json, true); //Decode JSON

            $website_name        = htmlspecialchars($json_data['website_name']);
            $website_url         = htmlspecialchars($json_data['website_url']);
			$website_email         = htmlspecialchars($json_data['website_email']);
            $website_path        = htmlspecialchars($json_data['website_path']);
            $website_lang        = htmlspecialchars($json_data['website_lang']);
            $website_frontTheme  = htmlspecialchars($json_data['website_frontTheme']);
            $website_backTheme   = htmlspecialchars($json_data['website_backTheme']);
            $website_timezone    = htmlspecialchars($json_data['website_timezone']);
            $website_maintenance = htmlspecialchars($json_data['website_maintenance']);

            $this->_smarty->assign(array(
                "frontThemes" => $frontThemes,
                "backThemes" => $backThemes,
                "timezones" => $timezones,
                "website_name" => $website_name,
                "website_path" => $website_path,
				"website_email" => $website_email,
                "website_url" => $website_url,
                "website_lang" => $website_lang,
                "website_frontTheme" => $website_frontTheme,
                "website_backTheme" => $website_backTheme,
                "website_timezone" => $website_timezone,
                "website_maintenance" => $website_maintenance
            ));

            $this->_smarty->display("app/backend/views/configuration.tpl");
        }
    }

    /**
     * Allows the user(administrator) to manage and install backend and frontend templates
     */
    public function templates() {
        //We check if the user is an administrator
        if(!$this->_isAdministrator)
            throw new Exception("accessError_restricted");

        $templatesInfo = array();
        $j = 0;

        //List of frontend templates
        $templates = scandir("web/templates/frontend");
        for($i = 2; $i < count($templates); $i++) {
            if (is_dir("web/templates/frontend/" . $templates[$i]) && file_exists('web/templates/frontend/' . $templates[$i] . '/theme.json')) {
                $json = file_get_contents('web/templates/frontend/' . $templates[$i] . '/theme.json'); // Read JSON file
                $json_data = json_decode($json,true); //Decode JSON

                $templatesInfo[$j]['author'] = htmlspecialchars($json_data['author']);
                $templatesInfo[$j]['name'] = htmlspecialchars($json_data['name']);
                $templatesInfo[$j]['description'] = htmlspecialchars($json_data['description']);
                $templatesInfo[$j]['compatibility'] = htmlspecialchars($json_data['compatibility']);
                $templatesInfo[$j]['colors'] = htmlspecialchars($json_data['colors']);
                $templatesInfo[$j]['framework'] = htmlspecialchars($json_data['framework']);
                $templatesInfo[$j]['html'] = htmlspecialchars($json_data['html']);
                $templatesInfo[$j]['css'] = htmlspecialchars($json_data['css']);
                $templatesInfo[$j]['type'] = htmlspecialchars($json_data['type']);
                $templatesInfo[$j]['preview'] = (file_exists('web/templates/frontend/' . $templates[$i] . '/images/preview.png') ? 'web/templates/frontend/' . $templates[$i] . '/images/preview.png' : "web/templates/no-preview.png");
                $templatesInfo[$j]['removeTemplateURL'] = $this->_path . '/backend/templates/remove/frontend/' . $templates[$i];
                $templatesInfo[$j]['isRemovable'] = ($templates[$i] == Config::getFrontendTheme() ? false : true);

                $j++;
            }
        }

        //List of backend templates
        $templates = scandir("web/templates/backend");
        for($i = 2; $i < count($templates); $i++) {
            if (is_dir("web/templates/backend/" . $templates[$i]) && file_exists('web/templates/backend/' . $templates[$i] . '/theme.json')) {
                $json = file_get_contents('web/templates/backend/' . $templates[$i] . '/theme.json'); // Read JSON file
                $json_data = json_decode($json,true); //Decode JSON

                $templatesInfo[$j]['author'] = htmlspecialchars($json_data['author']);
                $templatesInfo[$j]['name'] = htmlspecialchars($json_data['name']);
                $templatesInfo[$j]['description'] = htmlspecialchars($json_data['description']);
                $templatesInfo[$j]['compatibility'] = htmlspecialchars($json_data['compatibility']);
                $templatesInfo[$j]['colors'] = htmlspecialchars($json_data['colors']);
                $templatesInfo[$j]['framework'] = htmlspecialchars($json_data['framework']);
                $templatesInfo[$j]['html'] = htmlspecialchars($json_data['html']);
                $templatesInfo[$j]['css'] = htmlspecialchars($json_data['css']);
                $templatesInfo[$j]['type'] = htmlspecialchars($json_data['type']);
                $templatesInfo[$j]['preview'] = (file_exists('web/templates/backend/' . $templates[$i] . '/images/preview.png') ? 'web/templates/backend/' . $templates[$i] . '/images/preview.png' : "web/templates/no-preview.png");
                $templatesInfo[$j]['removeTemplateURL'] = $this->_path . '/backend/templates/remove/backend/' . $templates[$i];
                $templatesInfo[$j]['isRemovable'] = ($templates[$i] == Config::getFrontendTheme() ? false : true);

                $j++;
            }
        }

        $this->_smarty->assign(array(
            "templates" => $templatesInfo
        ));

        $this->_smarty->display("app/backend/views/templates.tpl");
    }

    /**
     * Allows the user(administrator) to remove/uninstall a specific template
     * @param array $args Saves template type(backend or frontend) and name from the URL
     */
    public function removeTemplate($args) {
        //We check if the user is an administrator
        if(!$this->_isAdministrator)
            throw new Exception("accessError_restricted");

        //We cbeck if the template exists
        if(!file_exists('web/templates/' . $args['type'] . '/' . $args['template'])) {
            header('Location: ' . $this->_path . '/backend/templates');
            exit();
        }

        ///We check if the template can be removed
        if($args['type'] == 'backend' AND $args['template'] == Config::getBackendTheme()) {
            header('Location: ' . $this->_path . '/backend/templates');
            exit();
        } elseif($args['type'] == 'frontend' AND $args['template'] == Config::getFrontendTheme()) {
            header('Location: ' . $this->_path . '/backend/templates');
            exit();
        }

        //We delete the template
        Config::deleteFiles('web/templates/' . $args['type'] . '/' . $args['template']);

        setcookie('module_successMsg', "templateDeleted", time() + 365*24*3600, '/', null, false, true);
        header("location:".  $this->_path . "/backend/templates");
        exit();
    }

    /**
     * Uploads and installs a new template
     */
    public function uploadTemplate() {
        try {
            //We check if the user is an administrator
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            //We check if a template has been uploaded
            if (isset($_FILES['template']) && $_FILES['template']['size'] > 0) {
                $folder = 'web/templates/'; //Temporarily stored in the templates folder

                //We verify if extension is valid
                $extension = strrchr($_FILES['template']['name'], '.');
                if ($extension != '.zip')
                    throw new Exception("incorrectFileFormat");

                //We upload the file
                move_uploaded_file($_FILES['template']['tmp_name'], $folder . $_FILES['template']['name']);

                //We unzip the file
                $zip = new ZipArchive() ;
                $zip->open($folder . $_FILES['template']['name']);
                $zip->extractTo($folder);
                $template = explode('/', $zip->getNameIndex(0))[0]; //We save the name of the template folder
                $zip->close();
                unlink($folder . $_FILES['template']['name']); //We delete the ZIP file

                //We check if the folder has been correctly extracted
                if(!is_dir($folder . $template))
                    throw new Exception("invalidTemplateStructure");

                //We check if the folder name is correctly formatted
                if(!ctype_alnum($template))
                    throw new Exception("invalidTemplateName");

                //We check if the theme.json file exists
                if(!file_exists($folder . $template . '/theme.json'))
                    throw new Exception("jsonFileNotFound");

                //We need to check if template is for the backend or the frontend interface --> Done using the theme.json file
                $json = file_get_contents('web/templates/' . $template . '/theme.json'); // Read JSON file
                $json_data = json_decode($json,true); //Decode JSON
                if($json_data['type'] == 'Backend')
                    $finalFolder = $folder . 'backend/';
                elseif($json_data['type'] == 'Frontend')
                    $finalFolder = $folder . 'frontend/';
                else
                    throw new Exception("templateTypeNotSpecified");

                //If template is valid, we move it to the directory where it belongs
                rename($folder . $template, $finalFolder . $template);

                setcookie('module_successMsg', "templateUploaded", time() + 365 * 24 * 3600, '/', null, false, true);
                header("location:".  $this->_path . "/backend/templates");
                exit();
            } else 
				throw new Exception("noTemplateSubmitted");
        } catch(Exception $e) {
            //If file was uploaded and unzipped and an error occured, we have to delete the unzipped folder since it is useless
			if(file_exists($folder . $template))
				Config::deleteFiles($folder . $template);

            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
            header("location:".  $this->_path . "/backend/templates");
            exit();
        }
    }

    /**
     * Allows user(moderator or administrator) to manage modules
     */
    public function modules() {
        //List of modules
        $modulesInfo = array();
        $j = 0;
        $modules = scandir("app/modules");
        for($i = 2; $i < count($modules); $i++) {
            //We check if the module is inside a folder and if the module.json file exists
            if (is_dir("app/modules/" . $modules[$i]) && file_exists('app/modules/' . $modules[$i] . '/module.json')) {
                $json = file_get_contents('app/modules/' . $modules[$i] . '/module.json'); // Read JSON file
                $json_data = json_decode($json,true); //Decode JSON

                $modulesInfo[$j]['author'] = htmlspecialchars($json_data['author']);
                $modulesInfo[$j]['name'] = htmlspecialchars($json_data['name']);
                $modulesInfo[$j]['description'] = htmlspecialchars($json_data['description']);
                $modulesInfo[$j]['compatibility'] = htmlspecialchars($json_data['compatibility']);
                $modulesInfo[$j]['php'] = htmlspecialchars($json_data['php']);
                $modulesInfo[$j]['dependencies'] = $json_data['modules_dependencies'];
                $modulesInfo[$j]['removeModuleURL'] = $this->_path . '/backend/modules/uninstall/' . $modules[$i];

                //We check if the module can be configured from the backend interface
                if(file_exists('app/modules/' . $modules[$i] . '/' . ucfirst($modules[$i]) . 'BackController.php'))
                    $modulesInfo[$j]['manageModuleURL'] = $this->_path . '/backend/modules/manage/' . $modules[$i];
                else
                    $modulesInfo[$j]['manageModuleURL'] = null;

                //We check if the module is activated
                if($json_data['activated'] == 1) {
                    $modulesInfo[$j]['activated'] = true;
                    $modulesInfo[$j]['changeModuleStatusURL'] = $this->_path . '/backend/modules/deactivate/' . $modules[$i];
                } else {
                    $modulesInfo[$j]['activated'] = false;
                    $modulesInfo[$j]['changeModuleStatusURL'] = $this->_path . '/backend/modules/activate/' . $modules[$i];
                }

                //We check if the module is an official module
                $json = file_get_contents('http://expressif.net/zirkon/official-modules.json');
                $json_data = json_decode($json, true);
                if(in_array($modules[$i], $json_data['modules'])) {
                    $modulesInfo[$j]['isOfficial'] = true;
                } else
                    $modulesInfo[$j]['isOfficial'] = false;

                $j++;
            }
        }

        $this->_smarty->assign(array("modules" => $modulesInfo));
        $this->_smarty->display("app/backend/views/modules.tpl");
    }

    /**
     * Allows the user(administrator) to chose between 2 types of uninstallation (complete or normal)
     * @param array $args Saves module name from the URL
     */
    public function uninstallModuleConfirmation($args) {
       try {
           //We check if the user is an administrator
           if(!$this->_isAdministrator)
               throw new Exception("accessError_restricted");

           //We check if the module exists
           if (!file_exists("app/modules/" . $args['module']))
               throw new Exception("moduleDoesNotExist");

           $this->_smarty->assign(array("module" => $args['module']));

           $this->_smarty->display("app/backend/views/uninstallModule.tpl");
       } catch(Exception $e) {
           header("location:" . $this->_path . "/backend/modules");
           exit();
       }
    }

    /**
     * Allows the user(administrator) to uninstall a specific module
     * @param array $args Saves module name from URL
     */
    public function uninstallModule($args) {
        try {
            //We check if the user is an administrator
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            //We check if the module exists
            if(!file_exists("app/modules/" . $args['module']))
                throw new Exception("moduleDoesNotExist");

            //We check if the module is an official module
            $json = file_get_contents('http://expressif.net/zirkon/official-modules.json');
            $json_data = json_decode($json, true);
            if(in_array($args['module'], $json_data['modules']))
                throw new Exception("officialModulesCannotBeRemoved");

            //We check if the module has any dependencies
            $modules = scandir("app/modules");
            for($i = 2; $i < count($modules); $i++) {
                if (is_dir("app/modules/" . $modules[$i]) && file_exists('app/modules/' . $modules[$i] . '/module.json')) {
                    $json = file_get_contents('app/modules/' . $modules[$i] . '/module.json'); // Read JSON file
                    $json_data = json_decode($json,true); //Decode JSON

                    if(in_array($args['module'], $json_data['modules_dependencies']))
                        throw new Exception("moduleHasDependencies");
                }
            }

            //If the user decides to perform a complete uninstall of the module (Remove related DB tables)
            if($args['uninstallation'] == 'complete') {
                $db = Config::getDBInfos();
                $prefix = Config::getDBTablesPrefix() . $args['module'] . '_'; //Prefix of the table

                //Convention: Tables name begins with the name of the module --> module_
                //First we search all the tables that begin with the name of the module as a prefix and return the equivalent drop statement
                $query = $db->prepare('SELECT CONCAT(\'DROP TABLE \',table_name,\';\') AS drop_statement FROM information_schema.tables WHERE table_name LIKE :prefix');
                $query->bindValue('prefix', $prefix . '%', PDO::PARAM_STR);
                $query->execute();

                //We execute all the drop statements returned
                while($data = $query->fetch())
                    $drop_query = $db->exec($data['drop_statement']);
            }

            //We remove the module
            Config::deleteFiles('app/modules/' . $args['module']);

            setcookie('module_successMsg', 'moduleHasBeenUninstalled', time() + 365 * 24 * 3600, '/', null, false, true);
            header("location:" . $this->_path . "/backend/modules");
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
            header("location:" . $this->_path . "/backend/modules");
            exit();
        }
    }

    /**
     * Activates or deactivates a module.
     * When deactivated a module cannot be accessed
     * @param $args Saves module and action to perform (activate or deactivate) from the URL
     */
    public function changeModuleStatus($args) {
        try {
            //We check if the user is an administrator
            if (!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            //We check if the module exists
            if (!file_exists("app/modules/" . $args['module']))
                throw new Exception("moduleDoesNotExist");
			
			//We check if the module has any dependencies
            $modules = scandir("app/modules");
            for($i = 2; $i < count($modules); $i++) {
                if (is_dir("app/modules/" . $modules[$i]) && file_exists('app/modules/' . $modules[$i] . '/module.json')) {
                    $json = file_get_contents('app/modules/' . $modules[$i] . '/module.json'); // Read JSON file
                    $json_data = json_decode($json,true); //Decode JSON

                    if(in_array($args['module'], $json_data['modules_dependencies']))
                        throw new Exception("moduleHasDependencies");
                }
            }

            //We deactivate the module
            if(file_exists('app/modules/' . $args['module'] . '/module.json')) {
                $json      = file_get_contents('app/modules/' . $args['module'] . '/module.json'); // Read JSON file
                $json_data = json_decode($json, true); //Decode JSON
                if ($args['action'] == 'activate')
                    $json_data['activated'] = "1";
                else
                    $json_data['activated'] = "0";

                file_put_contents('app/modules/' . $args['module'] . '/module.json', json_encode($json_data));
            } else
                throw new Exception("invalidModuleStructure");

            setcookie('module_successMsg', "moduleStatusHasBeenChanged", time() + 365*24*3600, '/', null, false, true);
            header("location:" . $this->_path . "/backend/modules");
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
            header("location:" . $this->_path . "/backend/modules");
            exit();
        }
    }

    /**
     * Allows the user(administrator) to upload and install a new module
     */
    public function installModule() {
        try {
            //We check if the user is an administrator
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                //We check if a module has been uploaded
                if (isset($_FILES['module']) && $_FILES['module']['size'] > 0) {
                    $folder = 'app/modules/'; //Folder where the module will be stored

                    //We verify if extension is valid
                    $extension = strrchr($_FILES['module']['name'], '.');
                    if ($extension != '.zip')
                        throw new Exception("incorrectFileFormat");

                    //We upload the file
                    move_uploaded_file($_FILES['module']['tmp_name'], $folder . $_FILES['module']['name']);

                    //We unzip the file
                    $zip = new ZipArchive() ;
                    $zip->open($folder . $_FILES['module']['name']);
                    $zip->extractTo($folder);
                    $module = explode('/', $zip->getNameIndex(0))[0]; //We save the name of the module folder
					
                    $zip->close();
                    unlink($folder . $_FILES['module']['name']); //We delete the ZIP file

                    //We check if the folder has been correctly extracted
                    if(!is_dir($folder . $module))
                        throw new Exception("invalidModuleStructure");

                    //We check if the folder name is correctly formatted
                    if(!ctype_alnum($module))
                        throw new Exception("invalidModuleName");

                    //We check if the module.json file exists
                    if(!file_exists($folder . $module . '/module.json'))
                        throw new Exception("jsonFileNotFound");

                    //We check if any SQL file exists
                    foreach (glob($folder . $module . "/*.sql") as $file) {
                        Config::DBTables_addPrefix($file); //We edit the SQL file in order to add the prefix to the tables

                        //We upload the tables
                        $db = Config::getDBInfos();
                        $fileContent = file_get_contents($file);
                        $query = $db->prepare($fileContent);
                        $query->execute();

                        //We remove the SQL file
                        unlink($file);
                    }

                    $this->_smarty->assign(array("module_successMsg" => 'moduleUploaded'));
                } else {
                    throw new Exception("noModuleSubmitted");
                }
            }
        } catch(Exception $e) {
			//If file was uploaded and unzipped and an error occured, we have to delete the unzipped folder since it is useless
			if(file_exists($folder . $template))
				Config::deleteFiles($folder . $module);
			
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $this->_smarty->display("app/backend/views/installModule.tpl");
        }
    }
}