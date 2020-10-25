<?php

$root = &XCube_Root::getSingleton();
if ($root->mContext->mUser->isInRole('Site.GuestUser')) {
    define('_MI_MYFRIEND_NAME', '新規登録について');
} else {
    define('_MI_MYFRIEND_NAME', 'マイフレンド');
}

define('_MI_MYFRIEND_GROUP', 'ユーザのグループ');
define('_MI_MYFRIEND_GROUP_DESC', 'SNSで使用するユーザのグループ');

define('_MI_MYFRIEND_BLOCK_NAME', '新着ブロック');

define('_MI_MYFRIEND_GUEST_TITLE', '新規登録について');
