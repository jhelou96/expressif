<?php

namespace config;

use \Smarty;

class Exception extends \Exception
{
    public function __construct($message, $code = 0, $previous = null) {
        $smarty = new Smarty();
        $smarty->setCompileDir('web/templates_c');

        $website_path = Config::getPath();
		$website_lang = Config::getLang();
		$webmaster_email = Config::getEmail();
		$website_frontTemplate = Config::getFrontendTheme();

        if($message == 'accessError_404') {
            $smarty->assign(array(
				"website_path" => $website_path,
				"website_lang" => $website_lang,
				"webmaster_email" => $webmaster_email
			));
            $smarty->display("web/templates/frontend/" . $website_frontTemplate . "/errors/404.tpl");
            exit();
        } elseif($message == 'accessError_restricted') {
           $smarty->assign(array(
				"website_path" => $website_path,
				"website_lang" => $website_lang,
				"webmaster_email" => $webmaster_email
			));
            $smarty->display("web/templates/frontend/" . $website_frontTemplate . "/errors/restrictedAccess.tpl");
            exit();
        } elseif($message == 'accessError_maintenance') {
            $smarty->assign(array(
				"website_path" => $website_path,
				"website_lang" => $website_lang,
				"webmaster_email" => $webmaster_email
			));
            $smarty->display("web/templates/frontend/" . $website_frontTemplate . "/errors/maintenance.tpl");
            exit();
        }

        parent::__construct($message, $code, $previous);
    }
}