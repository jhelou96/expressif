<?php
namespace app\modules\forum\models;

use config\Exception;

/**
 * Class Category
 * Forum category entity as it saved in the DB
 * @package app\modules\forum\models
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
			throw new Exception("category_dCannotBeEmpty");
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
			if(is_string($name))
				$this->_name = htmlspecialchars($name);
			else
				throw new Exception("category_nameShouldBeString");
		} else
			throw new Exception("category_nameCannotBeEmpty");
	}
}