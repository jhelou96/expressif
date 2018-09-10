<?php
namespace app\modules\members;

use config\Config;
use config\Exception;
use vendor\Achievements;
use vendor\Mail;

use app\modules\members\models\Member;

use app\modules\members\models\MembersManager;
use app\modules\forum\models\TopicsManager;
use app\modules\forum\models\MessagesManager;
use app\modules\articles\models\ArticlesManager;

use app\modules\Module;

/**
 * Class MembersFrontController
 * Member module controller for the frontend interface
 * @package app\modules\members
 */
class MembersFrontController extends Module {
    /**
     * @var MembersManager
     * DB manager for the member entity
     */
    private $_membersManager;

    /**
     * @var TopicsManager
     * DB manager for the forum topic entity
     */
    private $_topicsManager;

    /**
     * @var ArticlesManager
     * DB manager for the article entity
     */
    private $_articlesManager;

    /**
     * @var MessagesManager
     * DB manager for the forum message entity
     */
    private $_messagesManager;

    /**
     * MembersFrontController constructor.
     */
    public function __construct() {
        $this->run();

        //We load the managers related to this module
        $this->_membersManager = new MembersManager();
        $this->_topicsManager = new TopicsManager();
        $this->_messagesManager = new MessagesManager();
        $this->_articlesManager = new ArticlesManager();
    }

