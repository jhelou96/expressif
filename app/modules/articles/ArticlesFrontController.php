<?php
namespace app\modules\articles;

use config\Config;
use config\Exception;
use vendor\Pagination;
use vendor\editor\BBCode;

use app\modules\members\models\Member;
use app\modules\articles\models\Category;
use app\modules\articles\models\Article;

use app\modules\articles\models\CategoriesManager;
use app\modules\articles\models\ArticlesManager;
use app\modules\members\models\MembersManager;

use app\modules\Module;

/**
 * Class ArticlesFrontController
 * Articles module controller for the frontend interface
 * @package app\modules\articles
 */
class ArticlesFrontController extends Module {
    /**
     * @var categoriesManager
     * DB manager object for the articles category entity
     */
    private $_categoriesManager;
    /**
     * @var articlesManager
     * DB manager object for the article entity
     */
    private $_articlesManager;
    /**
     * @var membersManager
     * DB manager object for the member entity
     */
    private $_membersManager;

    /**
     * @var BBCode
     * BBCode object used to parse BBCode
     */
    private $_bbcode;

    /**
     * ArticlesFrontController constructor.
     */
    public function __construct() {
        //Module is instantiated using the parent class
        $this->run();

        //We load the managers related to this module
        $this->_membersManager = new MembersManager();
        $this->_categoriesManager = new CategoriesManager();
        $this->_articlesManager = new ArticlesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Displays the list of categories and latest articles
     */
    public function articles() {
		try {
			$categories = $this->_categoriesManager->getListCategories();
			$articles = $this->_articlesManager->getLatestArticles();

			$this->_smarty->assign(array(
				"articles" => $articles,
				"categories" => $categories
			));
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}	

		$this->_smarty->display("app/modules/articles/views/frontend/tpl/articles.tpl");
	}

    /**
     * Displays the list of articles of a given category
     * @param array $args Gets the name of the category and the page number from the URL
     */
    public function showCategory($args) {
        try {
            //We check if the category exists and get its info from the DB
            $category = new Category();
            $category->setName($args['category']);
            $category = $this->_categoriesManager->get($category);
            if(is_null($category))
                throw new Exception("accessError_404");

            //Pagination system
            $pagination = new Pagination();
            $pagination->setnbRecords($this->_articlesManager->getNbArticles($category));
            if (isset($args['page']))
                $pagination->setActualPage($args['page']);
            $pagination->execute();

            $articles = $this->_articlesManager->getListArticles($category, $pagination->sqlLimit());

            $this->_smarty->assign(array(
                "articles" => $articles,
                "articles_categoryName" => $category->getName(),
                "articles_categoryNameURLFormat" => Config::urlFormat($category->getName()),
                "pagination" => $pagination->parse()
            ));

            $this->_smarty->display("app/modules/articles/views/frontend/tpl/showCategory.tpl");
        } catch(Exception $e) {
            header('Location: ' . $this->_path . '/articles');
            exit();
        }
	}

    /**
     * Displays the article
     * @param array $args Gets the name of the category and the title of the article from the URL
     */
    public function showArticle($args) {
		try {
            //We check if the category exists
			$category = new Category();
			$category->setName($args['category']);
			$category = $this->_categoriesManager->get($category);
            if(is_null($category))
                throw new Exception("accessError_404");

            //We check if the article exists
			$article = new Article();
			$article->setTitle($args['titleArticle']);
			$article = $this->_articlesManager->get($article);
            if(is_null($article))
                throw new Exception("accessError_404");

			//We check if the article has been validated
            if($article->getStatus() != 2)
                throw new Exception("accessError_404");

			//We get info about the author
			$author = new Member();
			$author->setId($article->getIdAuthor());
			$author = $this->_membersManager->get($author);
			
			//We check if the category and article specified in the URL matches
			if($category->getId() != $article->getIdCategory())
			    throw new Exception("accessError_404");
			
			$this->_smarty->assign(array(
            "article_id" => $article->getId(),
			"article_author" => $author->getUsername(),
			"article_category" => $category->getName(),
			"article_title" => $article->getTitle(),
			"article_content" => $this->_bbcode->parseBBCode($article->getContent()),
			"article_thumbnail" => $article->getThumbnail(),
			"article_status" => $article->getStatus(),
			"article_publicationDate" => Config::dateFormat($article->getPublicationDate()),
			"article_tableContents" => $article->getTableContents($article->getContent())
			));
			
			$this->_smarty->display("app/modules/articles/views/frontend/tpl/showArticle.tpl");
		} catch(Exception $e) {
            header('Location: ' . $this->_path . '/articles');
            exit();
		}
	}

    /**
     * Allows the user(member) to write an article
     */
    public function writeArticle() {
	    try {
            //If the user is not logged in, he can't access this page
            if (!$this->_isConnected) {
                header('Location: ' . $this->_path . '/members/login');
                exit();
            }

            //We check if the article has been submitted
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $article = new Article();
                $article->setIdAuthor($this->_member->getId());
                $article->setContent($_POST['article_content']);
                $article->setDescription($_POST['article_description']);
                $article->setIdCategory($_POST['article_category']);

                //We check if an article with the same title exists
                $article->setTitle($_POST['article_title']);
                if(!is_null($this->_articlesManager->get($article)))
                    throw new Exception("articleWithSameTitleAlreadyExists");

                if (isset($_POST['submit'])) { //If the user chose to submit the article
                    $article->setStatus(1);
                    //We save a success message to display in the myArticles.tpl file
                    setcookie('module_successMsg', 'articleHasBeenSubmitted', time() + 365 * 24 * 3600, '/', null, false, true);

                } elseif (isset($_POST['save'])) { //If the user chose to save the article
                    $article->setStatus(0);
                    //We save a success message to display in the myArticles.tpl file
                    setcookie('module_successMsg', 'articleHasBeenSaved', time() + 365 * 24 * 3600, '/', null, false, true);
                }

                $this->_articlesManager->add($article);

                header('Location: ' . Config::getPath() . '/articles/my-articles');
                exit();
            }
        } catch(Exception $e) {
	        //We save temporarily the content of the fields so the user doesn't have to fill them again
            if(isset($_POST['article_content']) AND !empty($_POST['article_content']))
                $this->_smarty->assign(array("article_content" => $_POST['article_content']));
            if(isset($_POST['article_title']) AND !empty($_POST['article_title']))
                $this->_smarty->assign(array("article_title" => $_POST['article_title']));
            if(isset($_POST['article_description']) AND !empty($_POST['article_description']))
                $this->_smarty->assign(array("article_description" => $_POST['article_description']));
            if(isset($_POST['article_category']) AND !empty($_POST['article_category']))
                $this->_smarty->assign(array("article_category" => $_POST['article_category']));

            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $categories = $this->_categoriesManager->getListCategories(); //We get the list of categories
            $this->_smarty->assign(array("article_categories" => $categories));
            $this->_smarty->display("app/modules/articles/views/frontend/tpl/writeArticle.tpl");
        }
	}

