<?php
namespace app\modules\messaging\models;

use config\Exception;

/**
 * Class Thread
 * Messaging thread entity as it is saved in the DB
 * @package app\modules\messaging\models
 */
class Thread {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idAuthor;
    /**
     * @var array
     * List of thread participants ID
     */
    private $_idParticipants;

    /**
     * @var string
     */
    private $_title;

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
				throw new Exception("thread_idShouldBeNumeric");
		} else
			throw new Exception("thread_idCannotBeEmpty");
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
				throw new Exception("thread_idAuthorShouldBeNumeric");
		} else
			throw new Exception("thread_idAuthorCannotBeEmpty");
	}

    /**
     * @return array
     */
    public function getIdParticipants() {
		return $this->_idParticipants;
	}

    /**
     * @param array $idParticipants
     * @throws Exception
     */
    public function setIdParticipants($idParticipants) {
		if(!empty($idParticipants)) {
			if(is_string($idParticipants)) 
				$this->_idParticipants = $idParticipants;
			else
				throw new Exception("thread_idParticipantsShouldBeString");
		} else
			throw new Exception("thread_idParticipantsCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getTitle() {
		return $this->_title;
	}

    /**
     * @param string $title
     * @throws Exception
     */
    public function setTitle($title) {
		if(!empty($title)) {
			if(is_string($title))
				$this->_title = htmlspecialchars($title);
			else
				throw new Exception("thread_titleShouldBeString");
		} else
			throw new Exception("thread_titleCannotBeEmpty");
	}
}