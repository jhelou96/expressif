<?php
namespace app\modules\forum\models;

use config\Exception;

/**
 * Class Topic
 * Forum topic entity as it is saved in the DB
 * @package app\modules\forum\models
 */
class Topic {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idSubCategory;

    /**
     * @var int
     * ID of the member who posted the topic
     */
    private $_idAuthor;

    /**
     * @var string
     */
    private $_title;

    /**
     * @var int
     * Specifies if the topic is highlighted
     */
    private $_postit;

    /**
     * @var int
     * Specifies if the topic is solved
     */
    private $_solved;

    /**
     * @var int
     * Specifies if the topic is locked
     */
    private $_locked;

    /**
     * @var string
     */
    private $_creationDate;

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
				throw new Exception("topic_idShouldBeNumeric");
		} else
			throw new Exception("topic_idCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdSubCategory() {
		return $this->_idSubCategory;
	}

    /**
     * @param int $idSubCategory
     * @throws Exception
     */
    public function setIdSubCategory($idSubCategory) {
		if(!empty($idSubCategory)) {
			if(is_numeric($idSubCategory))
				$this->_idSubCategory = $idSubCategory;
			else
				throw new Exception("topic_idSubCategoryShouldBeNumeric");
		} else
			throw new Exception("topic_idSubCategoryCannotBeEmpty");
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
				throw new Exception("topic_idAuthorShouldBeNumeric");
		} else
			throw new Exception("topic_idAuthorCannotBeEmpty");
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
			if(is_string($title)) {
			    if(preg_match("#[^a-zA-Z0-9ÇçèéêëÈÉÊËàáâãäå@ÀÁÂÃÄÅìíîïÌÍÎÏðòóôõöÒÓÔÕÖùúûüÙÚÛÜýÿÝ -]#", $title) != 1)
                     $this->_title = htmlspecialchars($title);
			    else
			        throw new Exception("topic_titleContainsUnauthorizedChars");
            } else
				throw new Exception("topic_titleShouldBeString");
		} else
			throw new Exception("topic_titleCannotBeEmpty");
	}

    /**
     * @return bool
     */
    public function getPostit() {
		if($this->_postit == 1)
			return true;
		else
			return false;
	}

    /**
     * @param int $postit
     * @throws Exception
     */
    public function setPostit($postit) {
		if(is_numeric($postit))
			$this->_postit = $postit;
		else
			throw new Exception("topic_postitShouldBeNumeric");
	}

    /**
     * @return bool
     */
    public function getSolved() {
		if($this->_solved == 1)
			return true;
		else
			return false;
	}

    /**
     * @param int $solved
     * @throws Exception
     */
    public function setSolved($solved) {
		if(is_numeric($solved))
			$this->_solved = $solved;
		else
			throw new Exception("topic_solvedShouldBeNumeric");
	}

    /**
     * @return bool
     */
    public function getLocked() {
		if($this->_locked == 1)
			return true;
		else
			return false;
	}

    /**
     * @param int $locked
     * @throws Exception
     */
    public function setLocked($locked) {
		if(is_numeric($locked))
			$this->_locked = $locked;
		else
			throw new Exception("topic_lockedShouldBeNumeric");
	}

    /**
     * @return string
     */
    public function getCreationDate() {
		return $this->_creationDate;
	}

    /**
     * @param string $creationDate
     * @throws Exception
     */
    public function setCreationDate($creationDate) {
		if(!empty($creationDate)) 
			$this->_creationDate = htmlspecialchars($creationDate);
		else
			throw new Exception("topic_creationDateCannotBeEmpty");
	}
}