    /**
     * Allows the user to login to his account
     */
    public function login() {
		try {
			//If the user is logged in, he can't access the login page
			if($this->_isConnected) {
				header('Location: ' . $this->_path . '/');
				exit();
			}

			//If the user checked the "remember me" option, we send his credentials to smarty to display them
            if(isset($_COOKIE['login_rememberUsername']) && isset($_COOKIE['login_rememberPassword'])) {
                $this->_smarty->assign(array(
                    "login_remember" => true,
                    "login_username" => $_COOKIE['login_rememberUsername'],
                    "login_password" => $_COOKIE['login_rememberPassword']
                ));
            }

			//We check if the login form has been submitted
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$member = new Member();
				
				$member->setUsername($_POST['login_username']);
				$member->setPassword($_POST['login_password']);

                //We check if username exists
                if(!$this->_membersManager->checkIfUsernameExists($member))
                    throw new Exception("usernameDoesntExist");

                //We check if username/password combination is valid
                if(!$this->_membersManager->checkUsernamePasswordCombination($member))
                    throw new Exception("passwordDoesntMatch");

                //We check if member is authorized to login
                if(!$this->_membersManager->checkIfLoginAllowed($member))
                    throw new Exception("loginNotAllowed");

                //If no error, we create a new session
                $member = $this->_membersManager->get($member);
                session_regenerate_id();
                $_SESSION['idUser'] = $member->getId();
                $_SESSION['username'] = $member->getUsername();

                //We update the last login date
                $this->_membersManager->updateLastLoginDate($member);

				//Once the user is logged in, we check if he checked the "remember me" option
                if(isset($_POST['login_remember'])) {
                    setcookie('login_rememberUsername', $member->getUsername(), time() + 365*24*3600, '/', null, false, true);
                    setcookie('login_rememberPassword', $_POST['login_password'], time() + 365*24*3600, '/', null, false, true);
                } else {
                    if(isset($_COOKIE['login_rememberUsername']))
                        setcookie('login_rememberUsername', '', -1);
                    if(isset($_COOKIE['login_rememberPassword']))
                        setcookie('login_rememberPassword', '', -1);
                }

				header("location:".  $_COOKIE['redirectionPage']);
				exit();
			} else { //If not, we save the page where the user comes from in a cookie so we can redirect him to it after he log in
				if(isset($_SERVER['HTTP_REFERER']) AND strpos($_SERVER['HTTP_REFERER'], $_SERVER["HTTP_HOST"]) !== false)
					setcookie('redirectionPage', $_SERVER['HTTP_REFERER'], time() + 365*24*3600, '/', null, false, true);
				else
					setcookie('redirectionPage', $this->_path . '/', time() + 365*24*3600, '/', null, false, true);
			}
		} catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $this->_smarty->display("app/modules/members/views/frontend/tpl/login.tpl");
        }
    }

    /**
     * Allows the user to reset his password
     * @param array $args Saves the validation token from the URL
     */
    public function resetPassword($args) {
        try {
            //If the user is logged in, he can't access the login page
            if($this->_isConnected) {
                header('Location: ' . $this->_path . '/');
                exit();
            }

            //We check if the token is valid
            if(!$this->_membersManager->checkIfTokenValid($args['token']))
                throw new Exception("accessError_404");

            //We check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if($_POST['resetPassword_password'] != $_POST['resetPassword_confirmation'])
                    throw new Exception("passwordShouldMatchConfirmation");

                $member = new Member();
                $member->setPassword($_POST['resetPassword_password']);

                $this->_membersManager->resetPassword($member, $args['token']);

                setcookie('module_successMsg', "passwordResetSuccessfully", time() + 365*24*3600, '/', null, false, true);
                header('Location: ' . $this->_path . '/members/login');
                exit();
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $this->_smarty->assign(array("resetPassword_token" => $args['token']));
            $this->_smarty->display("app/modules/members/views/frontend/tpl/resetPassword.tpl");
        }
    }

    /**
     * Allows the user to make a password reset request
     */
    public function requestPasswordReset() {
        try {
            //If the user is logged in, he can't access the login page
            if($this->_isConnected) {
                header('Location: ' . $this->_path . '/');
                exit();
            }

            $member = new Member();
            $member->setEmail($_POST['passwordForgotten_email']);

            //We check if the email provided exists
            if ($this->_membersManager->checkIfEmailExists($member)) {
                //We generate a token and save it in the DB
                $member = $this->_membersManager->get($member);
                $token  = $this->_membersManager->generateVerifToken($member);

                //We send the token by mail
                $mail = new Mail();
                $mail->sendPasswordResetMail($member->getEmail(), $member->getUsername(), $token);

                setcookie('module_successMsg', "passwordResetMailSent", time() + 365 * 24 * 3600, '/', null, false, true);
                header("location:" . $this->_path . "/members/login");
                exit();
            } else {
                setcookie('module_errorMsg', "passwordResetEmailDoesntExist", time() + 365 * 24 * 3600, '/', null, false, true);
                header("location:" . $this->_path . "/members/login");
                exit();
            }
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
            header("location: " . $this->_path . "/members/login");
            exit();
        }
    }

    /**
     * Allows the user to register
     */
    public function register() {
		try {
			//If the user is logged in, he can't access the registration page
			if($this->_isConnected) { 
				header('Location: ' . $this->_path . '/');
				exit();
			}
				
			//We check if the registration form has been submitted
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$member = new Member();
				
				$member->setUsername($_POST['register_username']);
				
				//We check if the username already exists
				if($this->_membersManager->checkIfUsernameExists($member)) {
					throw new Exception("usernameAlreadyExists");
				} else {
					//If no errors we send the value of the username to Smarty so the user doesn't have to type it again if there has been errors in other inputs
					$this->_smarty->assign(array("register_username" => $member->getUsername()));
				}
				
				$member->setEmail($_POST['register_email']);
					
				//We check if the email address already exists
				if($this->_membersManager->checkIfEmailExists($member)) {
					throw new Exception("emailAlreadyExists");
				} else {
					//If no errors we send the value of the email to Smarty so the user doesn't have to type it again if there has been errors in other inputs
					$this->_smarty->assign(array("register_email" => $member->getEmail()));
				}
				
				$member->setPassword($_POST['register_password']);
				
				//We check if the user has accepted the rules
				if(!isset($_POST['register_rules']))
					throw new Exception("rulesMustBeAccepted");
				
				//If no errors, the user can be registered
				$token = $this->_membersManager->add($member);

				//We send a mail to the user to verify his email address
				$mail = new Mail();
				$mail->sendRegistrationMail($member->getEmail(), $member->getUsername(), $token);
				
				$this->_smarty->assign(array("module_successMsg" => "registrationSucceeded"));
			}
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
            $this->_smarty->display("app/modules/members/views/frontend/tpl/register.tpl");
        }
    }

    /**
     * Validates the user registration by using a token sent by email
     * @param array $args Saves the validation token from the URL
     */
    public function registrationValidation($args) {
		try {
			//If the user is logged in, he can't access the validation page
			if($this->_isConnected) { 
				header('Location: ' . $this->_path . '/');
				exit();
			}
			
			$this->_membersManager->registrationValidation($args['token']);
			
			setcookie('module_successMsg', 'tokenProcessed', time() + 365*24*3600, '/', null, false, true);
		} catch(Exception $e) {
			setcookie('module_errorMsg', $e->getMessage(), time() + 365*24*3600, '/', null, false, true);
		}
		
		header("location: " . $this->_path . "/members/login"); 
		exit();
	}

    /**
     * Allows the user to logout and destroys his session
     */
    public function logout() {
		if(isset($_SESSION['idUser']))
			session_destroy();
		
		header('Location: ' . $this->_path . '/');
		exit();
	}

    /**
     * Displays the profile of the user
     * @param array $args Saves user username from the URL
     */
    public function profile($args) {
        //We check if member exists
        $member = new Member();
        $member->setUsername($args['username']);
        $member = $this->_membersManager->get($member);
        if(is_null($member))
            throw new Exception("accessError_404");

        //We check the member's achievements
        $achievements = new Achievements($member);
        $achievementsList = $achievements->getAchievementsList();

        //We check if the member is visiting his own profile
        if($this->_isConnected) {
            if($this->_member->getId() == $member->getId())
                $isMyProfile = true;
            else
                $isMyProfile = false;
        } else
            $isMyProfile = false;

        $this->_smarty->assign(array(
            "member_isMyProfile" => $isMyProfile,
            "member_id" => $member->getId(),
            "member_username" => $member->getUsername(),
            "member_level" => $member->getLevel(),
            "member_avatar" => $member->getAvatar(),
            "member_signature" => $member->getSignature(),
            "member_registrationDate" => Config::datetimeFormat($member->getRegistrationDate()),
            "member_lastConnectionDate" => Config::datetimeFormat($member->getLastConnectionDate()),
            "member_isConnected" => ((isset($_SESSION['idUser']) AND $member->getLastQuery() + 5*60 >  time()) ? true : false),
            "member_achievements" => $achievementsList,
            "messaging_isModuleActive" => Config::checkIfModuleActive("app/modules/messaging")
        ));

        //If forum module is active, we get some info about the member
        if(Config::checkIfModuleActive("app/modules/forum")) {
            $this->_smarty->assign(array(
                "forum_isModuleActive" => true,
                "forum_memberParticipation" => $this->_topicsManager->getMemberParticipation($member),
                "forum_memberNbTopicsCreated" => $this->_topicsManager->getNbTopicsCreated($member),
                "forum_memberNbMessagesPosted" => $this->_messagesManager->getNbMessagesPosted($member)
            ));
        } else
            $this->_smarty->assign(array("forum_isModuleActive" => false));

        //If articles module is active, we get some info about the member
        if(Config::checkIfModuleActive("app/modules/articles")) {
            $this->_smarty->assign(array(
                "articles_isModuleActive" => true,
                "articles_memberLatestArticlesPublished" => $this->_articlesManager->getListMemberValidatedArticles($member),
                "articles_memberNbArticlesPublished" => $this->_articlesManager->getNbArticlesPublished($member)
            ));
        } else
            $this->_smarty->assign(array("articles_isModuleActive" => false));

        $this->_smarty->display("app/modules/members/views/frontend/tpl/profile.tpl");
	}

    /**
     * Allows the user to modify his settings
     */
    public function settings() {
		try {
			//If the user is not logged in, he can't access the login page
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/');
				exit();
			}
			
			//We check if the form has been submitted
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				$this->_member->setEmail($_POST['settings_email']);
				$this->_member->setSignature($_POST['settings_signature']);
				
				if(!empty($_POST['settings_password'])) {
					if(!isset($_POST['settings_passwordConfirmation']) OR $_POST['settings_passwordConfirmation'] != $_POST['settings_password'])
						throw new Exception("passwordShouldMatchConfirmation");
					else {
						//Password Encryption
						$password = password_hash($member->getPassword(), PASSWORD_DEFAULT);
						$this->_member->setPassword($password);
					}
				}

				//We check if an avatar has been uploaded
                if(isset($_FILES['settings_avatar']) && $_FILES['settings_avatar']['size'] > 0) {
                    $folder = 'web/upload/';

                    //We verify if the format of the file is valid
                    $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                    $extension = strrchr($_FILES['settings_avatar']['name'], '.');
                    if(!in_array($extension, $extensions)) //if extensions not in array
                        throw new Exception("incorrectFileFormat");

                    //We rename the file using the timestamp function so we are assured to have a unique name
                    $fileWithoutExtension = pathinfo($_FILES['settings_avatar']['name'], PATHINFO_FILENAME);
                    $file = $fileWithoutExtension . time() . $extension;

                    if(move_uploaded_file($_FILES['settings_avatar']['tmp_name'], $folder . $file))
                        $this->_member->setAvatar('web/upload/' . $file);
                }
				
				$this->_membersManager->update($this->_member);
				
				$this->_smarty->assign(array("module_successMsg" => "settingsUpdated"));
			}
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
			$this->_smarty->assign(array(
				"member_email" => $this->_member->getEmail(),
				"member_avatar" => $this->_member->getAvatar(),
				"member_signature" => $this->_member->getSignature()
			));
		}
			
		$this->_smarty->display("app/modules/members/views/frontend/tpl/settings.tpl");
	}
}