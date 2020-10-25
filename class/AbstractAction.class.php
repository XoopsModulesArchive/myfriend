<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class MyFriend_Abstract
{
    public $isError = false;

    public $errMsg = '';

    public $mActionForm;

    public function __construct()
    {
    }

    public function getisError()
    {
        return $this->isError;
    }

    public function geterrMsg()
    {
        return $this->errMsg;
    }

    public function executeView(&$render)
    {
    }
}
