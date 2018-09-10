<?php
namespace app\modules\messaging\models;

use config\Exception;

/**
 * Class Message
 * Messaging message entity as it is saved in the DB
 * @package app\modules\messaging\models
 */
class Message {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idThread;

    /**
     * @var int
     * ID of the member who wrote the message
     */
    private $_idAuthor;

    /**
     * @var string
     */
    private $_content;

    /**
     * @var int
     * Position of the message in the thread
     */
    private $_msgInThread;

    /**
     * @var string
     * Date when the message was sent
     */
    private $_expeditionDate;

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
    public function getIdThread() {
		return $this->_idThread;
	}

    /**
     * @param int $idThread
     * @throws Exception
     */
    public function setIdThread($idThread) {
		if(!empty($idThread)) {
			if(is_numeric($idThread)) 
				$this->_idThread = $idThread;
			else
				throw new Exception("message_idThreadShouldBeNumeric");
		} else
			throw new Exception("message_idThreadCannotBeEmpty");
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
     * @return string
     */
    public function getContent() {
		return $this->_content;
	}

    /**
     * @param string $content
     * @throws Exception
     */
    public function setContent($content) {
		if(!empty($content)) {
			if(is_string($content))
				$this->_content = $content;  //XSS security is handled by BBCode editor
			else
				throw new Exception("message_contentShouldBeString");
		} else
			throw new Exception("message_contentCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getMsgInThread() {
		return $this->_msgInThread;
	}

    /**
     * @param int $msgInThread
     * @throws Exception
     */
    public function setMsgInThread($msgInThread) {
		if(!empty($msgInThread)) {
			if(is_numeric($msgInThread)) 
				$this->_msgInThread = $msgInThread;
			else
				throw new Exception("message_msgInThreadShouldBeNumeric");
		} else
			throw new Exception("message_msgInThreadCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getExpeditionDate() {
		return $this->_expeditionDate;
	}

    /**
     * @param string $expeditionDate
     * @throws Exception
     */
    public function setExpeditionDate($expeditionDate) {
		if(!empty($expeditionDate)) {
			if(is_string($expeditionDate))
				$this->_expeditionDate = htmlspecialchars($expeditionDate);
			else
				throw new Exception("message_expeditionDateShouldBeString");
		} else
			throw new Exception("message_expeditionDateCannotBeEmpty");
	}
}