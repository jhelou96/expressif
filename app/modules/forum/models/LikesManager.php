<?php
namespace app\modules\forum\models;

use \PDO;
use config\Config;

use app\modules\members\models\Member;

/**
 * Class LikesManager
 * DB manager for the forum like entity
 * @package app\modules\forum\models
 */
class LikesManager {
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
     * LikesManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Returns the number of likes of a given message
     * @param Message $message
     * @return int
     */
    public function getLikes(Message $message) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_likes WHERE liked = 1 AND idMessage = :idMessage');
		$query->bindValue('idMessage', $message->getId(), PDO::PARAM_INT);
		$query->execute();
		
		return $query->rowCount();
	}

    /**
     * Returns the number of dislikes of a given message
     * @param Message $message
     * @return int
     */
    public function getDislikes(Message $message) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_likes WHERE disliked = 1 AND idMessage = :idMessage');
		$query->bindValue('idMessage', $message->getId(), PDO::PARAM_INT);
		$query->execute();
		
		return $query->rowCount();
	}

    /**
     * Allows the user to like or dislike a message
     * @param Member $member
     * @param Message $message
     * @param string $action Defines action to perform (like or dislike)
     */
    public function like(Member $member, Message $message, $action) {
		//We check if the user has already voted for this message
		$check = $this->checkIfAlreadyVoted($member, $message);
		
		//If he hasn't
		if(empty($check)) {
			//We check what action the user wants to perform
			if($action == 'like') {
				$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_likes(idUser, idMessage, liked) VALUES(:idUser, :idMessage, \'1\')');
				$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
				$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
				$query->execute();
			} elseif($action == 'dislike') {
				$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'forum_likes(idUser, idMessage, disliked) VALUES(:idUser, :idMessage, 1)');
				$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
				$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
				$query->execute();
			}
		} else {
			//If the user already liked the message
			if($check == 'liked') {
				if($action == 'like') { //And he re-likes it again, it means that he wants to cancel his vote
					$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_likes WHERE idUser = :idUser AND idMessage = :idMessage');
					$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
					$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
					$query->execute();
				} elseif($action == 'dislike') {  //And he dislikes it, it means he wants to change his vote
					$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_likes SET liked = 0, disliked = 1 WHERE idUser = :idUser AND idMessage = :idMessage');
					$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
					$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
					$query->execute();
				}
			} elseif($check == 'disliked') { //Same process if he disliked the message
				if($action == 'like') {
					$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'forum_likes SET liked = 1, disliked = 0 WHERE idUser = :idUser AND idMessage = :idMessage');
					$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
					$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
					$query->execute();
				} elseif($action == 'dislike') {
					$query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_likes WHERE idUser = :idUser AND idMessage = :idMessage');
					$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
					$query->bindValue('idMessage', $message->getId(), PDO::PARAM_STR);
					$query->execute();
				}
			}
		}
	}

    /**
     * Checks if the user has already liked or disliked the message
     * @param Member $member
     * @param Message $message
     * @return null|string Returns the type of operation performed (like or dislike) or null if the user didn't vote yet
     */
    public function checkIfAlreadyVoted(Member $member, Message $message) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'forum_likes WHERE idUser = :idUser AND idMessage = :idMessage');
		$query->bindValue('idMessage', $message->getId(), PDO::PARAM_INT);
		$query->bindValue('idUser', $member->getId(), PDO::PARAM_INT);
		$query->execute();
		
		if($query->rowCount() > 0) {
			$data = $query->fetch();
			
			$like = new Like();
			$like->hydrate($data);
			
			if($like->getLiked() == 1)
				return ('liked');
			elseif($like->getDisliked() == 1)
				return ('disliked');
			else
				return null;
		} else
			return null;
	}

    /**
     * Removes a like/dislike from the DB
     * @param Message $message
     */
    public function remove(Message $message) {
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'forum_likes WHERE idMessage = :idMessage');
        $query->bindValue(':idMessage', $message->getId(), PDO::PARAM_INT);
        $query->execute();
    }
}