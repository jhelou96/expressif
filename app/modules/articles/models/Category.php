<?php
namespace app\modules\articles\models;

use config\Exception;

/**
 * Class Category
 * Category entity as it is saved in the DB
 * @package app\modules\articles\models
 */
class Category {
    /**
     * @var int
     */
    private $_id;
    /**
     * @var string
     */
    private $_name;
    /**
     * @var string
     */
    private $_thumbnail;

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
				throw new Exception("category_idShouldBeNumeric");
		} else
			throw new Exception("category_idCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getName() {
		return $this->_name;
	}

    /**
     * @param string $name
     * @throws Exception
     */
    public function setName($name) {
		if(!empty($name)) {
			if(is_string($name)) {
                if(preg_match("#[^a-zA-Z0-9ÇçèéêëÈÉÊËàáâãäå@ÀÁÂÃÄÅìíîïÌÍÎÏðòóôõöÒÓÔÕÖùúûüÙÚÛÜýÿÝ -]#", $name) != 1)
                    $this->_name = htmlspecialchars($name);
			    else
			        throw new Exception("category_nameSContainsUnauthorizedChars");
            } else
				throw new Exception("category_nameShouldBeString");
		} else
			throw new Exception("category_nameCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getThumbnail() {
		return $this->_thumbnail;
	}

    /**
     * @param string $thumbnail
     * @throws Exception
     */
    public function setThumbnail($thumbnail) {
		if(!empty($thumbnail)) {
			if(is_string($thumbnail)) 
				$this->_thumbnail = htmlspecialchars($thumbnail);
			else
				throw new Exception("category_thumbnailShouldBeString");
		} else
			throw new Exception("category_thumbnailCannotBeEmpty");
	}
}