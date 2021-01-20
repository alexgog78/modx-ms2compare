<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $list
 * @var string $emptyTpl
 */

/** @var ms2Compare $ms2Compare */
$ms2Compare = $modx->getService('ms2compare', 'ms2Compare', MODX_CORE_PATH . 'components/ms2compare/model/');
if (!($ms2Compare instanceof ms2Compare)) {
    exit('Could not load ms2Compare');
}
$ms2Compare->loadWebDefaultCssJs();

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, $scriptProperties);

//unset($_SESSION['ms2compare']);
echo '<pre>';
print_r($_SESSION['ms2compare']);
echo '</pre>';
