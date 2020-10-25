<?php

$root = &XCube_Root::getSingleton();
if ($root->mContext->mUser->isInRole('Site.GuestUser')) {
    define('_MI_MYFRIEND_NAME', 'About the initial registration');
} else {
    define('_MI_MYFRIEND_NAME', 'MyFriend');
}

define('_MI_MYFRIEND_GROUP', 'Users Group');
define('_MI_MYFRIEND_GROUP_DESC', 'Group of user who uses it with SNS');

define('_MI_MYFRIEND_BLOCK_NAME', 'Newly arrived block');
