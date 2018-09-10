<?php
namespace app\modules\articles\models;

use config\Config;
use \PDO;

/**
 * Class CategoriesManager
 * DB manager for the articles category entity
 * @package app\modules\articles\models
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
        //We check if a thumbnail has been uploaded
        if(!empty($category->getThumbnail())) {
            $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'articles_categories(name, thumbnail) VALUES(:name, :thumbnail)');
            $query->bindValue('name', $category->getName(), PDO::PARAM_STR);
            $query->bindValue('thumbnail', $category->getThumbnail(), PDO::PARAM_STR);

            $query->execute();
        } else {
            $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'articles_categories(name) VALUES(:name)');
            $query->bindValue('name', $category->getName(), PDO::PARAM_STR);

            $query->execute();
        }
    }

    /**
     * Updates the attributes of a category and saves them in the DB
     * @param Category $category
     */
    public function update(Category $category) {
        //We check if a thumbnail has been uploaded
        if(!empty($category->getThumbnail())) {
            $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'articles_categories SET name = :name, thumbnail = :thumbnail WHERE id = :id');
            $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
            $query->bindValue('name', $category->getName(), PDO::PARAM_STR);
            $query->bindValue('thumbnail', $category->getThumbnail(), PDO::PARAM_STR);

            $query->execute();
        } else {
            $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'articles_categories SET name = :name WHERE id = :id');
            $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
            $query->bindValue('name', $category->getName(), PDO::PARAM_STR);

            $query->execute();
        }
    }

    /**
     * Removes a category from the DB
     * @param Category $category
     */
    public function remove(Category $category) {
        //We remove the articles related to that category
        $articlesManager = new ArticlesManager();
        $articlesManager->removeArticlesRelatedToCategory($category);

        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'articles_categories WHERE id = :id');
        $query->bindValue('id', $category->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Returns the list of categories
     * @return array Array of articles
     */
    public function getListCategories() {
		$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_categories');
	
		$categories = array();
		$i = 0;
		
		$category = new Category();
		
		while($data = $query->fetch()) {
			$category->hydrate($data);
			
			$categories[$i]['id'] = $category->getId();
			$categories[$i]['name'] = $category->getName();
			$categories[$i]['thumbnail'] = $category->getThumbnail();
			$categories[$i]['categoryURL'] = Config::getPath() . '/articles/' . Config::urlFormat($category->getName());
			$i++;
		}
		
		return $categories;
	}

    /**
     * Selects and returns a category from the DB based on its name or ID
     * @param Category $category
     * @return category|null Null if no category found
     */
    public function get(Category $category) {
		if(!empty($category->getId())) { //Search category by ID --> ID priority
			$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_categories WHERE id = :idCategory');
			$query->bindValue('idCategory', $category->getId(), PDO::PARAM_INT);
			$query->execute();
			
			if($query->rowCount() > 0) {
				$data = $query->fetch();
				$categoryReturned = new Category();
                $categoryReturned->hydrate($data);
				
				return $categoryReturned;
			} else
				return null;
		} elseif(!empty($category->getName())) { //Search category by name
             $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_categories');

             //We convert each category name to the URL Format and compare it to the parameter
             while($data = $query->fetch()) {
                 $categoryReturned = new Category();
                 $categoryReturned->hydrate($data);

                 if(Config::urlFormat($categoryReturned->getName()) == Config::urlFormat($category->getName()))
                     return $categoryReturned;
             }

             return null;
         }
	}
}