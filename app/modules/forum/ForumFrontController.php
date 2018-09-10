<?php
namespace app\modules\forum;

use app\modules\forum\models\CategoriesManager;
use app\modules\forum\models\LikesManager;
use app\modules\forum\models\MessagesManager;
use app\modules\forum\models\SubCategoriesManager;
use app\modules\forum\models\TopicsManager;

use config\Config;
use config\Exception;
use vendor\editor\BBCode;
use vendor\Pagination;

use app\modules\forum\models\SubCategory;
use app\modules\forum\models\Topic;
use app\modules\forum\models\Message;

use app\modules\Module;

/**
 * Class ForumFrontController
 * Forum module controller for the frontend interface
 * @package app\modules\forum
 */
class ForumFrontController extends Module {
    /**
     * @var BBCode
     * BBCode object used to parse BBCode
     */
    private $_bbcode;

    /**
     * @var CategoriesManager
     * DB manager object for the forum category entity
     */
    private $_categoriesManager;

    /**
     * @var SubCategoriesManager
     * DB manager object for the forum subcategory entity
     */
    private $_subCategoriesManager;

    /**
     * @var TopicsManager
     * DB manager object for the forum topic entity
     */
    private $_topicsManager;

    /**
     * @var MessagesManager
     * DB manager object for the forum message entity
     */
    private $_messagesManager;

    /**
     * @var LikesManager
     * DB manager object for the forum like entity
     */
    private $_likesManager;

    /**
     * ForumFrontController constructor.
     */
    public function __construct() {
        $this->run();

        //We load the managers related to this module
        $this->_categoriesManager = new CategoriesManager();
        $this->_subCategoriesManager = new SubCategoriesManager();
        $this->_topicsManager = new TopicsManager();
        $this->_messagesManager = new MessagesManager();
        $this->_likesManager = new LikesManager();

        //We load the BBCode buttons of the editor
        define('IN_MEGA_BBCODE', true);
        $this->_bbcode = new BBCode();
        $this->_smarty->assign(array("bbcodeEditor_buttons" => $this->_bbcode->bbcodebuttons()));
    }

    /**
     * Displays the list of subcategories under their corresponding category
     */
    public function forum() {
		try {
			$categories = $this->_categoriesManager->get();
			$subCategories = $this->_subCategoriesManager->getListSubcategories();
			
			$this->_smarty->assign(array(
				"forum_categories" => $categories,
				"forum_subCategories" => $subCategories
			));
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
		
		$this->_smarty->display("app/modules/forum/views/frontend/tpl/forum.tpl");
	}

    /**
     * Displays the list of topics of a given subcategory
     * @param array $args Gets the name of the subcategory and page number from the URL
     */
    public function showSubCategory($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
			    throw new Exception("accessError_404");
			
			$this->_smarty->assign(array(
				"forum_subCategoryId" => $subCategory->getId(),
				"forum_subCategoryName" => $subCategory->getName(),
				"forum_subCategoryDesc" => $subCategory->getDescription(),
				"forum_subCategoryURL" => Config::urlFormat($subCategory->getName())
			));
			
			$pagination = new Pagination();
			$pagination->setnbRecords($this->_topicsManager->getNbTopics($subCategory));
			
			if(isset($args['page'])) 
				$pagination->setActualPage($args['page']);
			
			$pagination->execute();
			
			$topics = $this->_topicsManager->getTopicsList($subCategory, $pagination->sqlLimit());
			
			//We set topic views if the user is connected
			if($this->_isConnected) {
				//We deal with the postit topics
				for($i = 0; $i < count($topics['postit']); $i++) {
				    $topic = new Topic();
				    $topic->setId($topics['postit'][$i]['id']);
                    $topics['postit'][$i]['view'] = $this->_topicsManager->checkTopicViews($this->_member, $topic);
                }

				//We deal with the normal topics
				for($i = 0; $i < count($topics['normal']); $i++) {
				    $topic = new Topic();
				    $topic->setId($topics['normal'][$i]['id']);
                    $topics['normal'][$i]['view'] = $this->_topicsManager->checkTopicViews($this->_member, $topic);
                }
			} else {
				//We deal with the postit topics
				for($i = 0; $i < count($topics['postit']); $i++) 
					$topics['postit'][$i]['view'] = 0;
				
				//We deal with the normal topics
				for($i = 0; $i < count($topics['normal']); $i++) 
					$topics['normal'][$i]['view'] = 0;
			}
		
			if(!empty($topics['postit']) OR !empty($topics['normal'])) {
				$this->_smarty->assign(array(
					"forum_topics" => $topics,
					"pagination" => $pagination->parse()
				));
			} else
				throw new Exception("noTopicsFound");
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
			$this->_smarty->display("app/modules/forum/views/frontend/tpl/showSubCategory.tpl");
		}
	}

