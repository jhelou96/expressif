<?php
namespace app\modules\members\models;

use config\Config;
use \PDO;

/**
 * Class MembersManager
 * DB manager for the member entity
 * @package app\modules\members\models
 */
class MembersManager {
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
     * MembersManager constructor.
     */
    public function __construct() {
        $this->_db = Config::getDBInfos();
        $this->_tablePrefix = Config::getDBTablesPrefix();
    }

    /**
     * Stores a member in the DB and return his id
     * @param Member $member
     * @return int ID of the member just stored
     */
    public function add(Member $member) {
		//Password Encryption
		$password = password_hash($member->getPassword(), PASSWORD_DEFAULT);
		
		//We create a new member
		$query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'members_members(username, password, email) VALUES(:username, :password, :email)');
		$query->bindValue(':username', $member->getUsername(), PDO::PARAM_STR);
		$query->bindValue(':password', $password, PDO::PARAM_STR);
		$query->bindValue(':email', $member->getEmail(), PDO::PARAM_STR);
		$query->execute();

        //We generate a token for the email verification process
        $member->setId($this->_db->lastInsertId());
        $token = $this->generateVerifToken($member);
		
		return $token;
	}

    /**
     * Returns the list of members from the DB
     * @param $sqlLimit SQL limit statement for the pagination system
     * @return array
     */
    public function getListMembers($sqlLimit) {
	    $member = new Member();
	    $members = array();
	    $i = 0;

	    $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'members_members ORDER BY username ' . $sqlLimit . '');
	    while($data = $query->fetch()) {
            $member->hydrate($data);

            $members[$i]['id'] = $member->getId();
            $members[$i]['username'] = $member->getUsername();
            $members[$i]['email'] = $member->getEmail();
            $members[$i]['level'] = $member->getLevel();

            $i++;
        }

        return $members;
    }

    /**
     * Updates the member attributes and saves them in the DB
     * @param Member $member
     */
    public function update(Member $member) {
		$query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'members_members SET email = :email, password = :password, avatar = :avatar, signature = :signature, level = :level WHERE id = :idMember');
		$query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
		$query->bindValue('email', $member->getEmail(), PDO::PARAM_STR);
		$query->bindValue('password', $member->getPassword(), PDO::PARAM_STR);
		$query->bindValue('avatar', $member->getAvatar(), PDO::PARAM_STR);
		$query->bindValue('signature', $member->getSignature(), PDO::PARAM_STR);
        $query->bindValue('level', $member->getLevel(), PDO::PARAM_INT);
		$query->execute();
	}

    /**
     * Checks if validation token provided is valid and validates the user account if it is
     * @param string $token
     */
    public function registrationValidation($token) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_validation WHERE token = :token');
		$query->bindValue('token', $token, PDO::PARAM_STR);
		$query->execute();

        $data = $query->fetch();

        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE id = :idMember');
        $query->bindValue('idMember', $data['idMember'], PDO::PARAM_INT);
        $query->execute();

        $data = $query->fetch();

        $member = new Member();
        $member->hydrate($data);

        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'members_members SET level = 1 WHERE id = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();

        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'members_validation WHERE idMember = :idMember AND token = :token');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->bindValue('token', $token, PDO::PARAM_STR);
        $query->execute();
	}

    /**
     * @param Member $member
     * @return bool True if a member with the same username exists, false otherwise
     */
    public function checkIfUsernameExists(Member $member) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE username = :username');
		$query->bindValue('username', $member->getUsername(), PDO::PARAM_STR);
		$query->execute();
		
		if($query->rowCount() > 0) 
			return true;
		else
			return false;
	}

    /**
     * @param Member $member
     * @return bool True if a member with the same email address exists, false otherwise
     */
    public function checkIfEmailExists(Member $member) {
		$query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE email = :email');
		$query->bindValue('email', $member->getEmail(), PDO::PARAM_STR);
		$query->execute();
		
		if($query->rowCount() > 0) 
			return true;
		else
			return false;
	}

    /**
     * Checks if username and password match for the login process
     * @param Member $member
     * @return bool True if username and password correspond, false otherwise
     */
    public function checkUsernamePasswordCombination(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE username = :username');
        $query->bindValue('username', $member->getUsername(), PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);

        if(password_verify($member->getPassword(), $data['password']))
            return true;
        else
            return false;
    }

    /**
     * Checks if the user is not banned and if he has validated his email address
     * @param Member $member
     * @return bool True if the user is allowed to login, false otherwise
     */
    public function checkIfLoginAllowed(Member $member) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE username = :username');
        $query->bindValue('username', $member->getUsername(), PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC);
		
        if($data['level'] > 0)
            return true;
        else
            return false;
    }

    /**
     * Updates the last login date of a member
     * @param Member $member
     */
    public function updateLastLoginDate(Member $member) {
        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'members_members SET lastConnectionDate = NOW() WHERE username = :username');
        $query->bindValue('username', $member->getUsername(), PDO::PARAM_STR);
        $query->execute();
    }

    /**
     * Selects and returns a specific member from the DB
     * @param Member $member
     * @return Member|null Null if no member found
     */
    public function get(Member $member)
    {
        if (!empty($member->getId())) { //Search member by ID
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE id = :idMember');
            $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
            $query->execute();
        } elseif (!empty($member->getUsername())) { //Search member by username
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE username = :usernameMember');
            $query->bindValue('usernameMember', $member->getUsername(), PDO::PARAM_STR);
            $query->execute();
        } elseif(!empty($member->getEmail())) { //Search member by email
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE email = :email');
            $query->bindValue('email', $member->getEmail(), PDO::PARAM_STR);
            $query->execute();
        } else
			return null;
		
		if($query->rowCount() > 0) {
			$data = $query->fetch();
			
			$memberReturned = new Member();
            $memberReturned->hydrate($data);
			
			return $memberReturned;
		} else
			return null;
	}

    /**
     * Generates a validation token for the email verification processes
     * @param Member $member
     * @return string String of characters representing the generated token
     */
    public function generateVerifToken(Member $member) {
        //We generate a token for the mail validation process
        $token = Config::random(20);

        //We check if the token generated already exists
        $verif = true;
        while($verif) {
            $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_validation WHERE token = :token');
            $query->bindValue('token', $token, PDO::PARAM_STR);
            $query->execute();

            if($query->rowCount() == 0)
                $verif = false;
        }

        //We save the token in the db
        $query = $this->_db->prepare('INSERT INTO ' . $this->_tablePrefix . 'members_validation(idMember, token) VALUES(:idMember, :token)');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->bindValue('token', $token, PDO::PARAM_STR);
        $query->execute();

        return $token;
    }

    /**
     * Resets member password
     * @param Member $member
     * @param string $token
     */
    public function resetPassword(Member $member, $token) {
       //We check if the token is valid
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_validation WHERE token = :token');
        $query->bindValue('token', $token, PDO::PARAM_STR);
        $query->execute();

        $data = $query->fetch();
        $member->setId($data['idMember']);

        //Password Encryption
        $password = password_hash($member->getPassword(), PASSWORD_DEFAULT);

        //We update the password
        $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'members_members SET password = :password WHERE id = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->bindValue('password', $password, PDO::PARAM_STR);
        $query->execute();

        //We validate the member's account if it isn't yet since we verified his email address
        if($this->get($member)->getLevel() == 0) {
            $query = $this->_db->prepare('UPDATE ' . $this->_tablePrefix . 'members_members SET level = 0 WHERE id = :idMember');
            $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
            $query->execute();
        }

        //We delete the token and all the eventual tokens of the member since we don't need them anymore
        $query = $this->_db->prepare('DELETE FROM ' . $this->_tablePrefix . 'members_validation WHERE idMember = :idMember');
        $query->bindValue('idMember', $member->getId(), PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * Checks if validation token is valid or not
     * @param $token
     * @return bool
     */
    public function checkIfTokenValid($token) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_validation WHERE token = :token');
        $query->bindValue('token', $token, PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount() > 0)
            return true;
        else
            return false;
    }

    /**
     * @return int
     */
    public function getNbMembers() {
        $query = $this->_db->query('SELECT * FROM ' . $this->_tablePrefix . 'members_members');

        return $query->rowCount();
    }

    /**
     * Returns a list of members which username or email matches with the search input
     * @param string $pattern
     * @return array Array of members
     */
    public function search($pattern) {
        $query = $this->_db->prepare('SELECT * FROM ' . $this->_tablePrefix . 'members_members WHERE username LIKE :pattern OR email LIKE :pattern');
        $query->bindValue('pattern', '%' . $pattern . '%', PDO::PARAM_STR);
        $query->execute();

        $member = new Member();
        $members = array();
        $i = 0;

        while($data = $query->fetch()) {
            $member->hydrate($data);

            $members[$i]['id'] = $member->getId();
            $members[$i]['username'] = $member->getUsername();
            $members[$i]['email'] = $member->getEmail();
            $members[$i]['level'] = $member->getLevel();

            $i++;
        }

        return $members;
    }
}