    /**
     * Allows the user(author) to edit a draft article
     * @param array $args Gets the ID of the article from the URL
     */
    public function editArticle($args) {
		try {
			//If the user is not logged in, he can't access this page
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}

			//We check if the article exists
			$article = new Article();
			$article->setId($args['id']);
			$article = $this->_articlesManager->get($article);
            if(is_null($article))
                throw new Exception("accessError_404");
				
			//We check if the user is the author of the article
			if($article->getIdAuthor() != $this->_member->getId()) {
				header('Location: ' . $this->_path . '/articles/my-articles');
				exit();
			}
			
			//We check if the article can be edited
			if($article->getStatus() != 0) { //If the article is not a draft  the user can't edit it
				header('Location: ' . $this->_path . '/articles/my-articles');
				exit();
			}

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $article->setIdAuthor($this->_member->getId());
                $article->setContent($_POST['article_content']);
                $article->setDescription($_POST['article_description']);
                $article->setIdCategory($_POST['article_category']);

                //We check if there is any other article with the same title
                $article->setTitle($_POST['article_title']);
                if(!is_null($this->_articlesManager->get($article)) AND $this->_articlesManager->get($article)->getId() != $article->getId())
                    throw new Exception("articleWithSameTitleAlreadyExists");

                if (isset($_POST['submit'])) { //If the user chose to submit the article
                    $article->setStatus(1);
                    //We save a success message to display in the myArticles.tpl file
                    setcookie('module_successMsg', 'articleHasBeenSubmitted', time() + 365 * 24 * 3600, '/', null, false, true);

                } elseif (isset($_POST['save'])) { //If the user chose to save the article
                    $article->setStatus(0);
                    //We save a success message to display in the myArticles.tpl file
                    setcookie('module_successMsg', 'articleHasBeenSaved', time() + 365 * 24 * 3600, '/', null, false, true);
                }

                $this->_articlesManager->update($article);

                header('Location: ' . $this->_path . '/articles/my-articles');
                exit();
			} else {
				//If the article has not been posted, we get the values from the DB
				$this->_smarty->assign(array(
					"article_content" => $article->getContent(),
					"article_title" => $article->getTitle(),
					"article_description" => $article->getDescription(),
					"article_category" => $article->getIdCategory()
				));
			}
		} catch(Exception $e) {
            //We save temporarily the content of the fields so the user doesn't have to fill them again
            if(isset($_POST['article_content']) AND !empty($_POST['article_content']))
                $this->_smarty->assign(array("article_content" => $_POST['article_content']));
            if(isset($_POST['article_title']) AND !empty($_POST['article_title']))
                $this->_smarty->assign(array("article_title" => $_POST['article_title']));
            if(isset($_POST['article_description']) AND !empty($_POST['article_description']))
                $this->_smarty->assign(array("article_description" => $_POST['article_description']));
            if(isset($_POST['article_category']) AND !empty($_POST['article_category']))
                $this->_smarty->assign(array("article_category" => $_POST['article_category']));

			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
            $categories = $this->_categoriesManager->getListCategories(); //We get the list of categories

            $this->_smarty->assign(array(
                "article_categories" => $categories,
                "article_id" => $article->getId()
            ));
            $this->_smarty->display("app/modules/articles/views/frontend/tpl/editArticle.tpl");
        }
	}

    /**
     * Allows the user(author) to remove a draft article
     * @param array $args Gets the ID of the article from the URL
     */
    public function removeArticle($args) {
        try {
            //If the user is not logged in, he can't access this page
            if(!$this->_isConnected) {
                header('Location: ' . $this->_path . '/members/login');
                exit();
            }

            //We check if article exists
		    $article = new Article();
			$article->setId($args['id']);
            if(is_null($this->_articlesManager->get($article)))
                throw new Exception("accessError_404");
			
			//We check if the user is the author of the article
			if($article->getIdAuthor() != $this->_member->getId()) {
				header('Location: ' . $this->_path . '/articles/my-articles');
				exit();
			}
			
			//If the article is not a draft, it can't be deleted
			if($article->getStatus() != 0) {
				header('Location: ' . $this->_path . '/articles/my-articles');
				exit();
			}
			
			$this->_articlesManager->remove($article);
			
			//We save a success message to display in the myArticles.tpl file
            setcookie('module_successMsg', 'articleHasBeenRemoved', time() + 365 * 24 * 3600, '/', null, false, true);
			header('Location: ' . Config::getPath() . '/articles/my-articles');
		} catch(Exception $e) {
			header('Location: ' . $this->_path . '/articles/my-articles');
			exit();
		}
	}

    /**
     * Displays the list of articles written by the user
     */
    public function myArticles() {
		try {
			//If the user is not logged in, he can't access this page
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			$article = new Article();
			$article->setIdAuthor($this->_member->getId());
			
			$articles = $this->_articlesManager->getListMemberArticles($article);

			$this->_smarty->assign(array("articles" => $articles));
			
		} catch(Exception $e) {
			header('Location: ' . $this->_path . '/members/login');
			exit();
		} finally {
            $this->_smarty->display("app/modules/articles/views/frontend/tpl/myArticles.tpl");
        }
	}

    /**
     * Displays the list of articles which title matches with the search input
     */
    public function search() {
        try {
            if (empty($_POST['articles_search']))
                throw new Exception('searchInputCannotBeEmpty');

            if(strlen($_POST['articles_search'] < 3))
                throw new Exception('searchInputTooShort');

            $this->_smarty->assign(array("search_articles" => $this->_articlesManager->search($_POST['articles_search'])));
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $this->_smarty->display("app/modules/articles/views/frontend/tpl/search.tpl");
        }
    }
}