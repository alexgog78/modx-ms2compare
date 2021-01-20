<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 */

/** @var ms2Compare $ms2Compare */
$ms2Compare = $modx->getService('ms2compare', 'ms2Compare', MODX_CORE_PATH . 'components/ms2compare/model/');
if (!($ms2Compare instanceof ms2Compare)) {
    exit('Could not load ms2Compare');
}
$modxEvent = $modx->event->name;
$ms2Compare->handleEvent($modxEvent, $scriptProperties);
return;
