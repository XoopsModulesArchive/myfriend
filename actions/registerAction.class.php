<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require _MY_MODULE_PATH . 'forms/myfriendregisterForm.class.php';
require_once XOOPS_MODULE_PATH . '/user/class/RegistMailBuilder.class.php';

//http://localhost/xoopscube/modules/myfriend/index.php?action=regist&actkey=b07204ef934a9bb9b306
class registerAction extends MyFriend_Abstract
{
    public $tplname;

    public $mNewUser = null;

    public $mConfig;

    public $myGroups = [];

    public function __construct()
    {
        $root = &XCube_Root::getSingleton();

        if (is_object($root->mContext->mXoopsUser)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR1;

            return;
        }

        $this->myGroups = $root->mContext->mModuleConfig['in_group'];

        $root->mController->setupModuleContext('user');

        $root->mLanguageManager->loadModuleMessageCatalog('user');

        $this->_processActionForm();

        $sesArray = $this->mActionForm->get_session();

        $this->mActionForm->delete_session();

        $this->mActionForm->fetch();

        $this->mActionForm->set('uname', $sesArray['uname']);

        $this->mActionForm->set('email', $sesArray['email']);

        $this->mActionForm->set('user_viewemail', $sesArray['user_viewemail']);

        $this->mActionForm->set('timezone_offset', $sesArray['timezone_offset']);

        $this->mActionForm->set('pass', $sesArray['pass']);

        $this->mActionForm->set('vpass', $sesArray['pass']);

        $this->mActionForm->set('user_mailok', $sesArray['user_mailok']);

        $this->mActionForm->set('actkey', $sesArray['actkey']);

        $modhand = xoops_getModuleHandler('invitation');

        $mCriteria = new CriteriaCompo();

        $mCriteria->add(new Criteria('actkey', $sesArray['actkey']));

        $modObj = &$modhand->getObjects($mCriteria);

        if (1 == count($modObj)) {
            //$this->mActionForm->fetch();

            $this->mActionForm->validate();

            if (!$this->mActionForm->hasError()) {
                if ($this->insertUser()) {
                    $this->isError = true;

                    $this->errMsg = _MD_MYFRIEND_ACTERR2;

                    $uid = $modObj[0]->get('uid');

                    if ($modhand->delete($modObj[0])) {
                        $frihand = xoops_getModuleHandler('friend');

                        $friObj = $frihand->create();

                        $friObj->set('uid', $uid);

                        $friObj->set('friend_uid', $this->mNewUser->get('uid'));

                        $friObj->set('utime', time());

                        if ($frihand->insert($friObj)) {
                            $friObj->set('uid', $this->mNewUser->get('uid'));

                            $friObj->set('friend_uid', $uid);

                            if (!$frihand->insert($friObj)) {
                                $this->errMsg = _MD_MYFRIEND_ACTERR3;
                            }
                        } else {
                            $this->errMsg = _MD_MYFRIEND_ACTERR3;
                        }
                    } else {
                        $this->errMsg = _MD_MYFRIEND_ACTERR4;
                    }

                    return;
                }

                return;
            }
        } else {
            $this->tplname = 'myfriend_invitation_none.html';
        }
    }

    public function insertUser()
    {
        $root = &XCube_Root::getSingleton();

        $moduleHandler = xoops_getHandler('module');

        $usermod = $moduleHandler->getByDirname('user');

        $configHandler = xoops_getHandler('config');

        $this->mConfig = $configHandler->getConfigsByCat(0, $usermod->get('mid'));

        $memberHandler = xoops_getHandler('member');

        $this->mNewUser = &$memberHandler->createUser();

        $this->mActionForm->update($this->mNewUser);

        $this->mNewUser->set('uorder', $root->mContext->getXoopsConfig('com_order'), true);

        $this->mNewUser->set('umode', $root->mContext->getXoopsConfig('com_mode'), true);

        if (1 == $this->mConfig['activation_type']) {
            $this->mNewUser->set('level', 1, true);
        }

        if (!$memberHandler->insertUser($this->mNewUser)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR5;

            return false;
        }

        foreach ($this->myGroups as $group) {
            if (!$memberHandler->addUserToGroup($group, $this->mNewUser->get('uid'))) {
                $this->isError = true;

                $this->errMsg = _MD_MYFRIEND_ACTERR6;

                return false;
            }
        }

        $this->_processMail($root->mController);

        $this->_eventNotifyMail($root->mController);

        XCube_DelegateUtils::call('Legacy.Event.RegistUser.Success', new XCube_Ref($this->mNewUser));

        return true;
    }

    public function _processMail($controller)
    {
        $activationType = $this->mConfig['activation_type'];

        if (1 == $activationType) {
            return;
        }

        // Wmm..

        $builder = (0 == $activationType) ? new User_RegistUserActivateMailBuilder() : new User_RegistUserAdminActivateMailBuilder();

        $director = new User_UserRegistMailDirector($builder, $this->mNewUser, $controller->mRoot->mContext->getXoopsConfig(), $this->mConfig);

        $director->contruct();

        $mailer = &$builder->getResult();

        if (!$mailer->send()) {
        }  // TODO CHECKS and use '_MD_USER_ERROR_YOURREGMAILNG'
    }

    public function _eventNotifyMail($controller)
    {
        if (1 == $this->mConfig['new_user_notify'] && !empty($this->mConfig['new_user_notify_group'])) {
            $builder = new User_RegistUserNotifyMailBuilder();

            $director = new User_UserRegistMailDirector($builder, $this->mNewUser, $controller->mRoot->mContext->getXoopsConfig(), $this->mConfig);

            $director->contruct();

            $mailer = &$builder->getResult();

            $mailer->send();
        }
    }

    public function _processActionForm()
    {
        $moduleHandler = xoops_getHandler('module');

        $usermod = $moduleHandler->getByDirname('user');

        $configHandler = xoops_getHandler('config');

        $configs = $configHandler->getConfigsByCat(0, $usermod->get('mid'));

        $this->mActionForm = new myfreendRegisterForm($configs);

        $this->mActionForm->prepare();
    }

    public function executeView($render)
    {
        $render->setTemplateName($this->tplname);
    }
}
