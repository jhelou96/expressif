<?php
namespace app\modules\forum\models;

use config\Exception;

/**
 * Class SubCategory
 * Subcategory entity as it is saved in the DB
 * @package app\modules\forum\models
 */
class SubCategory {

    /**
     * @var int
     */
    private $_id;

    /**
     * @var int
     */
    private $_idCategory;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_description;

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
				throw new Exception("subCategory_idShouldBeNumeric");
		} else
			throw new Exception("subCategory_idCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getIdCategory() {
		return $this->_idCategory;
	}

    /**
     * @param int $idCategory
     * @throws Exception
     */
    public function setIdCategory($idCategory) {
		if(!empty($idCategory)) {
			if(is_numeric($idCategory))
				$this->_idCategory = $idCategory;
			else
				throw new Exception("subCategory_idCategoryShouldBeNumeric");
		} else
			throw new Exception("subCategory_idCategoryCannotBeEmpty");
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
                    throw new Exception("subCategory_nameContainsUnauthorizedChars");
            } else
				throw new Exception("subCategory_nameShouldBeString");
		} else
			throw new Exception("subCategory_nameCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getDescription() {
		return $this->_description;
	}

    /**
     * @param string $description
     * @throws Exception
     */
    public function setDescription($description) {
		if(!empty($description)) {
			if(is_string($description))
				$this->_description = htmlspecialchars($description);
			else
				throw new Exception("subCategory_descriptionShouldBeString");
		} else
			throw new Exception("subCategory_descriptionCannotBeEmpty");
	}
}