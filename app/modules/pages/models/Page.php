<?php
namespace app\modules\pages\models;

use config\Exception;

/**
 * Class Page
 * Page entity as it is saved in the DB
 * @package app\modules\pages\models
 */
class Page
{
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
    private $_content;

    /**
     * @var int
     * Specifies if page is homepage or not
     */
    private $_isHomepage;

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
                throw new Exception("page_idShouldBeNumeric");
        } else
            throw new Exception("page_idCannotBeEmpty");
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
                    throw new Exception("page_nameContainsUnauthorizedChars");
            } else
                throw new Exception("page_nameShouldBeString");
        } else
            throw new Exception("page_nameCannotBeEmpty");
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
                $this->_content = $content; //XSS security is handled by BBCode editor
            else
                throw new Exception("page_contentShouldBeString");
        } else
            throw new Exception("page_contentCannotBeEmpty");
    }

    /**
     * @return bool
     */
    public function getIsHomepage() {
        if($this->_isHomepage == 1)
            return true;
        else
            return false;
    }

    /**
     * @param int $isHomepage
     * @throws Exception
     */
    public function setIsHomepage($isHomepage) {
        if(is_numeric($isHomepage))
            $this->_isHomepage = $isHomepage;
        else
            throw new Exception("page_isHomepageShouldBeNumeric");
    }
}