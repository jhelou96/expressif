<?php
namespace app\modules\messaging\models;

use config\Config;
use \PDO;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class ThreadsManager
 * DB manager for the messaging thread entity
 * @package app\modules\messaging\models
 */
class ThreadsManager {
    /**
     * @var PDO
     * PDO object used to communicate with the DB
     */
    private $_db;

    /**
     * @var string
     * DB tables prefix
     */
    private $_tablePrefix;

    /**
     * ThreadsManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores a thread in the DB and returns its ID
     * @param Thread $thread
     * @return int ID of the thread just stored
     */
    public function add(Thread $thread) {
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'messaging_threads(idAuthor, idParticipants, title) VALUES(:idAuthor, :idParticipants, :title)');
		$query->bindValue('idAuthor', $thread->getIdAuthor(), PDO::PARAM_STR);
		$query->bindValue('idParticipants', $thread->getIdParticipants(), PDO::PARAM_STR);
		$query->bindValue('title', $thread->getTitle(), PDO::PARAM_STR);
		$query->execute();
		
		return $this->_db->lastInsertId();
	}

    /**
     * Returns the list of thread of a specific member
     * @param Member $member
     * @param $sqlLimit SQL limit statement for the pagination system
     * @return array
     */
    public function getListThreads(Member $member, $sqlLimit) {
		$thread = new Thread();
		
		//We join the thread with the last message posted in it
		$query = $this->_db->prepare('SELECT T.*, M.content, M.msgInThread, M.expeditionDate FROM ' . $this->_tablePrefix . 'messaging_threads T JOIN ' . $this->_tablePrefix . 'messaging_messages M ON T.id = M.idThread WHERE M.msgInThread = (SELECT MAX(msgInThread) FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = T.id) AND (idParticipants LIKE :searchID OR idParticipants LIKE :searchIDFirst OR idParticipants LIKE :searchIDMiddle OR idParticipants LIKE :searchIDLast) ORDER BY M.msgInThread DESC ' . $sqlLimit);
		$query->bindValue(':searchID', $member->getId(), PDO::PARAM_STR); //If there is only 1 ID/participant
		$query->bindValue(':searchIDFirst', $member->getId() . ",%", PDO::PARAM_STR); //We check if the ID of the member is in first position
		$query->bindValue(':searchIDMiddle', "%," . $member->getId() . ",%", PDO::PARAM_STR); //We check if the ID of the member between other IDs
		$query->bindValue(':searchIDLast', "%," . $member->getId(), PDO::PARAM_STR); //We check if the ID of the member is in last position
		$query->execute();
		
		$threads = array();
		$i = 0;
		
		while($data = $query->fetch()) {
			$thread->hydrate($data);
			
			$threads[$i]['id'] = $thread->getId();
			$threads[$i]['title'] = $thread->getTitle();
			$threads[$i]['content'] = substr($data['content'], 0, 20) . '...';
			$threads[$i]['lastMsgDate'] = Config::dateFormat($data['expeditionDate']);
			
			$author = new Member();
			$membersManager = new MembersManager();
			$author->setId($data['idAuthor']);
			$author = $membersManager->get($author);
					
			$threads[$i]['author'] = $author->getUsername();
			
			//We check if the thread is set as favorite
			if($this->checkIfFavorite($member, $thread))
				$threads[$i]['favorite'] = true;
			else
				$threads[$i]['favorite'] = false;
			
			//We check if there is any new message that the user didn't see yet
			$threads[$i]['hasNewMsg'] = $this->checkThreadView($member, $thread);
				
			$i++;
		}
		
		return $threads;
	}

    /**
     * Removes a member from the list of participants of a specific thread and delete the thread if no more participants
     * @param Thread $thread
     * @param Member $member
     * @return null Null value to interrupt the execution of the rest of the code after the thread deletion if it contains no more participants
     */
    public function remove(Thread $thread, Member $member) {
		//We get the participants ID
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		$participants = explode(',', $data['idParticipants']);
		
		if(($key = array_search($member->getId(), $participants)) !== false) {
			unset($participants[$key]);
			$participants = array_values($participants);
		}
		
		//We check if there is no more participants
		if(empty($participants)) {
			//We delete the thread
			$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread');
			$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
			$query->execute();

			//We delete all the messages related to the thread
			$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread');
			$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
			$query->execute();

			//We delete the thread from the favorites in case it is in it
            $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'messaging_threadsfavorite WHERE idThread = :idThread');
            $query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
            $query->execute();

            //We delete all the views related to the thread
            $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'messaging_threadsviews WHERE idThread = :idThread');
            $query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
            $query->execute();

            return null; //Interrupts the execution of the rest of the code
		}
		
		//We save the participants ID in a string format to store them back in the DB
		$idParticipants = implode(',', $participants);
		
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'messaging_threads SET idParticipants = :idParticipants WHERE id = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->bindValue(':idParticipants', $idParticipants, PDO::PARAM_STR);
		$query->execute();
	}

    /**
     * Selects and returns a specific thread from the DB
     * @param Thread $thread
     * @return Thread|null Null if no thread found
     */
    public function get(Thread $thread) {
		$threadReturned = new Thread();
		
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		if($query->rowCount() > 0) {
			$data = $query->fetch();
            $threadReturned->hydrate($data);
			
			return $threadReturned;
		} else
			return null;
	}

    /**
     * Checks if member is in the list of participants of a specific thread
     * @param Member $member
     * @param Thread $thread
     * @return bool True if the member is in the list, false otherwise
     */
    public function checkIfParticipant(Member $member, Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread AND (idParticipants LIKE :searchID OR idParticipants LIKE :searchIDFirst OR idParticipants LIKE :searchIDMiddle OR idParticipants LIKE :searchIDLast)');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->bindValue(':searchID', $member->getId(), PDO::PARAM_STR); //If there is only 1 ID/participant
		$query->bindValue(':searchIDFirst', $member->getId() . ",%", PDO::PARAM_STR);
		$query->bindValue(':searchIDMiddle', "%," . $member->getId() . ",%", PDO::PARAM_STR);
		$query->bindValue(':searchIDLast', "%," . $member->getId(), PDO::PARAM_STR);
		$query->execute();
		
		if($query->rowCount() > 0)
			return true;
		else
			return false;
	}

    /**
     * Returns the number of threads of a specific member
     * @param Member $member
     * @return int Number of threads
     */
    public function getNbThreads(Member $member) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE idParticipants LIKE :searchIDFirst OR idParticipants LIKE :searchIDMiddle OR idParticipants LIKE :searchIDLast');
		$query->bindValue(':searchIDFirst', $member->getId() . ",%", PDO::PARAM_STR);
		$query->bindValue(':searchIDMiddle', "%," . $member->getId() . ",%", PDO::PARAM_STR);
		$query->bindValue(':searchIDLast', "%," . $member->getId(), PDO::PARAM_STR);
		$query->execute();
		
		return $query->rowCount();
	}

    /**
     * Returns the number of participants of a specific thread
     * @param Thread $thread
     * @return int
     */
    public function getNbParticipants(Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		$nbParticipants = count(explode(',', $data['idParticipants']));
		
		return $nbParticipants;
	}

    /**
     * Returns the list of participants of a specific thread
     * @param Thread $thread
     * @return array Array of strings containing the username of each participant
     */
    public function getListParticipants(Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threads WHERE id = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		$idParticipants = explode(',', $data['idParticipants']);
		
		$participant = new Member();
		$membersManager = new MembersManager();
		
		$listParticipants = array();
		
		for($i = 0; $i < count($idParticipants); $i++) {
			$participant->setId($idParticipants[$i]);
			$participant = $membersManager->get($participant);
			
			$listParticipants[$i] = $participant->getUsername();
		}
		
		return $listParticipants;
	}

    /**
     * Sets a specific thread in member favorites
     * @param Member $member
     * @param Thread $thread
     */
    public function setAsFavorite(Member $member, Thread $thread) {
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'messaging_threadsfavorite(idMember, idThread) VALUES(:idMember, :idThread)');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->bindValue('idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
	}

    /**
     * Removes a specific thread from member favorites
     * @param Member $member
     * @param Thread $thread
     */
    public function removeFromFavorite(Member $member, Thread $thread) {
		$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'messaging_threadsfavorite WHERE idMember = :idMember AND idThread = :idThread');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->bindValue('idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		print_r($query->errorInfo());
	}

    /**
     * Checks if a thread is in member favorites
     * @param Member $member
     * @param Thread $thread
     * @return bool
     */
    public function checkIfFavorite(Member $member, Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threadsfavorite WHERE idThread = :idThread AND idMember = :idMember');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->bindValue(':idMember', $member->getId(), PDO::PARAM_INT);
		$query->execute();
		
		if($query->rowCount() > 0)
			return true;
		else
			return false;
	}

    /**
     * Returns the last message position of a specific thread
     * @param Thread $thread
     * @return int Integer corresponding to the position of the latest message sent
     */
    public function getThreadLastMsg(Thread $thread) {
		$query = $this->_db->prepare('SELECT MAX(msgInThread) AS lastMsg FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		return $data['lastMsg']; 
	}

    /**
     * Updates the views of a specific thread after the member visits it
     * Used to highlight a thread if a new message has been sent
     * @param Member $member
     * @param Thread $thread
     */
    public function updateThreadView(Member $member, Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threadsviews WHERE idThread = :idThread AND idMember = :idMember');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->bindValue(':idMember', $member->getId(), PDO::PARAM_INT);
		$query->execute();
		
		//We check if it is the first time the user visits the thread
		if($query->rowCount() == 0) {
			$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'messaging_threadsviews(idMember, idThread, lastMsgViewed) VALUES(:idMember, :idThread, :lastMsgViewed)');
			$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
			$query->bindValue('idThread', $thread->getId(), PDO::PARAM_INT);
			$query->bindValue('lastMsgViewed', $this->getThreadLastMsg($thread), PDO::PARAM_INT);
			$query->execute();
		} else {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'messaging_threadsviews SET lastMsgViewed = :lastMsgViewed WHERE idThread = :idThread AND idMember = :idMember');
			$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
			$query->bindValue('idThread', $thread->getId(), PDO::PARAM_INT);
			$query->bindValue('lastMsgViewed', $this->getThreadLastMsg($thread), PDO::PARAM_INT);
			$query->execute();
		}
	}

    /**
     * Checks if thread has new unread message
     * @param Member $member
     * @param Thread $thread
     * @return bool True if the thread contains messages that has not been read by the user, false otherwise
     */
    public function checkThreadView(Member $member, Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_threadsviews WHERE idThread = :idThread AND idMember = :idMember');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->bindValue(':idMember', $member->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		$lastMsgViewed = ($query->rowCount() > 0 ? $data['lastMsgViewed'] : '0');
		
		//If there is a new message that the user didn't read yet --> return true
		if($this->getThreadLastMsg($thread) > $lastMsgViewed)
			return true;
		else
			return false;
	}
}