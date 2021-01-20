<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $tpl
 * @var string $lists
 * @var int $page
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

$lists = (!empty($lists)) ? explode(',', $lists) : [];
foreach ($lists as $list) {
    $ms2Compare->resourcesHandler->initializeList($list);
}

$totals = $ms2Compare->resourcesHandler->getTotals();
$link = $page ? $modx->makeUrl($page) : '#';

$modx->setPlaceholder('ms2compare_count', $totals['total']);
foreach ($totals['lists'] as $list => $total) {
    $modx->setPlaceholder('ms2compare_count_' . $list, $total);
}
return $pdoFetch->getChunk($tpl, array_merge([
    'link' => $link,
], $totals));
