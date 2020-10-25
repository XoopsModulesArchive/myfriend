<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require _MY_MODULE_PATH . 'forms/myfriendapplicationForm.class.php';

class applicationAction extends MyFriend_Abstract
{
    public $tplname;

    public $auser;

    public function __construct()
    {
        $root = &XCube_Root::getSingleton();

        $auid = (int)$root->mContext->mRequest->getRequest('auid');

        $uid = $root->mContext->mXoopsUser->get('uid');

        $this->mActionForm = new Myfriendapplication_Form();

        $this->mActionForm->prepare();

        $handler = xoops_getHandler('user');

        $this->auser = $handler->get($auid);

        if (!is_object($this->auser)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR7;
        }

        $this->mActionForm->load($auid);

        if ($this->chk_application($uid, $auid)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR8;
        }

        if ($this->chk_myfriend($uid, $auid)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR9;
        }

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->mActionForm->fetch();

            $this->mActionForm->validate();

            if ($this->mActionForm->hasError()) {
                $this->isError = true;

                $this->errMsg = implode('<br>', $this->mActionForm->getErrorMessages());

                return;
            }

            $ahandler = xoops_getModuleHandler('application');

            $modObj = $ahandler->create();

            $this->mActionForm->update($modObj);

            $this->isError = true;

            if (!$ahandler->insert($modObj)) {
                $this->errMsg = _MD_MYFRIEND_ACTERR10;
            } else {
                $this->errMsg = _MD_MYFRIEND_ACTERR11;
            }
        } else {
            $this->tplname = 'myfriend_application.html';
        }
    }

    public function chk_application($uid, $auid)
    {
        $mCriteria = new CriteriaCompo();

        $mCriteria->add(new Criteria('uid', $uid));

        $mCriteria->add(new Criteria('auid', $auid));

        $ahandler = xoops_getModuleHandler('application');

        return $ahandler->getCount($mCriteria);
    }

    public function chk_myfriend($uid, $auid)
    {
        $num = 0;

        $root = &XCube_Root::getSingleton();

        $db = &$root->mController->mDB;

        $sql = 'SELECT COUNT(*) FROM `' . $db->prefix('myfriend_friendlist') . '` ';

        $sql .= 'WHERE `uid` = ' . $uid;

        $sql .= ' AND `friend_uid` = ' . $auid;

        $result = $db->query($sql);

        [$num] = $db->fetchRow($result);

        return $num;
    }

    public function executeView($render)
    {
        $render->setTemplateName($this->tplname);

        $render->setAttribute('ActionForm', $this->mActionForm);

        $render->setAttribute('auser', $this->auser);

        $render->setAttribute('titlemsg', XCube_Utils::formatString(_MD_MYFRIEND_APP, $this->auser->getShow('uname')));
    }
}
