<?php
namespace app\modules\forum\models;

use config\Config;
use \PDO;


use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class MessagesManager
 * DB manager for the forum message entity
 * @package app\modules\forum\models
 */
class MessagesManager {
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
     * MessagesManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores a message in the DB
     * @param Message $message
     */
    public function add(Message $message) {
		date_default_timezone_set('Europe/Paris');
		setlocale(LC_TIME, "fr_FR");
		$date = strftime(date('Y-m-d H:i:s', time()));
		
		$message->setPublicationDate($date);
		
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_messages(idTopic, idAuthor, message, publicationDate, msgInTopic) VALUES(:idTopic, :idAuthor, :message, :publicationDate, :msgInTopic)');
		$query->bindValue(':idTopic', $message->getIdTopic(), PDO::PARAM_INT);
		$query->bindValue(':idAuthor', $message->getIdAuthor(), PDO::PARAM_INT);
		$query->bindValue(':message', $message->getMessage(), PDO::PARAM_STR);
		$query->bindValue(':publicationDate', $message->getPublicationDate(), PDO::PARAM_STR);
		$query->bindValue(':msgInTopic', $message->getMsgInTopic(), PDO::PARAM_INT);
		
		$query->execute();
	}

    /**
     * Updates the attributes of a message and saves them in the DB
     * @param Message $message
     */
    public function update(Message $message) {
		date_default_timezone_set('Europe/Paris');
		setlocale(LC_TIME, "fr_FR");
		$date = strftime(date('Y-m-d H:i:s', time()));
		
		$message->setLastEditionDate($date);
		
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_messages SET message = :message, lastEditionDate = :lastEditionDate, idEditor = :idEditor WHERE id = :idMessage');
		$query->bindValue(':idMessage', $message->getId(), PDO::PARAM_INT);
		$query->bindValue(':message', $message->getMessage(), PDO::PARAM_STR);
		$query->bindValue(':lastEditionDate', $message->getLastEditionDate(), PDO::PARAM_STR);
		$query->bindValue(':idEditor', $message->getIdEditor(), PDO::PARAM_INT);
		
		$query->execute();
	}

    /**
     * Removes a message from the DB
     * @param Message $message
     */
    public function remove(Message $message) {
		$topic = new Topic();
		$topic->setId($message->getIdTopic());
		
		//We decrement the msgInTopic variable of each message where it is greater than the msgInTopic variable of the message we're removing
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_messages SET msgInTopic = msgInTopic-1 WHERE idTopic = :idTopic AND msgInTopic > :msgInTopic');
		$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->bindValue(':msgInTopic', $message->getMsgInTopic(), PDO::PARAM_INT);
		$query->execute();
		
		$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_messages WHERE id = :idMessage');
		$query->bindValue(':idMessage', $message->getId(), PDO::PARAM_INT);
		$query->execute();
	}

