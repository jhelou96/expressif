<?php
namespace app\modules\members;

use config\Exception;
use vendor\Pagination;

use app\modules\members\models\MembersManager;
use app\modules\members\models\Member;

use app\modules\Module;

/**
 * Class MembersBackController
 * Member module controller for the backend/admin interface
 * @package app\modules\members
 */
class MembersBackController extends Module
{
    /**
     * @var MembersManager
     * DB manager object for the member entity
     */
    private $_membersManager;

    /**
     * MembersBackController constructor.
     */
    public function __construct()
    {
        $this->run();

        //We check if the user is a moderator
        if (!$this->_isModerator)
            throw new Exception("accessError_restricted");

        //We load the managers related to this module
        $this->_membersManager    = new MembersManager();
    }

    /**
     * Allows the user (moderator or administrator) to manage members
     */
    public function members() {
        try {
            //If search form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (empty($_POST['members_search']))
                    throw new Exception("searchFieldCannotBeEmpty");

                $this->_smarty->assign(array("search_results" => $this->_membersManager->search($_POST['members_search'])));
            }
        } catch(Exception $e) {
            $this->_smarty->assign(array("module_errorMsg" => $e->getMessage()));
        } finally {
            $pagination = new Pagination();
            $pagination->setnbRecords($this->_membersManager->getNbMembers());

            if (isset($args['page']))
                $pagination->setActualPage($args['page']);

            $pagination->execute();

            $members = $this->_membersManager->getListMembers($pagination->sqlLimit());
            $this->_smarty->assign(array(
                "members" => $members,
                "pagination" => $pagination->parse()
            ));

            $this->_smarty->display("app/modules/members/views/backend/tpl/members.tpl");
        }
    }

    /**
     * Changes the group (members, moderators and administrators) of a member
     * @param array $args Saves member ID from the URL
     */
    public function changeMemberGroup($args) {
        try {
            //User needs to be an admin to be able to modify the member's group
            if(!$this->_isAdministrator)
                throw new Exception("accessError_restricted");

            $member = new Member();
            $member->setId($args['id']);
            $member = $this->_membersManager->get($member);
            if (is_null($member)) //We check if member exists
                throw new Exception("memberDoesNotExist");

            $member->setLevel($_POST['member_level']);

            $this->_membersManager->update($member);

            setcookie('module_successMsg', 'memberGroupHasBeenChanged', time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/members');
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/members');
            exit();
        }
    }

    /**
     * Allows the user (moderator or administrator) to ban/unban a member
     * @param array $args Saves member ID from the URL
     */
    public function changeMemberStatus($args) {
        try {
            $member = new Member();
            $member->setId($args['id']);
            $member = $this->_membersManager->get($member);
            if (is_null($member)) //We check if member exists
                throw new Exception("memberDoesNotExist");

            //For the user to be able to ban a member, he has to have a higher level than the concerned member
            if ($member->getLevel() >= $this->_member->getLevel())
                throw new Exception("memberWithHigherLevelCannotBeBanned");

            if ($args['action'] == 'ban') {
                $member->setLevel(-1);
                $this->_membersManager->update($member);

                setcookie('module_successMsg', 'memberHasBeenBanned', time() + 365 * 24 * 3600, '/', null, false, true);
            } elseif ($args['action'] == 'unban') {
                $member->setLevel(1);
                $this->_membersManager->update($member);

                setcookie('module_successMsg', 'memberHasBeenUnbanned', time() + 365 * 24 * 3600, '/', null, false, true);
            }

            header('Location: ' . $this->_path . '/backend/modules/manage/members');
            exit();
        } catch(Exception $e) {
            setcookie('module_errorMsg', $e->getMessage(), time() + 365 * 24 * 3600, '/', null, false, true);

            header('Location: ' . $this->_path . '/backend/modules/manage/members');
            exit();
        }
    }
}