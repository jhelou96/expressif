<?php
namespace app\modules\messaging;

use config\Exception;
use vendor\Pagination;
use vendor\editor\BBCode;

use app\modules\members\models\Member;
use app\modules\messaging\models\Thread;
use app\modules\messaging\models\Message;

use app\modules\messaging\models\ThreadsManager;
use app\modules\messaging\models\MessagesManager;
use app\modules\members\models\MembersManager;

use app\modules\Module;

/**
 * Class MessagingFrontController
 * Messaging module controller for the frontend interface
 * @package app\modules\messaging
 */
class MessagingFrontController extends Module {
    /**
     * @var ThreadsManager
     * DB manager for the messaging thread entity
     */
    private $_threadsManager;

    /**
     * @var MessagesManager
     * DB manager for the messaging messages entity
     */
    private $_messagesManager;

    /**
     * @var MembersManager
     * DB manager for the member entity
     */
    private $_membersManager;

    /**
     * MessagingFrontController constructor.
     */
    public function __construct() {
        $this->run();

        //We check if the user is connected
        if(!$this->_isConnected) {
            header('Location: ' . $this->_path . '/members/login');
            exit();
        }

        //We load the managers related to the module
        $this->_threadsManager = new ThreadsManager();
        $this->_messagesManager = new MessagesManager();
        $this->_membersManager = new MembersManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Displays the user list of threads
     * @param array $args Saves member username and page number from the URL
     */
    public function inbox($args) {
		try {
			$pagination = new Pagination();
			$pagination->setnbRecords($this->_threadsManager->getNbThreads($this->_member));
			
			if(isset($args['page'])) 
				$pagination->setActualPage($args['page']);
			
			$pagination->execute();
				
			$threads = $this->_threadsManager->getListThreads($this->_member, $pagination->sqlLimit());
			
			$this->_smarty->assign(array(
				"messaging_threads" => $threads,
				"pagination" => $pagination->parse()
			));
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
		
		$this->_smarty->display("app/modules/messaging/views/frontend/tpl/inbox.tpl");
	}

    /**
     * Displays the list of messages of a thread for the user(participant)
     * @param array $args Saves thread ID and page number from the URL
     */
    public function readThread($args) {
		try {
		    //We check if thread exists
			$thread = new Thread();
			$thread->setId($args['idThread']);
			$thread = $this->_threadsManager->get($thread);
			if(is_null($thread))
			    throw new Exception("accessError_404");
			
			//We check if the user is a participant
			if(!$this->_threadsManager->checkIfParticipant($this->_member, $thread))
                throw new Exception("accessError_restricted");
			
			$pagination = new Pagination();
			$pagination->setnbRecords($this->_messagesManager->getNbMessages($thread));
			
			if(isset($args['page'])) 
				$pagination->setActualPage($args['page']);
			
			$pagination->execute();
			
			$this->_smarty->assign(array(
				"messaging_threadTitle" => $thread->getTitle(),
				"messaging_threadID" => $thread->getId()
			));

			//We check if the user posted a new message
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                $threadMessage = $_POST['messaging_messageContent'];

                $message = new Message();
                $message->setIdThread($thread->getId());
                $message->setContent($threadMessage);
                $message->setIdAuthor($this->_member->getId());

                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, "fr_FR");
                $date = strftime(date('Y-m-d H:i:s', time()));

                $message->setExpeditionDate($date);

                //We add the message and save its position
                $msgInTopic = $this->_messagesManager->add($message);

                //We redirect the user to the new answer in the correct page
                $pagination->setNbRecords($pagination->getNbRecords()+1);
                $pagination->execute();

                header('Location: ' . $this->_path . '/messaging/thread/' . $thread->getId() . (($pagination->getNbPages() > 1) ? '/page/' . $pagination->getNbPages() : '') . '#m' . $msgInTopic);
                exit();
            }
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
            $messages = $this->_messagesManager->get($thread, $pagination->sqlLimit());

            //We update the views for the user
            $this->_threadsManager->updateThreadView($this->_member, $thread);

            //We parse the BBCode of each message
            for($i = 0; $i < count($messages); $i++)
                $messages[$i]['content'] = $this->_bbcode->parseBBCode($messages[$i]['content']);

            //Statistics about the thread
            $nbMessages = $this->_messagesManager->getNbMessages($thread);
            $threadCreationDate = $this->_messagesManager->getThreadCreationDate($thread);
            $nbParticipants = $this->_threadsManager->getNbParticipants($thread);
            $listParticipants = $this->_threadsManager->getListParticipants($thread);

            $this->_smarty->assign(array(
                "messaging_nbMessages" => $nbMessages,
                "messaging_threadCreationDate" => $threadCreationDate,
                "messaging_nbParticipants" => $nbParticipants,
                "messaging_listParticipants" => $listParticipants,
                "messaging_messages" => $messages,
                "pagination" => $pagination->parse(),
                "isLastPage" => (($pagination->getActualPage() == $pagination->getNbPages()) ? true : false) //We check if the user is at the last page of the topic to display the text editor
            ));

            $this->_smarty->display("app/modules/messaging/views/frontend/tpl/readThread.tpl");
        }
	}

