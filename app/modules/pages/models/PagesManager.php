<?php
namespace app\modules\pages\models;

use \PDO;

use config\Config;

/**
 * Class PagesManager
 * DB manager for the page entity
 * @package app\modules\pages\models
 */
class PagesManager {
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
     * PagesManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Selects and returns a specific page from the DB
     * @param Page $page
     * @return Page|null Returns a page or return null if no results found
     */
    public function get(Page $page) {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'pages_pages');
        $returnedPage = new Page();

        while($data = $query->fetch()) {
            //Search by name
            if(!empty($page->getName())) {
                if (Config::urlFormat($data['name']) == Config::urlFormat($page->getName())) {
                    $returnedPage->hydrate($data);
                    return $returnedPage;
                }
            } elseif(!empty($page->getId())) { //Search by id
                if($data['id'] == $page->getId()) {
                    $returnedPage->hydrate($data);
                    return $returnedPage;
                }
            }
        }

        return null;
    }

    /**
     * Selects and returns the homepage from the DB
     * @return Page|null Returns a page or returns null if no results found
     */
    public function getHomepage() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'pages_pages WHERE isHomepage = 1');

        if($query->rowCount() > 0) {
            $data = $query->fetch();
            $page = new Page();
            $page->hydrate($data);

            return $page;
        } else
            return null;
    }

    /**
     * Returns the list of pages
     * @return array Array of pages
     */
    public function getListPages() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'pages_pages' . ' ORDER BY isHomepage DESC, name ASC');

        $pages = array();
        $i = 0;

        while($data = $query->fetch()) {
            $page = new Page();
            $page->hydrate($data);

            $pages[$i]['id'] = $page->getId();
            $pages[$i]['name'] = $page->getName();
            $pages[$i]['pageURL'] = Config::getPath() . '/pages/' . Config::urlFormat($page->getName());
            $pages[$i]['ishomepage'] = $page->getIsHomepage();

            $i++;
        }

        return $pages;
    }

    /**
     * Stores a page in the DB
     * @param Page $page
     */
    public function add(Page $page) {
        $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'pages_pages(name, content, ishomepage) VALUES(:name, :content, :ishomepage)');
        $query->bindValue(':name', $page->getName(), PDO::PARAM_STR);
        $query->bindValue(':content', $page->getContent(), PDO::PARAM_STR);
        $query->bindValue(':ishomepage', $page->getIsHomepage(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Updates page attributes and saves them in the DB
     * @param Page $page
     */
    public function update(Page $page) {
        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'pages_pages SET name = :name, content = :content WHERE id = :id');
        $query->bindValue(':id', $page->getId(), PDO::PARAM_INT);
        $query->bindValue(':name', $page->getName(), PDO::PARAM_STR);
        $query->bindValue(':content', $page->getContent(), PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Removes a specific page from the DB
     * @param Page $page
     */
    public function remove(Page $page) {
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'pages_pages WHERE id = :id');
        $query->bindValue(':id', $page->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Sets old homepage as normal page and specified page as new homepage
     * @param Page $page
     */
    public function changeHomepage(Page $page) {
        //We set the old homepage as a normal page
        $query = $this->_db->query('UPDATE ' . $this->_tablePrefix . 'pages_pages SET isHomepage = 0 WHERE isHomepage = 1');

        //We set the new homepage
        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'pages_pages SET isHomepage = 1 WHERE id = :id');
        $query->bindValue(':id', $page->getId(), PDO::PARAM_INT);
        $query->execute();
    }
}