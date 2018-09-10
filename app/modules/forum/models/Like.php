<?php
namespace app\modules\forum\models;

use config\Exception;

/**
 * Class Like
 * Forum like entity as it is saved in the DB
 * @package app\modules\forum\models
 */
class Like {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idUser;

    /**
     * @var int
     */
    private $_idMessage;

    /**
     * @var int
     * Specifies if the object represents a like
     */
    private $_liked;

    /**
     * @var int
     * Specifies if the object represents a dislike
     */
    private $_disliked;

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
     * @param int
     * $id
     * @throws Exception
     */
    public function setId($id) {
		if(!empty($id)) {
			if(is_numeric($id))
				$this->_id = $id;
			else
				throw new Exception("like_idShouldBeNumeric");
		} else
			throw new Exception("like_idCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdUser() {
		return $this->_idUser;
	}

    /**
     * @param int $idUser
     * @throws Exception
     */
    public function setIdUser($idUser) {
		if(!empty($idUser)) {
			if(is_numeric($idUser))
				$this->_idUser = $idUser;
			else
				throw new Exception("like_idUserShouldBeNumeric");
		} else
			throw new Exception("like_idUserCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdMessage() {
		return $this->_idMessage;
	}

    /**
     * @param int $idMessage
     * @throws Exception
     */
    public function setIdMessage($idMessage) {
		if(!empty($idMessage)) {
			if(is_numeric($idMessage))
				$this->_idMessage = $idMessage;
			else
				throw new Exception("like_idMessageShouldBeNumeric");
		} else
			throw new Exception("like_idMessageCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getLiked() {
		return $this->_liked;
	}

    /**
     * @param int $like
     * @throws Exception
     */
    public function setLiked($like) {
		if(is_numeric($like)) 
			$this->_liked = $like;
		else
			throw new Exception("like_likedShouldBeNumeric");
	}

    /**
     * @return int
     */
    public function getDisliked() {
		return $this->_disliked;
	}

    /**
     * @param int $dislike
     * @throws Exception
     */
    public function setDisliked($dislike) {
		if(is_numeric($dislike)) 
			$this->_disliked = $dislike;
		else
			throw new Exception("like_dislikedShouldBeNumeric");
	}
}