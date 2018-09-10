<?php
namespace app\modules\contact;

use config\Config;
use config\Exception;

use app\modules\Module;

/**
 * Class ContactFrontController
 * Contact module controller for the frontend interface
 * @package app\modules\contact
 */
class ContactFrontController extends Module {
    /**
     * ContactFrontController constructor.
     */
    public function __construct() {
        //We run the module
        $this->run();
    }

    /**
     * Displays application frontend contact page
     */
    public function contact() {
		try {
			//We check if contact form has been submitted
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if(empty($_POST['contact_email']) OR empty($_POST['contact_subject']) OR empty($_POST['contact_message']))
					throw new Exception("allFieldsMustBeFilled");
				
				if(!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL))
					throw new Exception("invalidEmailAddress");
				
				$subject = $_POST['contact_subject'];
				$from = $_POST['contact_email'];
				$message = $_POST['contact_message'];
				$to = Config::getEmail();
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers .= "From: " . $from . "\r\n" . "Reply-To: " . $from . "\r\n" . "X-Mailer: PHP/" . phpversion();
				
				mail($to, $subject, $message, $headers);
				
				$this->_smarty->assign(array("module_successMsg" => "messageHasBeenSent"));
			}
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}

        $this->_smarty->display("app/modules/contact/views/frontend/tpl/contact.tpl");
	}
}