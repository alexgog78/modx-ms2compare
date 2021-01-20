<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var int $id
 * @var string $list
 * @var string $tpl
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

$resourceId = $id ?? $modx->resource->id;
$active = $ms2Compare->resourcesHandler->check($resourceId, $list);
return $pdoFetch->getChunk($tpl, [
    'id' => $resourceId,
    'list' => $list,
    'active' => $active,
]);
