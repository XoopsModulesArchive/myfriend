<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

class Myfriendapproval_Form extends XCube_ActionForm
{
    public $actkey;

    public function __construct()
    {
        parent::XCube_ActionForm();
    }

    public function getTokenName()
    {
        return 'module.myfriend.approval.TOKEN';
    }

    public function prepare()
    {
        $this->mFormProperties['note'] = new XCube_TextProperty('note');

        $this->mFormProperties['id'] = new XCube_IntProperty('id');
    }

    public function update($obj)
    {
        $root = &XCube_Root::getSingleton();

        $uid = $root->mContext->mXoopsUser->get('uid');

        $obj->set('uid', $uid);

        $obj->set('auid', $this->get('auid'));

        $obj->set('utime', time());

        $obj->set('note', $this->get('note'));
    }
}
