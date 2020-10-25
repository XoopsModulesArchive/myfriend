<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require XOOPS_ROOT_PATH . '/core/XCube_PageNavigator.class.php';

class Myfriend_PageNavi
{
    public $_mCriteria = null;

    public $mNavi = null;

    public $_mHandler = null;

    public function __construct(&$handler)
    {
        $this->_mHandler = &$handler;

        $this->_mCriteria = new CriteriaCompo();

        $this->mNavi = new XCube_PageNavigator('index.php');

        $this->mNavi->mGetTotalItems->add([&$this, 'getTotalItems']);

        $this->mNavi->setPerpage(_MYDIARY_PAGENUM);

        $this->mNavi->fetch();
    }

    public function getTotalItems(&$total)
    {
        $total = $this->_mHandler->getCount($this->getCriteria());
    }

    public function fetch()
    {
        $this->_mCriteria->setSort('udate', 'DESC');
    }

    public function addCriteria($key, $val)
    {
        $this->_mCriteria->add(new Criteria($key, $val));
    }

    public function getCriteria()
    {
        $this->_mCriteria->setSort('utime', 'DESC');

        $this->_mCriteria->setStart($this->mNavi->getStart());

        $this->_mCriteria->setLimit($this->mNavi->getPerpage());

        return $this->_mCriteria;
    }
}
