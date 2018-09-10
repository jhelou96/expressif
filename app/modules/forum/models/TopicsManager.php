<?php
namespace app\modules\forum\models;

use config\Config;
use \PDO;

use app\modules\members\models\Member;
use app\modules\members\models\MembersManager;

/**
 * Class TopicsManager
 * DB manager for the forum topic entity
 * @package app\modules\forum\models
 */
class TopicsManager {
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
     * TopicsManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores a topic in the DB and returns its ID
     * @param Topic $topic
     * @return int Integer representing the ID of the member just stored
     */
    public function add(Topic $topic) {
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_topics(idSubCategory, idAuthor, title) VALUES(:idSubCategory, :idAuthor, :title)');
		$query->bindValue('idSubCategory', $topic->getIdSubCategory(), PDO::PARAM_INT);
		$query->bindValue('idAuthor', $topic->getIdAuthor(), PDO::PARAM_INT);
		$query->bindValue('title', $topic->getTitle(), PDO::PARAM_STR);
		$query->execute();
		
		return $this->_db->lastInsertId();
	}

    /**
     * Selects and returns all the topics of a specific subcategory
     * @param SubCategory $subCategory
     * @param $sqlLimit SQL limit statement for the pagination system
     * @return array|null Array of topics or Null if no topic found
     */
    public function getTopicsList(SubCategory $subCategory, $sqlLimit) {
		$membersManager = new MembersManager();
		$messagesManager = new MessagesManager();
		
		//We get all the topics labeled as postit without the pagination system taken into account
		$query = $this->_db->prepare('SELECT T.*, min(M.publicationDate) AS creationDate FROM ' . $this->_tablePrefix . 'forum_topics  T LEFT JOIN ' . $this->_tablePrefix . 'forum_messages M ON T.id = M.idTopic WHERE T.idSubCategory = :idSubCategory AND T.postit = \'1\' GROUP BY T.id ORDER BY MAX(M.publicationDate) DESC');
		$query->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
		$query->execute();
		
		//We check if there is any existing topic for the given subcategory
		if($query->rowCount() > 0) {
			$topics_postit = array();
			$i = 0;
			
			while($data = $query->fetch()) {
                $topic = new Topic();
				$topic->hydrate($data);

				//We get some infos about the author
                $topicAuthor = new Member();
                $topicAuthor->setId($topic->getIdAuthor());
				$topicAuthor = $membersManager->get($topicAuthor);

				//We get the nb of messages and the last posted message
				$lastMsgInfos = $messagesManager->getLastMsgPosted($topic);
				$nbAnswers = $messagesManager->getNbMessages($topic) - 1; //-1 because the first message of the author is not considered as an answer

                //If there is any answer, we get some infos about the author who wrote the last message
				if($nbAnswers > 0) {
                    $lastMsgAuthor = new Member();
                    $lastMsgAuthor->setId($lastMsgInfos->getIdAuthor());
                    $lastMsgAuthor = $membersManager->get($lastMsgAuthor);
					
					$lastMsg = '<a href="#">' . Config::dateTimeFormat($lastMsgInfos->getPublicationDate()) . '</a><br />par <a href="#">' . $lastMsgAuthor->getUsername() . '</a>';
				} else
					$lastMsg = '<i>Aucun message</i>';
				
				$topics_postit[$i]['id'] = $topic->getId();
				$topics_postit[$i]['title'] = $topic->getTitle();
				$topics_postit[$i]['author'] = $topicAuthor->getUsername();
				$topics_postit[$i]['solved'] = $topic->getSolved();
				$topics_postit[$i]['url'] = Config::urlFormat($topic->getTitle());
				$topics_postit[$i]['nbAnswers'] = $nbAnswers;
				$topics_postit[$i]['lastMsg'] = $lastMsg;
				$topics_postit[$i]['creationDate'] = Config::dateTimeFormat($topic->getCreationDate());
				
				$i++;
			}
		} else
			$topics_postit = null;
		
		//We get all the topics labeled as normal with the pagination system taken into account
		$query = $this->_db->prepare('SELECT T.*, min(M.publicationDate) AS creationDate FROM ' . $this->_tablePrefix . 'forum_topics T LEFT JOIN ' . $this->_tablePrefix . 'forum_messages M ON T.id = M.idTopic WHERE T.idSubCategory = :idSubCategory AND T.postit = \'0\' GROUP BY T.id ORDER BY max(M.publicationDate) DESC ' . $sqlLimit . '');
		$query->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
		$query->execute();
		
		//We check if there is any existing topic for the given subcategory
		if($query->rowCount() > 0) {
			$topics_normal = array();
			$i = 0;
			
			while($data = $query->fetch()) {
			    $topic = new Topic();
                $topic->hydrate($data);

                //We get some infos about the author
                $topicAuthor = new Member();
                $topicAuthor->setId($topic->getIdAuthor());
                $topicAuthor = $membersManager->get($topicAuthor);

                //We get the nb of messages and the last posted message
                $lastMsgInfos = $messagesManager->getLastMsgPosted($topic);
                $nbAnswers = $messagesManager->getNbMessages($topic) - 1; //-1 because the first message of the author is not considered as an answer

                //If there is any answer, we get some infos about the author who wrote the last message
                if($nbAnswers > 0) {
                    $lastMsgAuthor = new Member();
                    $lastMsgAuthor->setId($lastMsgInfos->getIdAuthor());
                    $lastMsgAuthor = $membersManager->get($lastMsgAuthor);

                    $lastMsg = '<a href="#">' . Config::dateTimeFormat($lastMsgInfos->getPublicationDate()) . '</a><br />par <a href="#">' . $lastMsgAuthor->getUsername() . '</a>';
                } else
                    $lastMsg = '<i>Aucun message</i>';
				
				$topics_normal[$i]['id'] = $topic->getId();
				$topics_normal[$i]['title'] = $topic->getTitle();
				$topics_normal[$i]['author'] = $topicAuthor->getUsername();
				$topics_normal[$i]['solved'] = $topic->getSolved();
				$topics_normal[$i]['locked'] = $topic->getLocked();
				$topics_normal[$i]['url'] = Config::urlFormat($topic->getTitle());
				$topics_normal[$i]['nbAnswers'] = $nbAnswers; //-1 since the message of the author is not an answer
				$topics_normal[$i]['lastMsg'] = $lastMsg;
				$topics_normal[$i]['creationDate'] = Config::dateTimeFormat($topic->getCreationDate());
				
				//We calculate the colspan for the table in HTML
				$topics_normal[$i]['colspan'] = 2;
				if($topic->getLocked())
					$topics_normal[$i]['colspan']--;
				
				$i++;
			}
		} else
			$topics_normal = null;
		
		$topics = array (
			'postit' => $topics_postit,
			'normal' => $topics_normal
		);
		
		if(!empty($topics))
			return $topics;
		else 
			return null;
	}

