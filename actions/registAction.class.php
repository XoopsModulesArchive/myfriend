<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require _MY_MODULE_PATH . 'forms/myfriendregisterForm.class.php';

class registAction extends MyFriend_Abstract
{
    public $tplname;

    public $rdata = false;

    public function __construct()
    {
        $root = &XCube_Root::getSingleton();

        if (is_object($root->mContext->mXoopsUser)) {
            $this->isError = true;

            $this->errMsg = _MD_MYFRIEND_ACTERR1;

            return;
        }

        $actkey = $root->mContext->mRequest->getRequest('actkey');

        $modhand = xoops_getModuleHandler('invitation');

        $mCriteria = new CriteriaCompo();

        $mCriteria->add(new Criteria('actkey', $actkey));

        $modObj = &$modhand->getObjects($mCriteria);

        if (1 == count($modObj)) {
            $this->tplname = 'myfriend_invitation_regist.html';

            $root->mController->setupModuleContext('user');

            $root->mLanguageManager->loadModuleMessageCatalog('user');

            $this->_processActionForm();

            $this->mActionForm->set('timezone_offset', $root->mContext->getXoopsConfig('default_TZ'));

            $this->mActionForm->set('actkey', $actkey);

            $this->mActionForm->delete_session();

            if ('POST' == xoops_getenv('REQUEST_METHOD')) {
                $this->mActionForm->fetch();

                $this->mActionForm->validate();

                if (!$this->mActionForm->hasError()) {
                    $this->tplname = 'myfriend_invitation_regisconft.html';

                    $this->mActionForm->save_session();
                }
            }
        } else {
            $this->tplname = 'myfriend_invitation_none.html';
        }
    }

    //http://localhost/21b3/modules/myfriend/index.php?action=regist&actkey=3e16af2cfc

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

        $render->setAttribute('rdata', $this->rdata);

        $render->setAttribute('actionForm', $this->mActionForm);

        $tzoneHandler = xoops_getHandler('timezone');

        $timezones = &$tzoneHandler->getObjects();

        $render->setAttribute('timezones', $timezones);
    }
}
