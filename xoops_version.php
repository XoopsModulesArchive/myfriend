<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

$modversion['name'] = _MI_MYFRIEND_NAME;
$modversion['dirname'] = basename(__DIR__);

$modversion['version'] = 0.13;
$modversion['description'] = _MI_MYFRIEND_NAME;
$modversion['author'] = 'Marijuana';
$modversion['image'] = 'slogo.gif';

$modversion['cube_style'] = true;
$modversion['read_any'] = true;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][] = '{prefix}_{dirname}_friendlist';
$modversion['tables'][] = '{prefix}_{dirname}_invitation';
$modversion['tables'][] = '{prefix}_{dirname}_applist';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 1;

$modversion['templates'][] = ['file' => 'myfriend_index.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_invitation.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_invitation_confirm.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_invitation_none.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_invitation_regist.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_invitation_regisconft.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_userinfo.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_application.html', 'description' => ''];
$modversion['templates'][] = ['file' => 'myfriend_approval.html', 'description' => ''];

$modversion['blocks'][0]['file'] = 'myfriend_block.clas.php';
$modversion['blocks'][0]['name'] = _MI_MYFRIEND_BLOCK_NAME;
$modversion['blocks'][0]['description'] = '';
$modversion['blocks'][0]['show_func'] = '';
$modversion['blocks'][0]['class'] = 'Block';
$modversion['blocks'][0]['template'] = 'myfriend_block_template.html';
$modversion['blocks'][0]['visible'] = '1';
$modversion['blocks'][0]['func_num'] = 1;

$modversion['config'][0]['name'] = 'in_group';
$modversion['config'][0]['title'] = '_MI_MYFRIEND_GROUP';
$modversion['config'][0]['description'] = '_MI_MYFRIEND_GROUP_DESC';
$modversion['config'][0]['formtype'] = 'group_multi';
$modversion['config'][0]['valuetype'] = 'array';
$modversion['config'][0]['default'] = [XOOPS_GROUP_USERS];
