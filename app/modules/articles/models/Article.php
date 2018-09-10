<?php
namespace app\modules\articles\models;

use config\Exception;
use config\Config;

/**
 * Class Article
 * Article entity as it is saved in the DB
 * @package app\modules\articles\models
 */
class Article {
    /**
     * @var int
     */
    private $_id;
    /**
     * @var int
     */
    private $_idAuthor;
    /**
     * @var int
     */
    private $_idCategory;
    /**
     * @var string
     */
    private $_title;
    /**
     * @var string
     */
    private $_content;
    /**
     * @var string
     */
    private $_description;
    /**
     * @var string
     */
    private $_thumbnail;
    /**
     * @var int Determines whether article is a draft, in validation or published
     */
    private $_status;
    /**
     * @var string
     */
    private $_publicationDate;

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

    /**
     * Return the table of contents by retrieving BBCode titles from the article content
     * @param string $article Contains article content
     * @return string
     */
    public function getTableContents($article) {
		preg_match_all('#\[h\](.+)\[/h\]#', $article, $contents, PREG_SET_ORDER);
		
		$html = '<ul class="list-group">';
		
		foreach($contents as $content)
			$html .= '<li class="list-group-item"><a href="#' . $content[1] . '" class="link">' . $content[1] . '</a></li>';
			
		$html .= '</ul>';
		
		return $html;
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
				throw new Exception("article_idShouldBeNumeric");
		} else
			throw new Exception("article_idCannotBeEmpty");
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
				$this->_idAuthor = htmlspecialchars($idAuthor);
			else
				throw new Exception("article_idAuthorShouldBeNumeric");
		} else
			throw new Exception("article_idAuthorCannotBeEmpty");
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
				throw new Exception("article_idCategoryShouldBeNumeric");
		} else
			throw new Exception("article_idCategoryCannotBeEmpty");
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
			    if(Config::urlFormat($title) != 'my-article') {
                    if(preg_match("#[^a-zA-Z0-9ÇçèéêëÈÉÊËàáâãäå@ÀÁÂÃÄÅìíîïÌÍÎÏðòóôõöÒÓÔÕÖùúûüÙÚÛÜýÿÝ -]#", $title) != 1)
                        $this->_title = htmlspecialchars($title);
                    else
                        throw new Exception("article_titleContainsUnauthorizedChars");
			    } else
			        throw new Exception("article_titleIsReservedWord");
            } else
				throw new Exception("article_titleShouldBeString");
		} else
			throw new Exception("article_titleCannotBeEmpty");
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
				throw new Exception("article_contentShouldBeString");
		} else
			throw new Exception("article_contentCannotBeEmpty");
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
				throw new Exception("article_descriptionShouldBeString");
		} else
			throw new Exception("article_descriptionCannotBeEmpty");
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
				throw new Exception("article_thumbnailShouldBeString");
		} else
			throw new Exception("article_thumbnailCannotBeEmpty");
	}

    /**
     * @return int
     */
    public function getStatus() {
		return $this->_status;
	}

    /**
     * @param int $status
     * @throws Exception
     */
    public function setStatus($status) {
		if(is_numeric($status))
			$this->_status = $status;
		else
			throw new Exception("article_statusShouldBeNumeric");
	}

    /**
     * @return string
     */
    public function getPublicationDate() {
		return $this->_publicationDate;
	}

    /**
     * @param string $publicationDate
     * @throws Exception
     */
    public function setPublicationDate($publicationDate) {
		if(!empty($publicationDate)) 
			$this->_publicationDate = htmlspecialchars($publicationDate);
		else
			throw new Exception("article_publicationDateCannotBeEmpty");
	}
}