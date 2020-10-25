<?php

require_once dirname(__DIR__, 3) . '/mainfile.php';
$root = &XCube_Root::getSingleton();
$root->mController->executeHeader();

$xoopsLogger = &$root->mController->getLogger();
$xoopsLogger->stopTime();
$root->mController->executeView();
