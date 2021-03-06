<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require_once XOOPS_MODULE_PATH . '/user/forms/AbstractUserEditForm.class.php';

/**
 * This class is generated by makeActionForm tool.
 */
class myfreendRegisterForm extends User_AbstractUserEditForm
{
    public function getTokenName()
    {
        return 'module.myfriend.myfreendRegisterForm.TOKEN';
    }

    public function prepare()
    {
        parent::prepare();

        // Set form properties

        $this->mFormProperties['uname'] = new XCube_StringProperty('uname');

        $this->mFormProperties['email'] = new XCube_StringProperty('email');

        $this->mFormProperties['user_viewemail'] = new XCube_BoolProperty('user_viewemail');

        $this->mFormProperties['timezone_offset'] = new XCube_FloatProperty('timezone_offset');

        $this->mFormProperties['pass'] = new XCube_StringProperty('pass');

        $this->mFormProperties['vpass'] = new XCube_StringProperty('vpass');

        $this->mFormProperties['user_mailok'] = new XCube_BoolProperty('user_mailok');

        $this->mFormProperties['actkey'] = new XCube_StringProperty('actkey');

        $this->mFormProperties['user_regdate'] = new XCube_IntProperty('user_regdate');

        $this->mFormProperties['user_group'] = new XCube_IntProperty('user_group');

        // Set field properties

        $this->mFieldProperties['uname'] = new XCube_FieldProperty($this);

        $this->mFieldProperties['uname']->setDependsByArray(['required', 'maxlength', 'minlength']);

        $this->mFieldProperties['uname']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_UNAME, '25');

        $this->mFieldProperties['uname']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_UNAME, min(25, $this->mConfig['maxuname']));

        $this->mFieldProperties['uname']->addMessage('minlength', _MD_USER_ERROR_MINLENGTH, _MD_USER_LANG_UNAME, $this->mConfig['minuname']);

        $this->mFieldProperties['uname']->addVar('maxlength', min(25, $this->mConfig['maxuname']));

        $this->mFieldProperties['uname']->addVar('minlength', $this->mConfig['minuname']);

        $this->mFieldProperties['email'] = new XCube_FieldProperty($this);

        $this->mFieldProperties['email']->setDependsByArray(['required', 'maxlength', 'email']);

        $this->mFieldProperties['email']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_EMAIL, '60');

        $this->mFieldProperties['email']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_EMAIL, '60');

        $this->mFieldProperties['email']->addVar('maxlength', 60);

        $this->mFieldProperties['email']->addMessage('email', _MD_USER_ERROR_EMAIL, _MD_USER_LANG_EMAIL);

        $this->mFieldProperties['pass'] = new XCube_FieldProperty($this);

        $this->mFieldProperties['pass']->setDependsByArray(['required', 'minlength', 'maxlength']);

        $this->mFieldProperties['pass']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_PASS, '32');

        $this->mFieldProperties['pass']->addMessage('minlength', _MD_USER_ERROR_MINLENGTH, _MD_USER_LANG_PASS, $this->mConfig['minpass']);

        $this->mFieldProperties['pass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_PASS, '32');

        $this->mFieldProperties['pass']->addVar('minlength', $this->mConfig['minpass']);

        $this->mFieldProperties['pass']->addVar('maxlength', 32);

        $this->mFieldProperties['vpass'] = new XCube_FieldProperty($this);

        $this->mFieldProperties['vpass']->setDependsByArray(['required', 'maxlength']);

        $this->mFieldProperties['vpass']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_VERIFYPASS, '32');

        $this->mFieldProperties['vpass']->addMessage('maxlength', _MD_USER_ERROR_MAXLENGTH, _MD_USER_LANG_VERIFYPASS, '32');

        $this->mFieldProperties['vpass']->addVar('maxlength', 32);

        $this->mFieldProperties['timezone_offset'] = new XCube_FieldProperty($this);

        $this->mFieldProperties['timezone_offset']->setDependsByArray(['required']);

        $this->mFieldProperties['timezone_offset']->addMessage('required', _MD_USER_ERROR_REQUIRED, _MD_USER_LANG_TIMEZONE_OFFSET);
    }

    public function load(&$obj)
    {
    }

    public function delete_session()
    {
        $_SESSION['MYFRIEND'] = [];

        unset($_SESSION['MYFRIEND']);
    }

    public function save_session()
    {
        $_SESSION['MYFRIEND'] = [
            'uname' => $this->get('uname'),
'email' => $this->get('email'),
'user_viewemail' => $this->get('user_viewemail'),
'timezone_offset' => $this->get('timezone_offset'),
'pass' => $this->get('pass'),
'user_mailok' => $this->get('user_mailok'),
'actkey' => $this->get('actkey'),
        ];
    }

    public function get_session()
    {
        return $_SESSION['MYFRIEND'] ?? null;
    }

    public function update($obj)
    {
        $obj->set('uname', $this->get('uname'));

        $obj->set('email', $this->get('email'));

        $obj->set('user_viewemail', $this->get('user_viewemail'));

        $obj->set('user_avatar', 'blank.gif', true);

        $obj->set('timezone_offset', $this->get('timezone_offset'));

        $obj->set('pass', md5($this->get('pass')));

        $obj->set('user_mailok', $this->get('user_mailok'));

        $actkey = mb_substr(md5(uniqid(mt_rand(), 1)), 0, 8);

        $obj->set('actkey', $actkey, true);

        $obj->set('user_regdate', time(), true);
    }
}
