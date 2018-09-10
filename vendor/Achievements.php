<?php
namespace vendor;

use app\modules\forum\models\MessagesManager;
use \PDO;
use config\Config;

use app\modules\members\models\Member;

class Achievements {

	private $_db;
	private $_member;
	private $_tablePrefix;
	private $_achievementsList;
	private $_i;

	public function __construct(Member $member) {
		$this->_db = Config::getDBInfos();
		$this->_tablePrefix = Config::getDBTablesPrefix();
		$this->_member = $member;

		//We call all the methods of this class to check all the achievements
        $this->_achievementsList = array();
        $this->_i = 0;
        $methods = get_class_methods($this);
        foreach($methods as $method) {
            if($method != '__construct')
                $this->{$method}();
        }
	}

	public function getAchievementsList() {
	    return $this->_achievementsList;
    }

	public function forum_userHasHelpedOthers() {
		$forum_messagesManager = new MessagesManager();

		if($forum_messagesManager->achievements_userHasHelpedOthers($this->_member)) {
            $this->_achievementsList[$this->_i]['description'] = 'Helping a fellow member !';
            $this->_achievementsList[$this->_i]['thumbnail'] = 'http://www.flaticon.com/premium-icon/icons/svg/150/150297.svg';
            $this->_i++;
        }
	}
	
	public function forum_userHasPostedAMsg() {
		$forum_messagesManager = new MessagesManager();

		if($forum_messagesManager->achievements_userHasPostedAMessage($this->_member)) {
            $this->_achievementsList[$this->_i]['description'] = 'New blood';
            $this->_achievementsList[$this->_i]['thumbnail'] = 'http://www.flaticon.com/premium-icon/icons/svg/145/145750.svg';
            $this->_i++;
        }
	}
}