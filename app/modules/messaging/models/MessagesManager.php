<?php
namespace app\modules\messaging\models;

use config\Config;
use \PDO;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class MessagesManager
 * DB manager for the messaging message entity
 * @package app\modules\messaging\models
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
     * Returns the list of messages of a specific thread
     * @param Thread $thread
     * @param $sqlLimit SQL limit statement for the pagination system
     * @return array Array of threads
     */
    public function get(Thread $thread, $sqlLimit) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread ORDER BY msgInThread ' . $sqlLimit);
		$query->bindValue(':idThread', $thread->getId() . ",%", PDO::PARAM_INT);
		$query->execute();
		
		$message = new Message();
		$messages = array();
		
		$author = new Member();
		$membersManager = new MembersManager();
		
		$i = 0;
		
		while($data = $query->fetch()) {
			$message->hydrate($data);
			
			$messages[$i]['id'] = $message->getId();
			$messages[$i]['idAuthor'] = $message->getIdAuthor();
			$messages[$i]['content'] = $message->getContent();
			$messages[$i]['msgInThread'] = $message->getMsgInThread();
			$messages[$i]['expeditionDate'] = $message->getExpeditionDate();
			
			//We get some infos about the author
			$author->setId($message->getIdAuthor());
			$author = $membersManager->get($author);
			
			$messages[$i]['usernameAuthor'] = $author->getUsername();
			$messages[$i]['signatureAuthor'] = $author->getSignature();
			$messages[$i]['levelAuthor'] = $author->getLevel();
			$messages[$i]['avatarAuthor'] = $author->getAvatar();
			
			$i++;
		}
		
		return $messages;
	}

    /**
     * Stores a message in the DB and returns its ID
     * @param Message $message
     * @return int ID of the message stored
     */
    public function add(Message $message) {
		//We compute the position of the message in the thread
		$query = $this->_db->prepare('SELECT MAX(msgInThread) AS msgInThread FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread');
		$query->bindValue(':idThread', $message->getIdThread(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		$message->setMsgInThread($data['msgInThread'] + 1);
		
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'messaging_messages(idThread, idAuthor, content, msgInThread, expeditionDate) VALUES(:idThread, :idAuthor, :content, :msgInThread, :expeditionDate)');
		$query->bindValue('idThread', $message->getIdThread(), PDO::PARAM_INT);
		$query->bindValue('idAuthor', $message->getIdAuthor(), PDO::PARAM_INT);
		$query->bindValue('content', $message->getContent(), PDO::PARAM_STR);
		$query->bindValue('msgInThread', $message->getMsgInThread(), PDO::PARAM_INT);
		$query->bindValue('expeditionDate', $message->getExpeditionDate(), PDO::PARAM_STR);
		$query->execute();
		
		return $message->getMsgInThread();
	}

    /**
     * Returns the number of messages of a specific thread
     * @param Thread $thread
     * @return int Number of messages
     */
    public function getNbMessages(Thread $thread) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		return $query->rowCount();
	}

    /**
     * Returns the creation date of a specific thread
     * @param Thread $thread
     * @return string Creation date of the thread
     */
    public function getThreadCreationDate(Thread $thread) {
		$query = $this->_db->prepare('SELECT MIN(expeditionDate) AS threadCreationDate FROM ' . $this->_tablePrefix . 'messaging_messages WHERE idThread = :idThread');
		$query->bindValue(':idThread', $thread->getId(), PDO::PARAM_INT);
		$query->execute();
		
		$data = $query->fetch();
		
		return (Config::dateFormat($data['threadCreationDate']));
	}
}