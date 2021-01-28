<?php

define('MODX_CORE_PATH', dirname(__DIR__) . '/core/');

/**
 * @var modX $modx
 * @noinspection PhpIncludeInspection
 */
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
