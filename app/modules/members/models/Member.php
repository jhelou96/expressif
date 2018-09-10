<?php
namespace app\modules\members\models;

use config\Exception;

/**
 * Class Member
 * Member entity as it is saved in the DB
 * @package app\modules\members\models
 */
class Member {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_username;

    /**
     * @var string
     */
    private $_password;

    /**
     * @var string
     */
    private $_email;

    /**
     * @var string
     */
    private $_avatar;

    /**
     * @var String
     * Short sentence written by the user and displayed publicly
     */
    private $_signature;

    /**
     * @var int
     * (-1, banned), (0, not validated), (1, member), (2, moderator), (3, administrator)
     */
    private $_level;

    /**
     * @var string
     */
    private $_registrationDate;

    /**
     * @var string
     */
    private $_lastConnectionDate;

    /**
     * @var string
     * Date of the last query/action performed by the user on the application
     */
    private $_lastQuery;

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
				throw new Exception("member_idShouldBeNumeric");
		} else
			throw new Exception("member_idCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getUsername() {
		return $this->_username;
	}

    /**
     * @param string $username
     * @throws Exception
     */
    public function setUsername($username) {
		if(!empty($username)) {
			if(is_string($username)) {
				if(strlen($username) >= 3) { //Username larger than 3 caracters
					if(strlen($username <= 30)) {
						if (ctype_alnum($username)) //Username doesn't contain special caracters
							$this->_username = htmlspecialchars($username);
						else
							throw new Exception("member_usernameShouldBeAlphanumeric");
					} else
						throw new Exception("member_usernameCannotExceed30Chars");
				} else
					throw new Exception("member_usernameShouldBeAtLeast3Chars");
			} else
				throw new Exception("member_usernameShouldBeString");
		} else
			throw new Exception("member_usernameCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getPassword() {
		return $this->_password;
	}

    /**
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password) {
		if(!empty($password)) {
			if(is_string($password)) {
				if(strlen($password) >= 6) //Password larger than 6 caracters
					$this->_password = htmlspecialchars($password);
				else
					throw new Exception("member_passwordShouldBeAtLeast6Chars");
			} else
				throw new Exception("member_passwordShouldBeString");
		} else 
			throw new Exception("member_passwordCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getEmail() {
		return $this->_email;
	}

    /**
     * @param string $email
     * @throws Exception
     */
    public function setEmail($email) {
		if(!empty($email)) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) //Valid email address format
				$this->_email = htmlspecialchars($email);
			else
				throw new Exception("member_invalidEmailFormat");
		} else
			throw new Exception("member_emailCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getAvatar() {
		return $this->_avatar;
	}

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar) {
		if(is_string($avatar))
			$this->_avatar = htmlspecialchars($avatar);
	}

    /**
     * @return String
     */
    public function getSignature() {
		return $this->_signature;
	}

    /**
     * @param string $signature
     */
    public function setSignature($signature) {
		if(is_string($signature))
			$this->_signature = htmlspecialchars($signature);
	}

    /**
     * @return int
     */
    public function getLevel() {
		return $this->_level;
	}

    /**
     * @param int $level
     */
    public function setLevel($level) {
		if(is_numeric($level)) 
			$this->_level = $level;
	}

    /**
     * @return string
     */
    public function getRegistrationDate() {
		return $this->_registrationDate;
	}

    /**
     * @param string $registrationDate
     * @throws Exception
     */
    public function setRegistrationDate($registrationDate) {
		if(!empty($registrationDate)) 
			$this->_registrationDate = htmlspecialchars($registrationDate);
		else
			throw new Exception("member_registrationDateCannotBeEmpty");
	}

    /**
     * @return string
     */
    public function getLastConnectionDate() {
		return $this->_lastConnectionDate;
	}

    /**
     * @param string $lastConnectionDate
     */
    public function setLastConnectionDate($lastConnectionDate) {
			$this->_lastConnectionDate = htmlspecialchars($lastConnectionDate);
	}

    /**
     * @return string
     */
    public function getLastQuery() {
		return $this->_lastQuery;
	}

    /**
     * @param string $lastQuery
     * @throws Exception
     */
    public function setLastQuery($lastQuery) {
		if(is_numeric($lastQuery))
			$this->_lastQuery = $lastQuery;
		else
			throw new Exception("member_lastQueryShouldBeNumeric");
	}
}