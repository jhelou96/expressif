<?php
namespace app\modules\forum\models;

use config\Config;
use \PDO;

/**
 * Class SubCategoriesManager
 * DB manager for the forum subcategory entity
 * @package app\modules\forum\models
 */
class SubCategoriesManager {
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
     * SubCategoriesManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores a subcategory in the DB
     * @param Subcategory $subcategory
     */
    public function add(SubCategory $subcategory) {
        $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_subcategories(idCategory, name, description) VALUES(:idCategory, :name, :description)');
        $query->bindValue('idCategory', $subcategory->getIdCategory(), PDO::PARAM_INT);
        $query->bindValue('name', $subcategory->getName(), PDO::PARAM_STR);
        $query->bindValue('description', $subcategory->getDescription(), PDO::PARAM_STR);

        $query->execute();
    }

    /**
     * Updates the subcategory attributes and saves them in the DB
     * @param SubCategory $subcategory
     */
    public function update(SubCategory $subcategory) {
	    $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_subcategories SET idCategory = :idCategory, name = :name, description = :description WHERE id = :idSubCategory');
        $query->bindValue('idSubCategory', $subcategory->getId(), PDO::PARAM_INT);
        $query->bindValue('idCategory', $subcategory->getIdCategory(), PDO::PARAM_INT);
        $query->bindValue('name', $subcategory->getName(), PDO::PARAM_STR);
        $query->bindValue('description', $subcategory->getDescription(), PDO::PARAM_STR);

        $query->execute();
    }

    /**
     * Returns the list of subcategories
     * @return array Array of subcategories
     */
    public function getListSubcategories() {
		$subCategory = new SubCategory();
			
		//We save the subcategories in an array
		$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'forum_subcategories');
		
		$subCategories = array();
		$i = 0;

		$topicsManager = new TopicsManager();

		while($data = $query->fetch()) {
			$subCategory->hydrate($data);
			
			//We get the number of messages for each subcategory
			$query2 = $this->_db->prepare('SELECT COUNT(M.id) AS nbMessages FROM ' . $this->_tablePrefix . 'forum_messages M JOIN ' . $this->_tablePrefix . 'forum_topics T ON T.id = M.idTopic WHERE idSubCategory = :idSubCategory');
			$query2->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
			$query2->execute();
			
			$data2 = $query2->fetch();
			
			//We get the last message posted in each subCategory
			$query3 = $this->_db->prepare('SELECT title, publicationDate FROM ' . $this->_tablePrefix . 'forum_messages M JOIN ' . $this->_tablePrefix . 'forum_topics T ON T.id = M.idTopic WHERE idSubCategory = :idSubCategory ORDER BY publicationDate DESC');
			$query3->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
			$query3->execute();
			
			$data3 = $query3->fetch();
			
			$subCategories[$i]['id'] = $subCategory->getId();
			$subCategories[$i]['idCategory'] = $subCategory->getIdCategory();
			$subCategories[$i]['name'] = $subCategory->getName();
			$subCategories[$i]['description'] = $subCategory->getDescription();
			$subCategories[$i]['url'] = Config::urlFormat($subCategory->getName());
			$subCategories[$i]['nbTopics'] = $topicsManager->getNbTopics($subCategory);
			$subCategories[$i]['nbMessages'] = $data2['nbMessages'];
			
			if($query3->rowCount() > 0) {
				$subCategories[$i]['topicWithMostRecentMsg'] = $data3['title'];
				$subCategories[$i]['lastMsgPosted'] = Config::DateTimeFormat($data3['publicationDate']);
				$subCategories[$i]['topicWithMostRecentMsgURL'] = Config::URLFormat($subCategory->getName()) . '/' . Config::URLFormat($data3['title']);
			}
			
			$i++;
		}
	
		return $subCategories;
	}

    /**
     * Removes a subcategory from the DB
     * @param SubCategory $subCategory
     */
    public function remove(SubCategory $subCategory) {
        //We remove all the topics and messages related to the subcategory
        $topicsManager = new TopicsManager();
        $topicsManager->removeTopicsRelatedToSubCategory($subCategory);

	    $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_subcategories WHERE id = :id');
        $query->bindValue('id', $subCategory->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Removes all the subcategories of a specific category
     * @param Category $category
     */
    public function removeSubcategoriesRelatedToCategory(Category $category) {
	    //We have to remove the topics and messages related to every subcategory that is to be removed
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_subcategories WHERE idCategory = :idCategory');
        $query->bindValue('idCategory', $category->getId(), PDO::PARAM_INT);
        $query->execute();

        $subCategory = new SubCategory();

        while($data = $query->fetch()) {
            $subCategory->hydrate($data);

            $topicsManager = new TopicsManager();
            $topicsManager->removeTopicsRelatedToSubCategory($subCategory);

            $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_subcategories WHERE id = :id');
            $query->bindValue('id', $subCategory->getId(), PDO::PARAM_INT);
            $query->execute();
        }
    }

    /**
     * Selects and returns a specific category from the DB
     * @param SubCategory $subCategory
     * @return SubCategory|null Null if no subcategory found
     */
    public function get(SubCategory $subCategory) {
		$returnedSubcategory = new SubCategory();
		
		//Search subcategory by name
		if(!empty($subCategory->getName())) {
			$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'forum_subcategories');
		
			//We convert each subcategory name to the URL Format and compare it to the parameter
			while($data = $query->fetch()) {
                $returnedSubcategory->hydrate($data);
				
				if(Config::urlFormat($returnedSubcategory->getName()) == Config::urlFormat($subCategory->getName())) //Name might not be given in URL Format
					return $returnedSubcategory;
			}
			
			return null;
		} elseif(!empty($subCategory->getId())) { //Search subcategory by ID
			$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_subcategories WHERE id = :idSubCategory');
			$query->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
			$query->execute();
			
			if($query->rowCount() > 0) {
				$data = $query->fetch();
                $returnedSubcategory->hydrate($data);
			
				return $returnedSubcategory;
			} else
				return null;
		}
	}
}