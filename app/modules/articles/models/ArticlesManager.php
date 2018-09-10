<?php
namespace app\modules\articles\models;

use config\Config;
use \PDO;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class ArticlesManager
 * DB manager for the article entity
 * @package app\modules\articles\models
 */
class ArticlesManager {
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
     * ArticlesManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores an article in the DB
     * @param Article $article
     */
    public function add(Article $article) {
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'articles_articles(idAuthor, idCategory, title, description, content, status) VALUES(:idAuthor, :idCategory, :title, :description, :content, :status)');
		
		$query->bindValue('idAuthor', $article->getIdAuthor(), PDO::PARAM_STR);
		$query->bindValue('idCategory', $article->getIdCategory(), PDO::PARAM_INT);
		$query->bindValue('title', $article->getTitle(), PDO::PARAM_STR);
		$query->bindValue('description', $article->getDescription(), PDO::PARAM_STR);
		$query->bindValue('content', $article->getContent(), PDO::PARAM_STR);
		$query->bindValue('status', $article->getStatus(), PDO::PARAM_INT);
		
		$query->execute();
	}

    /**
     * Updates the attributes of an article and saves them in the DB
     * @param Article $article
     */
    public function update(Article $article) {
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'articles_articles SET idCategory = :idCategory, title = :title, description = :description, content = :content, status = :status WHERE id = :id');
		
		$query->bindValue('id', $article->getId(), PDO::PARAM_INT);
		$query->bindValue('idCategory', $article->getIdCategory(), PDO::PARAM_INT);
		$query->bindValue('title', $article->getTitle(), PDO::PARAM_STR);
		$query->bindValue('description', $article->getDescription(), PDO::PARAM_STR);
		$query->bindValue('content', $article->getContent(), PDO::PARAM_STR);
		$query->bindValue('status', $article->getStatus(), PDO::PARAM_INT);
		
		$query->execute();
	}

    /**
     * Removes an article from the DB
     * @param Article $article
     */
    public function remove(Article $article) {
		$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'articles_articles WHERE id = :id');
		$query->bindValue('id', $article->getId(), PDO::PARAM_INT);
		$query->execute();
	}

    /**
     * Remove all the articles of a given category from the DB
     * @param Category $category
     */
    public function removeArticlesRelatedToCategory(Category $category) {
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'articles_articles WHERE idCategory = :idCategory');
        $query->bindValue('idCategory', $category->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Selects and returns an article from the DB based on its title or its ID
     * @param Article $article
     * @return Article|null Null if no article found
     */
    public function get(Article $article) {
		$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles');
		
		//Check article by title
		if(!empty($article->getTitle())) {
			while($data = $query->fetch()) {
				if(Config::urlFormat($data['title']) == Config::urlFormat($article->getTitle())) {
				    $articleReturned = new Article();
                    $articleReturned->hydrate($data);
                    return $articleReturned;
                }
			}
		} elseif(!empty($article->getId())) { //Check article by id
            while($data = $query->fetch()) {
                if($data['id'] == $article->getId()) {
                    $articleReturned = new Article();
                    $articleReturned->hydrate($data);
                    return $articleReturned;
                }
            }
        }

        return null;
	}

    /**
     * Returns the list of articles of a given member
     * @param Article $article
     * @return array Array of articles
     */
    public function getListMemberArticles(Article $article) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE idAuthor = :idAuthor');
		$query->bindValue('idAuthor', $article->getIdAuthor(), PDO::PARAM_STR);
		$query->execute();
		
		//We store the articles in an array
		$articles = array();
		$i = 0;
		
		while($data = $query->fetch()) {
			$article->hydrate($data);
			
			$articles[$i]['id'] = $article->getId();
			$articles[$i]['title'] = $article->getTitle();
			$articles[$i]['status'] = $article->getStatus();
			
			//If the article is published, we save its URL
			if($article->getStatus() == 2) {
			    $category = new Category();
			    $categoriesManager = new CategoriesManager();

			    $category->setId($article->getIdCategory());
			    $category = $categoriesManager->get($category);

                $articles[$i]['url'] = Config::getPath() . "/articles/" . Config::urlFormat($category->getName()) . "/" . Config::urlFormat($article->getTitle());
            }

			$i++;
		}
		
		return $articles;
	}

    /**
     * Returns the list of validated articles of a specific member
     * @param Member $member
     * @return array Array of articles
     */
    public function getListMemberValidatedArticles(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE idAuthor = :idAuthor AND status = 2 ORDER BY publicationDate DESC');
        $query->bindValue('idAuthor', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        $articles = array();
        $i = 0;

        $categoriesManager = new CategoriesManager();

        while($data = $query->fetch()) {
            $article = new Article();
            $article->hydrate($data);

            $articles[$i]['title'] = $article->getTitle();
            $articles[$i]['description'] = $article->getDescription();
            $articles[$i]['publicationDate'] = Config::dateFormat($article->getPublicationDate());

            //We get the name of the article article
            $category = new Category();
            $category->setId($article->getIdCategory());
            $category = $categoriesManager->get($category);
            $articles[$i]['category'] = $category->getName();

            $articles[$i]['url'] = Config::getPath() . '/articles/' . Config::urlFormat($category->getName()) . '/' . Config::urlFormat($article->getTitle());

            $i++;
        }

        return $articles;
    }

    /**
     * Returns the list of articles from a given category
     * @param Category $category
     * @param $sqlLimit SQL Limit statement for the pagination system
     * @return array Array of articles
     */
    public function getListArticles(Category $category, $sqlLimit) {
		//If no category is specified
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE idCategory = :idCategory AND status = 2 ORDER BY publicationDate DESC ' . $sqlLimit);
        $query->bindValue('idCategory', $category->getId(), PDO::PARAM_INT);
        $query->execute();

		$articles = array();
        $article = new Article();
		$i = 0;

		$categoriesManager = new CategoriesManager();
		$membersManager = new MembersManager();
		
		while($data = $query->fetch()) {
			$article->hydrate($data);
			
			$articles[$i]['id'] = $article->getId();
			$articles[$i]['title'] = $article->getTitle();
			$articles[$i]['description'] = $article->getDescription();
			$articles[$i]['thumbnail'] = $article->getThumbnail();
			$articles[$i]['publicationDate'] = Config::dateFormat($data['publicationDate']);

            $category = new Category();
			$category->setId($data['idCategory']);
			
			$articles[$i]['category'] = $categoriesManager->get($category)->getName();
			$articles[$i]['articleURL'] = Config::getPath() . "/articles/" . Config::urlFormat($categoriesManager->get($category)->getName()) . "/" . Config::urlFormat($article->getTitle());
			$articles[$i]['categoryURL'] = Config::getPath() . "/articles/" . Config::urlFormat($categoriesManager->get($category)->getName());

            $author = new Member();
			$author->setId($article->getIdAuthor());
			$author = $membersManager->get($author);
			
			$articles[$i]['author'] = $author->getUsername();
			
			$i++;
		}
		
		return $articles;
	}

    /**
     * Returns the total number of articles or the number of a given category if specified
     * @param Category|null $category
     * @return int
     */
    public function getNbArticles(Category $category = null) {
		//If no category is specified we count all the articles
		if(is_null($category))
			$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE status = 2');
		else {
			$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE status = 2 AND idCategory = :idCategory');
			$query->bindValue('idCategory', $category->getId(), PDO::PARAM_INT);
		}
		
		$query->execute();
		return $query->rowCount();
	}

    /**
     * Returns the list of articles waiting for validation
     * @return array Array of articles
     */
    public function getListPendingArticles() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE status = 1 ORDER BY publicationDate DESC');

        $article = new Article();
        $articles = array();
        $i = 0;

        while($data = $query->fetch()) {
            $article->hydrate($data);

            $articles[$i]['id'] = $article->getId();
            $articles[$i]['title'] = $article->getTitle();
            $articles[$i]['description'] = $article->getDescription();
            $articles[$i]['publicationDate'] = Config::dateFormat($article->getPublicationDate());

            $i++;
        }

        return $articles;
    }

    /**
     * Returns the list of published articles
     * @param $sqlLimit SQL Limit statement for the pagination system
     * @return array Array of articles
     */
    public function getListValidatedArticles($sqlLimit) {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE status = 2 ORDER BY publicationDate DESC ' . $sqlLimit );

        $article = new Article();
        $articles = array();
        $i = 0;

        while($data = $query->fetch()) {
            $article->hydrate($data);

            $articles[$i]['id'] = $article->getId();
            $articles[$i]['title'] = $article->getTitle();
            $articles[$i]['description'] = $article->getDescription();
            $articles[$i]['publicationDate'] = Config::dateFormat($article->getPublicationDate());

            //Category name
            $category = new Category();
            $category->setId($article->getIdCategory());
            $categoriesManager = new CategoriesManager();
            $category = $categoriesManager->get($category);
            $articles[$i]['category'] = $category->getName();

            $articles[$i]['articleURL'] = Config::getPath() . "/articles/" . Config::urlFormat($category->getName()) . "/" . Config::urlFormat($article->getTitle());

            $i++;
        }

        return $articles;
    }

    /**
     * Returns a list of the latest published articles
     * @return array Array of articles
     */
    public function getLatestArticles() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE status = 2 ORDER BY publicationDate DESC LIMIT 0, 10');

        $articles = array();
        $article = new Article();
        $i = 0;

        $categoriesManager = new CategoriesManager();
        $membersManager = new MembersManager();

        while($data = $query->fetch()) {
            $article->hydrate($data);

            $articles[$i]['id'] = $article->getId();
            $articles[$i]['title'] = $article->getTitle();
            $articles[$i]['description'] = $article->getDescription();
            $articles[$i]['thumbnail'] = $article->getThumbnail();
            $articles[$i]['publicationDate'] = Config::dateFormat($data['publicationDate']);

            $category = new Category();
            $category->setId($data['idCategory']);

            $articles[$i]['category'] = $categoriesManager->get($category)->getName();
            $articles[$i]['articleURL'] = Config::getPath() . "/articles/" . Config::urlFormat($categoriesManager->get($category)->getName()) . "/" . Config::urlFormat($article->getTitle());
            $articles[$i]['categoryURL'] = Config::getPath() . "/articles/" . Config::urlFormat($categoriesManager->get($category)->getName());

            $author = new Member();
            $author->setId($article->getIdAuthor());
            $author = $membersManager->get($author);

            $articles[$i]['author'] = $author->getUsername();

            $i++;
        }

        return $articles;
    }

    /**
     * Returns the number of articles published by a specific member
     * @param Member $member
     * @return int
     */
    public function getNbArticlesPublished(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE idAuthor = :idMember AND status = 2');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount();
    }

    /**
     * Returns a list of articles which title matches with the argument
     * @param string $content
     * @return array Array of articles
     */
    public function search($content) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'articles_articles WHERE title LIKE :content LIMIT 0, 15 ORDER BY RAND()');
        $query->bindValue('content', '%' . $content . '%', PDO::PARAM_STR);
        $query->execute();

        $category = new Category;
        $categoriesManager = new CategoriesManager();

        $articles = array();
        $i = 0;

        while($data = $query->fetch()) {
            $articles[$i]['title'] = $data['title'];
            $articles[$i]['description'] = $data['description'];

            $category->setId($data['idCategory']);
            $articles[$i]['category'] = $categoriesManager->get($category)->getName();
            $articles[$i]['url'] = Config::getPath() . '/articles/' . Config::urlFormat($categoriesManager->get($category)->getName()) . '/' . Config::urlFormat($data['title']);

            $i++;
        }

        return $articles;
    }
}