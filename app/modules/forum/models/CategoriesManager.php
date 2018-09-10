<?php
namespace app\modules\forum\models;

use \PDO;
use config\Config;

/**
 * Class CategoriesManager
 * DB manager for the forum category entity
 * @package app\modules\forum\models
 */
class CategoriesManager {
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
     * CategoriesManager constructor.
     */
    public function __construct() {
		$this->_db = Config::getDBInfos();
		$this->_tablePrefix = Config::getDBTablesPrefix();
	}

    /**
     * Stores a category in the DB
     * @param Category $category
     */
    public function add(Category $category) {
        $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_categories(name) VALUES(:name)');
        $query->bindValue('name', $category->getName(), PDO::PARAM_STR);

        $query->execute();
    }

    /**
     * Updates the attributes of a category and saves them in the DB
     * @param Category $category
     */
    public function update(Category $category) {
        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_categories SET name = :name WHERE id = :id');
        $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
        $query->bindValue('name', $category->getName(), PDO::PARAM_STR);

        $query->execute();
    }

    /**
     * Removes a category from the DB
     * @param Category $category
     */
    public function remove(Category $category) {
	    //We have to remove all the subcategories, topics and messages related to the category that is to be removed
        $subCategoriesManager = new SubCategoriesManager();
        $subCategoriesManager->removeSubcategoriesRelatedToCategory($category);

        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_categories WHERE id = :id');
        $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Returns the list of categories
     * @return array Array of categories
     */
    public function get() {
		$category = new Category();

		//We save the categories in an array
		$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'forum_categories');
			
		$categories = array();
		$i = 0;
			
		while($data = $query->fetch()) {
			$category->hydrate($data);
				
			$categories[$i]['id'] = $category->getId();
			$categories[$i]['name'] = $category->getName();
			$i++;
		}
		
		return $categories;
	}

    /**
     * @param Category $category
     * @return bool
     */
    public function checkIfCategoryExists(Category $category) {
        //Search by ID --> ID priority
        if(!empty($category->getId())) {
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_categories WHERE id = :id');
            $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
            $query->execute();

            if ($query->rowCount() > 0)
                return true;
            else
                return false;
        } elseif(!empty($category->getName())) { //Search by name
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_categories WHERE name = :name');
            $query->bindValue('name', $category->getName(), PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0)
                return true;
            else
                return false;
        }
    }

    /**
     * @return int
     */
    public function getNbCategories() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'forum_categories');

        return $query->rowCount();
    }
}