    /**
     * Allows the user(member) to create a new thread
     * @param array $args Saves member username from the URL if the user is sending a message to a specific member
     */
    public function newThread($args) {
		try {
		    //We check if the user is sending a message to a specific user
            if(isset($args['username']))
                $this->_smarty->assign(array("messaging_username" => $args['username'])); //We send the username to Smarty so we display it directly in the text input of the form

			//We check if the form has been submitted
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$thread = new Thread();
				$message = new Message();
				
				$message->setIdAuthor($this->_member->getId());
				$message->setContent($_POST['messaging_messageContent']);
				
				date_default_timezone_set('Europe/Paris');
				setlocale(LC_TIME, "fr_FR");
				$date = strftime(date('Y-m-d H:i:s', time()));
				
				$message->setExpeditionDate($date);
				
				//We check if some recipients are provided or an exception will be thrown
				if(empty($_POST['messaging_threadParticipants']))
					throw new Exception("recipientsMustBeSpecified");
				
				//We get the ID of the participants
				$threadParticipants = str_replace(" ", "", $_POST['messaging_threadParticipants']); //We save the string containing the username of the participants and remove the spaces
				$usernameParticipants = explode(',', $threadParticipants);
				
				//If the user added his username in the list of participants, we remove it
				if(($key = array_search($this->_member->getUsername(), $usernameParticipants)) !== false) {
					unset($usernameParticipants[$key]);
				}

				$usernameParticipants = array_unique($usernameParticipants); //We remove duplicates
				$usernameParticipants = array_values($usernameParticipants); //We reindex array keys

                //If no valid recipients have been specified
                if(count($usernameParticipants) == 0)
                    throw new Exception("recipientsMustBeSpecified");

				$idParticipants = '';
				$j = 0;
				
				for($i = 0; $i < count($usernameParticipants); $i++) {
					$participant = new Member();
					$participant->setUsername($usernameParticipants[$i]);
					$participant = $this->_membersManager->get($participant);
					
					if(is_null($participant))
						throw new Exception("usernameDoesntExist");
					
					//We concatenate the IDs separated by a coma
					$idParticipants .= $participant->getId() . ',';
					$j++;
				}
				
				//We add the user who posted the thread as a participant and remove the last coma
				rtrim($idParticipants, ",");
				$idParticipants .= $this->_member->getId();
				
				$thread->setIdAuthor($this->_member->getId());
				$thread->setIdParticipants($idParticipants);
				$thread->setTitle($_POST['messaging_threadTitle']);
				
				$thread->setId($this->_threadsManager->add($thread));
				
				$message->setIdThread($thread->getId());
				$this->_messagesManager->add($message);

				header('Location: ' . $this->_path . '/messaging/thread/' . $thread->getId() . '');
				exit();
			}
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
		    if(isset($_POST['messaging_messageContent']) AND !empty($_POST['messaging_messageContent']))
                $this->_smarty->assign(array("messaging_messageContent" => $_POST['messaging_messageContent']));
            if(isset($_POST['messaging_threadTitle']) AND !empty($_POST['messaging_threadTitle']))
                $this->_smarty->assign(array("messaging_threadTitle" => $_POST['messaging_threadTitle']));
            if(isset($_POST['messaging_threadParticipants']) AND !empty($_POST['messaging_threadParticipants']))
                $this->_smarty->assign(array("messaging_threadParticipants" => $_POST['messaging_threadParticipants']));

            $this->_smarty->display("app/modules/messaging/views/frontend/tpl/newThread.tpl");
        }
	}

    /**
     * Allows the user(participant) to delete a thread
     * @param array $args Saves thread ID from the URL
     */
    public function deleteThread($args) {
		try {
            //We check if the thread exists
			$thread = new Thread();
			$thread->setId($args['idThread']);
			$thread = $this->_threadsManager->get($thread);
			if(is_null($thread))
			    throw new Exception("accessError_404");
			
			//We check if the user is not a participant
			if(!$this->_threadsManager->checkIfParticipant($this->_member, $thread))
                throw new Exception("accessError_restricted");

			$this->_threadsManager->remove($thread, $this->_member);
			
			setcookie("module_successMsg", "threadDeleted", time() + 365*24*3600, '/', null, false, true);
		} finally {
			header('Location: ' . $this->_path . '/messaging');
			exit();
		}
	}

    /**
     * Allows the user(participant) to set a thread in favorites
     * @param array $args Saves thread ID from the URL
     */
    public function threadFavorite($args) {
		try {
            //We check if the thread exists
            $thread = new Thread();
            $thread->setId($args['idThread']);
            $thread = $this->_threadsManager->get($thread);
            if(is_null($thread))
                throw new Exception("accessError_404");

            //We check if the user is not a participant
            if(!$this->_threadsManager->checkIfParticipant($this->_member, $thread))
                throw new Exception("accessError_restricted");
			
			//We check if the thread is set as favorite
			if(!$this->_threadsManager->checkIfFavorite($this->_member, $thread))
				$this->_threadsManager->setAsFavorite($this->_member, $thread);
			else
				$this->_threadsManager->removeFromFavorite($this->_member, $thread);
		} finally{	
			header('Location: ' . $this->_path . '/messaging');
			exit();
		}
	}
}