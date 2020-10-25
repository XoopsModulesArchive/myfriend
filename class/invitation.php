<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class MyFriendInvitationObject extends XoopsSimpleObject
{
    public function __construct()
    {
        $this->initVar('id', XOBJ_DTYPE_INT, '0', true);

        $this->initVar('uid', XOBJ_DTYPE_INT, '0', true);

        $this->initVar('email', XOBJ_DTYPE_STRING, '', true);

        $this->initVar('actkey', XOBJ_DTYPE_STRING, '', true);

        $this->initVar('utime', XOBJ_DTYPE_INT, '0', true);
    }
}

class MyFriendInvitationHandler extends XoopsObjectGenericHandler
{
    public $mTable = 'myfriend_invitation';

    public $mPrimary = 'id';

    public $mClass = 'MyFriendInvitationObject';

    public function __construct($db)
    {
        parent::XoopsObjectGenericHandler($db);
    }
}