    /**
     * Removes all the messages of a topic from the DB
     * @param Topic $topic
     */
    public function removeMessagesRelatedToTopic(Topic $topic) {
	    //STEP 1 - WE REMOVE THE LIST OF LIKES/DISLIKES OF EACH MESSAGE THAT IS TO BE REMOVED
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic');
        $query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
        $query->execute();
        while($data = $query->fetch()) {
            $message = new Message();
            $message->hydrate($data);

            $likesManager = new LikesManager();
            $likesManager->remove($message);
        }

        //STEP 2 - WE REMOVE THE MESSAGES
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic');
        $query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Returns the list of messages of a topic
     * @param Topic $topic
     * @param $sqlLimit SQL Limit statement for the pagination system
     * @return array Array of messages
     */
    public function getMessagesList(Topic $topic, $sqlLimit) {
		$message = new Message();
		$membersManager = new MembersManager();
	
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic ORDER BY publicationDate ASC ' . $sqlLimit);
		$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();
		
		//We save the messages in an array
		$messages = array();
		$i = 0;
		
		$likesManager = new LikesManager();
		
		while($data = $query->fetch()) {
			$message->hydrate($data);
			
			//We get some infos about the author
			$author = new Member();
			$author->setId($message->getIdAuthor());
			$author = $membersManager->get($author);
			
			//We check if the message has been written by the author
			if($topic->getIdAuthor() == $message->getIdAuthor())
				$messages[$i]['msgWrittenByAuthor'] = true;
			else
				$messages[$i]['msgWrittenByAuthor'] = false;
			
			$messages[$i]['authorUsername'] = $author->getUsername();
			$messages[$i]['authorAvatar'] = $author->getAvatar();
			$messages[$i]['authorLevel'] = $author->getLevel();
			//If the last query of the user has been performed in the last 5 min then the user is connected
			$messages[$i]['authorIsConnected'] = ((isset($_SESSION['idUser']) AND $author->getLastQuery() + 5*60 >  time()) ? true : false); 
			
			if(!empty($author->getSignature()))
				$messages[$i]['authorSignature'] = $author->getSignature();
				
				
			//We check if the message has been edited and by who 
			if(!empty($message->getLastEditionDate()) AND !empty($message->getIdEditor())) {
				$editor = new Member();
				$editor->setId($message->getIdEditor());
				$editor = $membersManager->get($editor);
				
				$messages[$i]['editorUsername'] = $editor->getUsername();
				$messages[$i]['lastEditionDate'] = Config::dateTimeFormat($message->getLastEditionDate());
			}
			
			$messages[$i]['id'] = $message->getId();
			$messages[$i]['message'] = $message->getMessage();
			$messages[$i]['helpedAuthor'] = $message->getHelpedAuthor();
			$messages[$i]['publicationDate'] = Config::dateTimeFormat($message->getPublicationDate());
			$messages[$i]['msgInTopic'] = $message->getMsgInTopic();
			
			//We check if the user connected is the author of the message
			$messages[$i]['isAuthor'] = (isset($_SESSION['idUser']) AND $_SESSION['idUser'] == $message->getIdAuthor() ? true : false);
	
			//We get the likes and dislikes of the message
			$messages[$i]['likes'] = $likesManager->getLikes($message);
			$messages[$i]['dislikes'] = $likesManager->getDislikes($message);
			
			//We check if the user has voted
			if(isset($_SESSION['idUser'])) {
				$member = new Member();
				$membersManager = new MembersManager();
				$member->setId($_SESSION['idUser']);
				$member = $membersManager->get($member);
				
				$messages[$i]['userAlreadyVoted'] = $likesManager->checkIfAlreadyVoted($member, $message);
			}
			
			$i++;
		}
		
		return $messages;
	}

    /**
     * Selects and returns a specific message from the DB
     * @param Message $message
     * @return Message|null Null if no message found
     */
    public function get(Message $message) {
		$messageInfo = new Message();
		
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE id = :idMessage');
		$query->bindValue('idMessage', $message->getId(), PDO::PARAM_INT);
		$query->execute();
		
		if($query->rowCount() > 0) {
			$data = $query->fetch();
			$messageInfo->hydrate($data);
		
			return $messageInfo;
		} else
			return null;
	}

    /**
     * Returns the number of messages related to a specific topic
     * @param Topic $topic
     * @return int
     */
    public function getNbMessages(Topic $topic) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic');
		$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();

		return $query->rowCount();
	}

    /**
     * Returns the position of the last message in the topic
     * @param Topic $topic
     * @return int Integer representing the position of the latest message sent in a specific topic
     */
    public function getMaxMsgInTopic(Topic $topic) {
		$query = $this->_db->prepare('SELECT max(msgInTopic) AS maxMsgInTopic FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic');
		$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		return $data['maxMsgInTopic'];
	}

    /**
     * Sets a message as helpful
     * @param Message $message
     */
    public function setMessageHelpful(Message $message) {
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_messages SET helpedAuthor = 1 WHERE id = :idMsg');
		$query->bindValue(':idMsg', $message->getId(), PDO::PARAM_INT);
		$query->execute();
	}

    /**
     * Returns the last message posted in a specific topic
     * @param Topic $topic
     * @return Message Last message posted
     */
    public function getLastMsgPosted(Topic $topic) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic ORDER BY publicationDate DESC');
        $query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
        $query->execute();

        $data = $query->fetch();

        $message = new Message();
        $message->hydrate($data);

        return $message;
    }

    /**
     * Returns the number of messages posted by a member
     * @param Member $member
     * @return int
     */
    public function getNbMessagesPosted(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idAuthor = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount();
    }

    /**
     * Checks if the user posted any message in the topic
     * @param Member $member
     * @param Topic $topic
     * @return int 1 if the user has participated to the discussion, 0 otherwise
     */
    public function checkUserParticipation(Member $member, Topic $topic) {
        //We check if the user posted any message in the topic
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idTopic = :idTopic AND idAuthor = :idAuthor');
        $query->bindValue('idAuthor', $member->getId(), PDO::PARAM_INT);
        $query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
        $query->execute();

        if($query->rowCount() > 0)
            $hasParticipated = 1;
        else
            $hasParticipated = 0;

        return $hasParticipated;
    }

    /**
     * Achievement system -  Checks if the user has any message set as helpful
     * @param Member $member
     * @return bool True if the user has any message set as helpful, false otherwise
     */
    public function achievements_userHasHelpedOthers(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idAuthor = :idMember AND helpedAuthor = 1');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        if($query->rowCount() > 0)
            return true;
        else
            return false;
    }

    /**
     * Achievement system - Checks if the user has posted any message
     * @param Member $member
     * @return bool True if the user has posted a message, false otherwise
     */
    public function achievements_userHasPostedAMessage(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_messages WHERE idAuthor = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        if($query->rowCount() > 0)
            return true;
        else
            return false;
    }
}