    /**
     * Displays the list of messages of a given topic
     * @param array $args Gets the name of the subcategory, the name of the topic and the page number from the URL
     */
    public function showTopic($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
			    throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
            if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
			    throw new Exception("accessError_404");
			
			$this->_smarty->assign(array(
				"forum_subCategoryName" => $subCategory->getName(),
				"forum_topicId" => $topic->getId(),
				"forum_topicTitle" => $topic->getTitle(), 
				"forum_topicSolved" => $topic->getSolved(),
				"forum_topicPostit" => $topic->getPostit(),
				"forum_topicLocked" => $topic->getLocked(),
				"forum_postURL" => Config::urlFormat($subCategory->getName()) . '/' . Config::urlFormat($topic->getTitle()) . ((isset($args['page'])) ? '/page/' . $args['page'] : ''),
				"forum_subCategoryURL" => Config::urlFormat($subCategory->getName()),
				"forum_topicURL" => Config::urlFormat($topic->getTitle()),
				"isAuthor" => (($this->_isConnected AND $this->_member->getId() == $topic->getIdAuthor()) ? true : false)
			));
			
			$pagination = new Pagination();
			$pagination->setnbRecords($this->_messagesManager->getNbMessages($topic));
			
			if(isset($args['page'])) 
				$pagination->setActualPage($args['page']);
			
			$pagination->execute();
			
			//We check if the form has been submitted
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
				//If the user is not logged in he can't post a new answer
				if(!$this->_isConnected) { 
					header('Location: ' . $this->_path . '/members/login');
					exit();
				}

				if($topic->getLocked())
					throw new Exception("errorTopicLocked");
				else {
					if(isset($_POST['post'])) { //The user wants to post the message
						$message = new Message();
						$message->setMessage($_POST['editor_textarea']);
						$message->setIdAuthor($_SESSION['idUser']);
						$message->setIdTopic($topic->getId());
						$message->setMsgInTopic($this->_messagesManager->getMaxMsgInTopic($topic) + 1);
						
						$this->_messagesManager->add($message);
						
						//We redirect the user to the new answer in the correct page
						$pagination->setNbRecords($pagination->getNbRecords()+1);
						$pagination->execute();
						
						header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . (($pagination->getNbPages() > 1) ? '/page/' . $pagination->getNbPages() : '') . '#m' . $message->getMsgInTopic());
						exit();
					} elseif(isset($_POST['preview'])) { //The user wants a preview of the message
						$this->_smarty->assign(array(
							"forum_previewMsg" => $this->_bbcode->parseBBCode($_POST['editor_textarea']),
							"forum_textAreaDefault" => htmlspecialchars($_POST['editor_textarea'])
						));
					}
				}
			}
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
            $messages = $this->_messagesManager->getMessagesList($topic, $pagination->sqlLimit());

            //We parse the BBCode of each message
            for ($i = 0; $i < count($messages); $i++)
                $messages[$i]['message'] = $this->_bbcode->parseBBCode($messages[$i]['message']);

            $this->_smarty->assign(array(
                "forum_messages" => $messages,
                "pagination" => $pagination->parse(),
                "pagination_actualPage" => $pagination->getActualPage()
            ));

            //If the user who reads the topic is connected, we update the views
            if ($this->_isConnected)
                $this->_topicsManager->updateUserTopicViews($this->_member, $topic);

            //We check if the user is at the last page of the topic to display the text editor
            if ($pagination->getActualPage() == $pagination->getNbPages())
                $this->_smarty->assign(array("isLastPage" => true));
            else
                $this->_smarty->assign(array("isLastPage" => false));

            $this->_smarty->display("app/modules/forum/views/frontend/tpl/showTopic.tpl");
        }
	}

    /**
     * Allows the user (author, moderator or administrator) to edit his message
     * @param array $args Gets the name of the subcategory, the title of the topic and the id of the message from the URL
     */
    public function editMsg($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
			    throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");

			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");

			//We check if the message exists
			$message = new Message();
			$message->setId($args['idMsg']);
			$message = $this->_messagesManager->get($message);
			if(is_null($message))
			    throw new Exception("accessError_404");
			
			//We check if the user has the rights to edit the message
			if($this->_isConnected) {
				//If the user is not the author of the message nor a moderator
				if($this->_member->getId() != $message->getIdAuthor() AND !$this->_isModerator) { 
					header('Location: ' . $this->_path . '/forum');
					exit();
				}
			} else {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			$this->_smarty->assign(array(
				"forum_subCategoryName" => $subCategory->getName(),
				"forum_subCategoryURL" => Config::URLFormat($subCategory->getName()),
				"forum_topicTitle" => $topic->getTitle(),
				"forum_topicURL" => Config::URLFormat($topic->getTitle()),
				"forum_textAreaDefault" => $message->getMessage(),
				"forum_postURL" => $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '/action/edit-message/' . $message->getId()
			));
			
			//We check if the form has been submitted
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
                //The user has posted the message
                if(isset($_POST['post'])) {
                    $message->setMessage($_POST['editor_textarea']);
                    $message->setIdEditor($this->_member->getId());
                    $this->_messagesManager->update($message);

                    //We get the page where the message is so we can redirect the user to the correct page
                    $pagination = new Pagination();
                    $page = $message->getMsgInTopic() / $pagination->getNbMsgsPerPage() + 1;

                    if($page > 2)
                        header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '/page/' . floor($page) . '#m' . $message->getMsgInTopic() . '');
                    else
                        header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '#m' . $message->getMsgInTopic() . '');

                    exit();
                } elseif(isset($_POST['preview'])) { //The user wants a preview of the message
                    $this->_smarty->assign(array(
                        "forum_previewMsg" => $this->_bbcode->parseBBCode(htmlspecialchars($_POST['editor_textarea'])),
                        "forum_textAreaDefault" => htmlspecialchars($_POST['editor_textarea'])
                    ));
                }
            }
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
		
		$this->_smarty->display("app/modules/forum/views/frontend/tpl/editMsg.tpl");
	}

    /**
     * Allows the user (moderator or administrator) to remove a message
     * @param array $args Gets the name of the subcategory, the title of the topic and the ID of the message from the URL
     */
    public function removeMsg($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			//We check if the message exists
			$message = new Message();
			$message->setId($args['idMsg']);
			$message = $this->_messagesManager->get($message);
			if(is_null($message))
                throw new Exception("accessError_404");
			
			//We check if the user has the rights to delete the message
			if($this->_isConnected) {
				//If the user is not a moderator
				if(!$this->_isModerator) { 
					header('Location: ' . $this->_path . '/forum');
					exit();
				}
			} else {
				header('Location: ' . $this->_path . '/forum');
				exit();
			}

			//If there is only one message we delete the whole topic
            if($this->_messagesManager->getNbMessages($topic) == 1) {
                $this->_topicsManager->removeTopic($topic);

                setcookie('module_successMsg', 'topicHasBeenDeleted', time() + 365 * 24 * 3600, '/', null, false, true);
                header('Location: ' . $this->_path . '/forum/' . $args['category']);
                exit();
            } else {
                $this->_messagesManager->remove($message);

                setcookie('module_successMsg', 'msgHasBeenDeleted', time() + 365 * 24 * 3600, '/', null, false, true);

                //We get the page where the message is so we can redirect the user to the correct page
                $pagination = new Pagination();
                $page       = $message->getMsgInTopic() / $pagination->getNbMsgsPerPage() + 1;

                if ($page > 2)
                    header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '/page/' . floor($page));
                else
                    header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic']);

                exit();
            }
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user (author) to set a message as helpful
     * @param array $args Gets the name of the subcategory, the title of the topic and the ID of the message from the URL
     */
    public function msgHelpedAuthor($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			//We get infos about the message
			$message = new Message();
			$message->setId($args['idMsg']);
			$message = $this->_messagesManager->get($message);
			if(is_null($message))
                throw new Exception("accessError_404");
			
			//We check if the user is connected
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			if($this->_member->getId() != $topic->getIdAuthor()) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			//We get the page where the message is
			$pagination = new Pagination();
			$page = $message->getMsgInTopic() / $pagination->getNbMsgsPerPage() + 1;
			
			$this->_messagesManager->setMessageHelpful($message);
			
			header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . (($page > 2) ? '/page/' . floor($page) : '') . '#m' . $message->getMsgInTopic() . '');
			exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user to like/dislike a message
     * @param array $args Gets the name of the subcategory, the title of the topic, the ID of the message and the action to perform (like/dislike) from the URL
     */
    public function likeMsg($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");

			//We get infos about the message
			$message = new Message();
			$message->setId($args['idMsg']);
			$message = $this->_messagesManager->get($message);
			if(is_null($message))
                throw new Exception("accessError_404");
			
			//We get the page where the message is
			$pagination = new Pagination();
			$page = $message->getMsgInTopic() / $pagination->getNbMsgsPerPage() + 1;

			//We check if the user is connected
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			$this->_likesManager->like($this->_member, $message, $args['action']);
			
			header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . (($page > 2) ? '/page/' . floor($page) : '') . '#m' . $message->getMsgInTopic() . '');
			exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user to create a new topic
     * @param array $args Saves the name of the subcategory from the URL
     */
    public function newTopic($args) {
		try {
			//We check if the subcategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the user is connected
			if(!$this->_isConnected) {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			//We check if the form has been submitted
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$topicTitle = $_POST['forum_topicTitle'];
				$topicMessage = $_POST['forum_topicMessage'];

				//Topic title goes through internal verifications
                $topic = new Topic();
                $topic->setTitle($topicTitle);

                //Topic message goes through internal verifications
                $message = new Message();
                $message->setMessage($topicMessage);

                $topic->setIdAuthor($this->_member->getId());
                $topic->setIdSubCategory($subCategory->getId());

                //We check if a topic with the same title already exists
                if (is_null($this->_topicsManager->get($topic))) {
                    if (isset($_POST['post'])) { //The user has posted the message
                        $topic->setId($this->_topicsManager->add($topic)); //We create the topic and save its id

                        $message->setIdTopic($topic->getId());
                        $message->setIdAuthor($this->_member->getId());
                        $message->setMsgInTopic(1);
                        $this->_messagesManager->add($message);

                        setcookie('module_successMsg', 'topicCreated', time() + 365 * 24 * 3600, '/', null, false, true);

                        header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . Config::urlFormat($topic->getTitle()) . '');
                        exit();
                    } elseif (isset($_POST['preview'])) { //The user wants a preview of the message
                        $this->_smarty->assign(array(
                            "forum_previewMsg" => $this->_bbcode->parseBBCode($topicMessage),
                            "forum_topicTitleDefault" => $topicTitle,
                            "forum_topicMessageDefault" => $topicMessage
                        ));
                    }
                } else
                    throw new Exception("errorTopicAlreadyExists");
            }
		} catch(Exception $e) {
		    //If any error occured, we save the title and the message of the topic so the user doesn't have to type it again
            if(isset($topicTitle))
                $this->_smarty->assign(array("forum_topicTitleDefault" => $topicTitle));
            if(isset($topicMessage))
                $this->_smarty->assign(array("forum_topicMessageDefault" => $topicMessage));

			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		} finally {
            $this->_smarty->assign(array(
                "forum_subCategoryName" => $subCategory->getName(),
                "forum_subCategoryURL" => Config::URLFormat($subCategory->getName()),
                "forum_postURL" => $this->_path . '/forum/' . $args['category'] . '/action/new-topic'
            ));

            $this->_smarty->display("app/modules/forum/views/frontend/tpl/newTopic.tpl");
        }
	}

    /**
     * Allows the user (author, moderator, administrator) to set a topic as solved
     * @param array $args Saves the name of the subcategory and the title of the topic from the URL
     */
    public function solvedTopic($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			//We check if the user has the rights to put the topic as solved
			if($this->_isConnected) {
				//If the user is not the author of the message nor a moderator
				if($this->_member->getId() != $topic->getIdAuthor() AND !$this->_isModerator) { 
					header('Location: ' . $this->_path . '/forum');
					exit();
				}
			} else {
				header('Location: ' . $this->_path . '/members/login');
				exit();
			}
			
			if($args['action'] == 'solved')
				$this->_topicsManager->setTopicSolved($topic);
			elseif($args['action'] == 'not-solved')
				$this->_topicsManager->setTopicUnsolved($topic);
			else
                throw new Exception("accessError_404");
			
			header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '');
			exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user (moderator or administrator) to highlight a given topic
     * @param array $args Saves the name of the subcategory, the title of the topic and the action to perform (pin or detach) from the URL
     */
    public function postitTopic($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			//We check if the user has the rights to put the topic as a postit
			if(!$this->_isModerator)
                throw new Exception("accessError_restricted");
			
			if($args['action'] == 'pin')
				$this->_topicsManager->setTopicPostit($topic);
			elseif($args['action'] == 'detach')
				$this->_topicsManager->setTopicNormal($topic);
			else
                throw new Exception("accessError_404");
			
			header('Location: ' . $this->_path . '/forum/' . $args['category'] . '');
			exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user (moderator or administrator) to lock or unlock a given topic
     * @param array $args Saves the name of the subcategory, the title of the topic and the action to perform (lock or open) from the URL
     */
    public function lockTopic($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			if(!$this->_isModerator)
                throw new Exception("accessError_restricted");
			
			if($args['action'] == 'lock') {
				$this->_topicsManager->setTopicLocked($topic);
				setcookie('module_successMsg', 'topicLocked', time() + 365*24*3600, '/', null, false, true);
			} elseif($args['action'] == 'unlock') {
				$this->_topicsManager->setTopicUnlocked($topic);
				setcookie('module_successMsg', 'topicReopened', time() + 365*24*3600, '/', null, false, true);
			} else
                throw new Exception("accessError_404");
			
			header('Location: ' . $this->_path . '/forum/' . $args['category'] . '/' . $args['topic'] . '');
			exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Allows the user (moderator or administrator) to remove a topic
     * @param array $args Saves subcategory name and topic title from the URL
     */
    public function removeTopic($args) {
		try {
			//We check if the subCategory exists
			$subCategory = new SubCategory();
			$subCategory->setName($args['category']); //Name in URL Format
			$subCategory = $this->_subCategoriesManager->get($subCategory);
			if(is_null($subCategory))
                throw new Exception("accessError_404");
			
			//We check if the topic exists
			$topic = new Topic();
			$topic->setTitle($args['topic']); //Title in URL Format
			$topic = $this->_topicsManager->get($topic);
			if(is_null($topic))
                throw new Exception("accessError_404");
			
			//We check if the topic and the subcategory match
			if($topic->getIdSubCategory() != $subCategory->getId())
                throw new Exception("accessError_404");
			
			if(!$this->_isModerator)
                throw new Exception("accessError_restricted");

			$this->_topicsManager->removeTopic($topic);
			setcookie('module_successMsg', 'topicRemoved', time() + 365*24*3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/forum/' . $args['category']);
            exit();
		} catch(Exception $e) {
			$this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
		}
	}

    /**
     * Displays the list of topics which title or content matches with the search input
     */
    public function search() {
        try {
            if (empty($_POST['forum_search']))
                throw new Exception('searchInputCannotBeEmpty');

            if(strlen($_POST['forum_search'] < 3))
                throw new Exception('searchInputTooShort');

            $this->_smarty->assign(array("search_topics" => $this->_topicsManager->search($_POST['forum_search'])));
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $this->_smarty->display("app/modules/forum/views/frontend/tpl/search.tpl");
        }
    }
}