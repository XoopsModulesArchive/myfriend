<?php

if (!defined('XOOPS_ROOT_PATH')) {
    die();
}

class Myfriend_Preload extends XCube_ActionFilter
{
    public function postFilter()
    {
        if ($this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser')) {
            $file = XOOPS_ROOT_PATH . '/modules/myfriend/kernel/myfriend.class.php';

            $this->mRoot->mDelegateManager->add('Legacypage.Userinfo.Access', 'MyfriendFunction::userinfo', XCUBE_DELEGATE_PRIORITY_FIRST, $file);

            $this->mRoot->mDelegateManager->add('Myfriend.NewAlert', 'Myfriend_Preload::getMyfriendNew');
        }
    }

    public function getMyfriendNew(&$arrays)
    {
        $root = &XCube_Root::getSingleton();

        if ($root->mContext->mUser->isInRole('Site.RegisteredUser')) {
            $root->mLanguageManager->loadModuleMessageCatalog('myfriend');

            $uid = $root->mContext->mXoopsUser->get('uid');

            $modHand = xoops_getModuleHandler('application', 'myfriend');

            $mCriteria = new CriteriaCompo();

            $mCriteria->add(new Criteria('uid', $uid));

            $modObj = &$modHand->getObjects($mCriteria);

            foreach ($modObj as $obj) {
                $arrays[] = [
                    'url' => XOOPS_MODULE_URL . '/myfriend/index.php?action=approval&amp;id=' . $obj->get('id'),
'title' => _MD_MYFRIEND_NEW,
                ];
            }
        }
    }
}
