<?php
namespace app\modules\forum\models;

use config\Exception;

/**
 * Class Message
 * Forum message entity as it is saved in the DB
 * @package app\modules\forum\models
 */
class Message {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idTopic;

    /**
     * @var int
     * ID of the member who wrote the message
     */
    private $_idAuthor;

    /**
     * @var string
     */
    private $_message;

    /**
     * @var int
     * Specifies if the message helped the author
     */
    private $_helpedAuthor;

    /**
     * @var string
     */
    private $_publicationDate;

    /**
     * @var string
     * Last message edition date
     */
    private $_lastEditionDate;

    /**
     * @var int
     * ID of the user who edited the message lastly
     */
    private $_idEditor;

    /**
     * @var int
     * Position of the message in the topic
     */
    private $_msgInTopic;

    /**
     * Assigns values to entity attributes
     * @param array $data Holds the values of the entity attributes which are taken from the DB
     */
    public function hydrate($data) {
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);

			if (method_exists($this, $method))
				$this->$method($value);
		}
	}
	
	//GETTERS AND SETTERS

    /**
     * @return int
     */
    public function getId() {
		return $this->_id;
	}

    /**
     * @param int $id
     * @throws Exception
     */
    public function setId($id) {
		if(!empty($id)) {
			if(is_numeric($id))
				$this->_id = $id;
			else
				throw new Exception("message_idShouldBeNumeric");
		} else
			throw new Exception("message_idCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdAuthor() {
		return $this->_idAuthor;
	}

    /**
     * @param int $idAuthor
     * @throws Exception
     */
    public function setIdAuthor($idAuthor) {
		if(!empty($idAuthor)) {
			if(is_numeric($idAuthor))
				$this->_idAuthor = $idAuthor;
			else
				throw new Exception("message_idAuthorShouldBeNumeric");
		} else
			throw new Exception("message_idAuthorCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdTopic() {
		return $this->_idTopic;
	}

    /**
     * @param int $idTopic
     * @throws Exception
     */
    public function setIdTopic($idTopic) {
		if(!empty($idTopic)) {
			if(is_numeric($idTopic))
				$this->_idTopic = $idTopic;
			else
				throw new Exception("message_idTopicShouldBeNumeric");
		} else
			throw new Exception("message_idTopicCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getMessage() {
		return $this->_message;
	}

    /**
     * @param string $message
     * @throws Exception
     */
    public function setMessage($message) {
		if(!empty($message)) {
			if(is_string($message))
				$this->_message = $message; //XSS security is handled by BBCode editor
			else
				throw new Exception("message_messageShouldBeString");
		} else
			throw new Exception("message_messageCannotBeEmpty");
	}

    /**
     * @return bool
     */
    public function getHelpedAuthor() {
		if($this->_helpedAuthor == 1)
			return true;
		else
			return false;
	}

    /**
     * @param int $helpedAuthor
     * @throws Exception
     */
    public function setHelpedAuthor($helpedAuthor) {
		if(is_numeric($helpedAuthor)) 
			$this->_helpedAuthor = $helpedAuthor;
		else
			throw new Exception("message_helpedAuthorShouldBeNumeric");
	}

    /**
     * @return string
     */
    public function getPublicationDate() {
		return $this->_publicationDate;
	}

    /**
     * @param string $publicationDate
     * @throws Exception
     */
    public function setPublicationDate($publicationDate) {
		if(!empty($publicationDate)) 
			$this->_publicationDate = htmlspecialchars($publicationDate);
		else
			throw new Exception("message_publicationDateCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getLastEditionDate() {
		return $this->_lastEditionDate;
	}

    /**
     * @param string $lastEditionDate
     */
    public function setLastEditionDate($lastEditionDate) {
		$this->_lastEditionDate = htmlspecialchars($lastEditionDate);
	}

    /**
     * @return int
     */
    public function getIdEditor() {
		return $this->_idEditor;
	}

    /**
     * @param int $idEditor
     * @throws Exception
     */
    public function setIdEditor($idEditor) {
		if(is_numeric($idEditor))
			$this->_idEditor = $idEditor;
		else
			throw new Exception("idEditorShouldBeNumeric");
	}

    /**
     * @return int
     */
    public function getMsgInTopic() {
		return $this->_msgInTopic;
	}

    /**
     * @param int $msgInTopic
     * @throws Exception
     */
    public function setMsgInTopic($msgInTopic) {
		if(!empty($msgInTopic)) {
			if(is_numeric($msgInTopic)) 
				$this->_msgInTopic = $msgInTopic;
			else
				throw new Exception("message_msgInTopicShouldBeNumeric");
		} else
			throw new Exception("message_msgInTopicCannotBeEmpty");
	}
}