    /**
     * Selects and returns a specific topic from the DB
     * @param Topic $topic
     * @return Topic|null Null if no topic found
     */
    public function get(Topic $topic) {
		$topicReturned = new Topic();
		
		//Search topic by title
		if(!empty($topic->getTitle())) {
			$query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'forum_topics');
			
			while($data = $query->fetch()) {
                $topicReturned->hydrate($data);
				if(Config::urlFormat($topicReturned->getTitle()) == Config::urlFormat($topic->getTitle()))
					return $topicReturned;
			}
			
			return null;
		} elseif(!empty($topic->getId())) { //Search topic by ID
			$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topics WHERE id = :idTopic');
			$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
			$query->execute();
			
			if($query->rowCount() > 0) {
				$data = $query->fetch();
                $topicReturned->hydrate($data);
			
				return $topicReturned;
			} else
				return null;
		}
		
		return null;
	}

    /**
     * Returns the number of topics in a specific subcategory
     * @param SubCategory $subCategory
     * @return int
     */
    public function getNbTopics(SubCategory $subCategory) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topics WHERE idSubCategory = :idSubCategory');
		$query->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
		$query->execute();
		
		return $query->rowCount();
	}

    /**
     * Sets a topic as solved
     * @param Topic $topic
     */
    public function setTopicSolved(Topic $topic) {
		if($topic->getSolved() == false) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET solved = \'1\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Removes the topics from solved topics
     * @param Topic $topic
     */
    public function setTopicUnsolved(Topic $topic) {
		if($topic->getSolved() == true) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET solved = \'0\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Highlights a specific topic
     * @param Topic $topic
     */
    public function setTopicPostit(Topic $topic) {
		if($topic->getPostit() == false) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET postit = \'1\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Removes topic from highlighted topics
     * @param Topic $topic
     */
    public function setTopicNormal(Topic $topic) {
		if($topic->getPostit() == true) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET postit = \'0\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Locks a specific topic
     * @param Topic $topic
     */
    public function setTopicLocked(Topic $topic) {
		if($topic->getLocked() == false) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET locked = \'1\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Reopen a specific topic
     * @param Topic $topic
     */
    public function setTopicUnlocked(Topic $topic) {
		if($topic->getLocked() == true) {
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topics SET locked = \'0\' WHERE id = :idTopic');
			$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
			
			$query->execute();
		}
	}

    /**
     * Removes a specific topic
     * @param Topic $topic
     */
    public function removeTopic(Topic $topic) {
		//We delete the topic
		$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_topics WHERE id = :idTopic');
		$query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();

		//We delete the views related to the topic
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_topicviews WHERE idTopic = :idTopic');
        $query->bindValue(':idTopic', $topic->getId(), PDO::PARAM_INT);
        $query->execute();

		//We delete the messages related to the topic
        $messagesManager = new MessagesManager();
        $messagesManager->removeMessagesRelatedToTopic($topic);
	}

    /**
     * Updates the topic views of a specific topic after the member visits it
     * Used to display the topic differently based on member's participation and new messages
     * @param Member $member
     * @param Topic $topic
     */
    public function updateUserTopicViews(Member $member, Topic $topic) {
		//We get the number of the last message in the topic
        $messagesManager = new MessagesManager();
		$lastMsgInTopic = $messagesManager->getMaxMsgInTopic($topic);
		
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topicviews WHERE idMember = :idMember AND idTopic = :idTopic');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();
		
		//If it's the first time the user visits the topic
		if($query->rowCount() == 0) {
		    //We check if the user is the author of the topic --> In case the topic has been newly created andit is the first time that the author visits the topic
            if($member->getId() == $topic->getIdAuthor())
                $participated = 1;
            else
                $participated = 0;

			$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_topicviews(idMember, idTopic, lastMsgSeen, participated) VALUES(:idMember, :idTopic, :lastMsgSeen, :participated)');
			$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
			$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
			$query->bindValue('lastMsgSeen', $lastMsgInTopic, PDO::PARAM_INT);
            $query->bindValue('participated', $participated, PDO::PARAM_INT);
			$query->execute();
		} else {
			//We check if the user posted any message in the topic
            $hasParticipated = $messagesManager->checkUserParticipation($member, $topic);
			
			$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_topicviews SET lastMsgSeen = :lastMsgSeen, participated = :participated WHERE idMember = :idMember AND idTopic = :idTopic');
			$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
			$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
			$query->bindValue('participated', $hasParticipated, PDO::PARAM_INT);
			$query->bindValue('lastMsgSeen', $lastMsgInTopic, PDO::PARAM_INT);
			$query->execute();
		}
	}

    /**
     * Displays the topic differently based on whether the member participated or not and whether it contains new messages not read by the member or not
     * @param Member $member
     * @param Topic $topic
     * @return int 0 if no new messages(orange bar), 1 if a new message has been posted and the user participates to the discussion(green bar), 2 if a new message has been posted and the user does not participate to the discussion(red bar)
     */
    public function checkTopicViews(Member $member, Topic $topic) {
		//We first get the topic views history of the member
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topicviews WHERE idMember = :idMember AND idTopic = :idTopic');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetch();
		
		//We get the last message in the topic
        $messagesManager = new MessagesManager();
		$lastMsgInTopic = $messagesManager->getMaxMsgInTopic($topic);
		
		/*
			$view = 0 --> No new messages - Orange color
			$view = 1 --> New messages and the user participated to the topic - Green color
			$view = 2 --> New messages and the user did not participate - Red color
		*/
		$view = 0;
		
		if($query->rowCount() != 0) {
			//View changes whether the user participated to the topic or not
			if($data['participated'] == 1) {
				//If the number of the last message seen is less than the number of the last message posted, than the user didn't see the last messages
				if($data['lastMsgSeen'] < $lastMsgInTopic)
					$view = 1;
			} else {
				//If the number of the last message seen is less than the number of the last message posted, than the user didn't see the last messages
				if($data['lastMsgSeen'] < $lastMsgInTopic)
					$view = 2;
			}
		} else 
			$view = 2;

		return $view;
	}

    /**
     * Returns the list of topics where the member participated
     * @param Member $member
     * @return array|null Array of topics or null if no topics found
     */
    public function getMemberParticipation(Member $member) {
		$query = $this->_db->prepare('SELECT T.*, MIN(M.publicationDate) AS topicCreationDate, MAX(M.publicationDate) lastMsgPostedByMemberDate FROM ' . $this->_tablePrefix . 'forum_topics T JOIN ' . $this->_tablePrefix . 'forum_messages M ON T.id = M.idTopic WHERE M.idAuthor = :idMember GROUP BY M.idTopic ORDER BY lastMsgPostedByMemberDate DESC LIMIT 0, 15');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->execute();
		
		if($query->rowCount() > 0) {
			$subCategory = new SubCategory();
			$subCategoriesManager = new SubCategoriesManager();
			
			$author = new Member();
			$membersManager = new MembersManager();
			
			$topics = array();
			$i = 0;
			
			while($data = $query->fetch()) {
				$topics[$i]['title'] = $data['title'];
				$topics[$i]['creationDate'] = Config::dateFormat($data['topicCreationDate']);
				$topics[$i]['lastMsgPostedByMemberDate'] = Config::dateFormat($data['lastMsgPostedByMemberDate']);

				$subCategory->setId($data['idSubCategory']);
				$topics[$i]['subCategory'] = $subCategoriesManager->get($subCategory)->getName();
				
				$author->setId($data['idAuthor']);
				$topics[$i]['author'] = $membersManager->get($author)->getUsername();
				
				$topics[$i]['topicURL'] = Config::getPath() . '/forum/' . Config::urlFormat($topics[$i]['subCategory']) . '/' . Config::urlFormat($data['title']);
				$i++;
			}

			return $topics;
		} else 
			return null;
	}

    /**
     * Returns the number of topics created by a specific member
     * @param Member $member
     * @return int
     */
    public function getNbTopicsCreated(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topics WHERE idAuthor = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount();
    }

    /**
     * Returns the list of topics which title or content matches with the search input
     * @param $content
     * @return array Array of topics
     */
    public function search($content) {
		$query = $this->_db->prepare('SELECT DISTINCT T.* FROM ' . $this->_tablePrefix . 'forum_topics T JOIN ' . $this->_tablePrefix . 'forum_messages M ON T.id = M.idTopic WHERE (T.title LIKE :content OR M.message LIKE :content) LIMIT 0, 15');
		$query->bindValue('content', '%' . $content . '%', PDO::PARAM_STR);
		$query->execute();
		
		$subCategory = new SubCategory();
		$subCategoriesManager = new SubCategoriesManager();

		$topics = array();
		$i = 0;

		while($data = $query->fetch()) {
			$topics[$i]['title'] = $data['title'];
			
			$subCategory->setId($data['idSubCategory']);
			$topics[$i]['subCategory'] = $subCategoriesManager->get($subCategory)->getName();
			$topics[$i]['url'] = Config::getPath() . '/forum/' . Config::urlFormat($subCategoriesManager->get($subCategory)->getName()) . '/' . Config::urlFormat($data['title']);
			
			$i++;
		}
		
		return $topics;
	}

    /**
     * Removes all the topics of a specific subcategory from the DB
     * @param SubCategory $subCategory
     */
    public function removeTopicsRelatedToSubCategory(SubCategory $subCategory) {
        //We have to remove the messages related to every topic that is to be removed
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_topics WHERE idSubCategory = :idSubCategory');
        $query->bindValue('idSubCategory', $subCategory->getId(), PDO::PARAM_INT);
        $query->execute();

        $topic = new Topic();

        while($data = $query->fetch()) {
            $topic->hydrate($data);

            $messagesManager = new MessagesManager();
            $messagesManager->removeMessagesRelatedToTopic($topic);

            //We remove the views related to the topic
            $query2 = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_topicviews WHERE idTopic = :idTopic');
            $query2->bindValue('idTopic', $topic->getId(), PDO::PARAM_INT);
            $query2->execute();

            $query3 = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_topics WHERE id = :id');
            $query3->bindValue('id', $topic->getId(), PDO::PARAM_INT);
            $query3->execute();
        }
    }
}