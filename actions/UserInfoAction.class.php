<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

define('USER_USERINFO_MAXHIT', 5);

class Myfriend_UserInfoAction
{
    public $mObject = null;

    public $mRankObject = null;

    public $mSearchResults = null;

    public $mSelfDelete = false;

    public $mPmliteURL = null;

    public $errFlg = false;

    public $errMsg;

    public $fuid;

    public function __construct($controller)
    {
        $user = &$controller->mRoot->mContext->mUser;

        if ($user->isInRole('Site.GuestUser')) {
            $this->_seterrMsg(_MD_MYFRIEND_NOGUEST);
        } else {
            $this->getDefaultView($controller);
        }
    }

    public function _seterrMsg($msg)
    {
        $this->errFlg = true;

        $this->errMsg[] = $msg;
    }

    public function getisError()
    {
        return $this->errFlg;
    }

    public function geterrMsg()
    {
        return $this->errMsg;
    }

    public function getDefaultView($controller)
    {
        $root = &$controller->mRoot;

        $xoopsUser = $root->mContext->mXoopsUser;

        $this->fuid = (int)$controller->mRoot->mContext->mRequest->getRequest('uid');

        $handler = xoops_getHandler('user');

        $this->mObject = $handler->get($this->fuid);

        if (!is_object($this->mObject)) {
            $this->_seterrMsg(_MD_MYFRIEND_NOUSER);

            return;
        }

        $rankHandler = xoops_getModuleHandler('ranks', 'user');

        $this->mRankObject = $rankHandler->get($this->mObject->get('rank'));

        $service = &$root->mServiceManager->getService('privateMessage');

        if (null != $service) {
            $client = &$root->mServiceManager->createClient($service);

            $this->mPmliteURL = $client->call('getPmliteUrl', ['fromUid' => $xoopsUser->get('uid'), 'toUid' => $this->fuid]);
        }

        unset($service);

        $service = &$root->mServiceManager->getService('LegacySearch');

        if (null != $service) {
            $this->mSearchResults = [];

            $client = &$root->mServiceManager->createClient($service);

            $moduleArr = $client->call('getActiveModules', []);

            foreach ($moduleArr as $t_module) {
                $module = [];

                $module['name'] = $t_module['name'];

                $module['mid'] = $t_module['mid'];

                $params['mid'] = $t_module['mid'];

                $params['uid'] = $this->mObject->get('uid');

                $params['maxhit'] = USER_USERINFO_MAXHIT;

                $params['start'] = 0;

                $module['results'] = $client->call('searchItemsOfUser', $params);

                if (count($module['results']) > 0) {
                    $module['has_more'] = (count($module['results']) >= USER_USERINFO_MAXHIT) ? true : false;

                    $this->mSearchResults[] = $module;
                }
            }
        }
    }

    public function executeView($render)
    {
        $render->setTemplateName('myfriend_userinfo.html');

        $render->setAttribute('thisUser', $this->mObject);

        $render->setAttribute('rank', $this->mRankObject);

        $render->setAttribute('pmliteUrl', $this->mPmliteURL);

        $render->setAttribute('isFriend', $this->chk_myfriend());

        $userSignature = $this->mObject->getShow('user_sig');

        $render->setAttribute('user_signature', $userSignature);

        $render->setAttribute('searchResults', $this->mSearchResults);

        $root = &XCube_Root::getSingleton();

        $xoopsUser = &$root->mContext->mXoopsUser;

        $user_ownpage = (is_object($xoopsUser) && $xoopsUser->get('uid') == $this->mObject->get('uid'));

        $render->setAttribute('user_ownpage', $user_ownpage);

        $render->setAttribute('myfriends', $this->get_myfreindlist());
    }

    public function get_myfreindlist()
    {
        $freiends = false;

        $root = &XCube_Root::getSingleton();

        $db = &$root->mController->mDB;

        $sql = 'SELECT u.`uid`, u.`uname`, u.`user_avatar` avatar ';

        $sql .= 'FROM `' . $db->prefix('users') . '` u, `' . $db->prefix('myfriend_friendlist') . '` m ';

        $sql .= 'WHERE m.`uid` = ' . $this->fuid . ' ';

        $sql .= 'AND u.`uid` = m.`friend_uid` ';

        $sql .= 'ORDER BY m.`uid`, rand() ';

        $sql .= 'LIMIT 0, 9';

        $result = $db->query($sql);

        while (false !== ($val = $db->fetchArray($result))) {
            $freiends[] = $val;
        }

        return $freiends;
    }

    public function chk_myfriend()
    {
        $num = 0;

        $root = &XCube_Root::getSingleton();

        $db = &$root->mController->mDB;

        $sql = 'SELECT COUNT(*) FROM `' . $db->prefix('myfriend_friendlist') . '` ';

        $sql .= 'WHERE `uid` = ' . $root->mContext->mXoopsUser->get('uid');

        $sql .= ' AND `friend_uid` = ' . $this->fuid;

        $result = $db->query($sql);

        [$num] = $db->fetchRow($result);

        return $num;
    }
}
