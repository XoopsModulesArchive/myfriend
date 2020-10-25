<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

class Myfriend_Block extends Legacy_BlockProcedure
{
    public $options = [];

    public $myfriendDelegate;

    public $mNewAlert = [];

    public function __construct(&$block)
    {
        parent::Legacy_BlockProcedure($block);
    }

    public function prepare()
    {
        $root = &XCube_Root::getSingleton();

        if ($root->mContext->mUser->isInRole('Site.RegisteredUser')) {
            $root->mLanguageManager->loadModinfoMessageCatalog(basename(dirname(__DIR__)));

            $this->myfriendDelegate = new XCube_Delegate();

            $this->myfriendDelegate->register('Myfriend.NewAlert');

            $this->myfriendDelegate->call(new XCube_Ref($this->mNewAlert));
        }
    }

    public function getTitle()
    {
        return _MI_MYFRIEND_BLOCK_NAME;
    }

    public function isDisplay()
    {
        if (count($this->mNewAlert) > 0) {
            return true;
        }

        return false;
    }

    public function execute()
    {
        $render = &$this->getRenderTarget();

        $render->setTemplateName($this->_mBlock->get('template'));

        $render->setAttribute('mid', $this->_mBlock->get('mid'));

        $render->setAttribute('bid', $this->_mBlock->get('bid'));

        $render->setAttribute('block', $this->mNewAlert);

        $root = &XCube_Root::getSingleton();

        $renderSystem = &$root->getRenderSystem($this->getRenderSystemName());

        $renderSystem->renderBlock($render);
    }
}
