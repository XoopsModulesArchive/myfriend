<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require _MY_MODULE_PATH . 'forms/myfriendPageNavi.class.php';

define('_MYDIARY_PAGENUM', 10);

class indexAction extends MyFriend_Abstract
{
    public $listuser;

    public $mPagenavi = null;

    public $listinvi;

    public function __construct()
    {
        $root = &XCube_Root::getSingleton();

        $uid = $root->mContext->mXoopsUser->getVar('uid');

        $modhand = xoops_getModuleHandler('friend');

        $this->mPagenavi = new Myfriend_PageNavi($modhand);

        $this->mPagenavi->fetch();

        $this->mPagenavi->addCriteria('uid', $uid);

        $modObj = &$modhand->getObjects($this->mPagenavi->getCriteria());

        $userhand = xoops_getHandler('user');

        foreach ($modObj as $mod) {
            $this->listuser[] = $userhand->get($mod->getShow('friend_uid'));
        }

        $modhand = xoops_getModuleHandler('invitation');

        $mCriteria = new CriteriaCompo();

        $mCriteria->add(new Criteria('uid', $uid));

        $modObj = &$modhand->getObjects($mCriteria);

        foreach ($modObj as $mod) {
            foreach (array_keys($mod->gets()) as $var_name) {
                $item_ary[$var_name] = $mod->getShow($var_name);
            }

            $item_ary['formattedDate'] = formatTimestamp($item_ary['utime']);

            $this->listinvi[] = $item_ary;
        }
    }

    public function executeView($render)
    {
        $render->setTemplateName('myfriend_index.html');

        $render->setAttribute('ListData', $this->listuser);

        $render->setAttribute('pageNavi', $this->mPagenavi->mNavi);

        $render->setAttribute('invidata', $this->listinvi);
    }
}
