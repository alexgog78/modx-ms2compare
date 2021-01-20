<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $list
 * @var string $fields
 * @var string $emptyTpl
 */

/** @var ms2Compare $ms2Compare */
$ms2Compare = $modx->getService('ms2compare', 'ms2Compare', MODX_CORE_PATH . 'components/ms2compare/model/');
if (!($ms2Compare instanceof ms2Compare)) {
    exit('Could not load ms2Compare');
}
$ms2Compare->loadWebDefaultCssJs();

/** @var miniShop2 $miniShop2 */
$miniShop2 = $modx->getService('miniShop2');
$miniShop2->initialize($modx->context->key);

/** @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {
    return false;
}
$pdoFetch = new pdoFetch($modx, $scriptProperties);

//unset($_SESSION['ms2compare']);
echo '<pre>';
print_r($_SESSION['ms2compare']);
echo '</pre>';

$fields = (!empty($fields)) ? explode(',', $fields) : [];
$resources = $ms2Compare->resourcesHandler->get($list);
echo '<pre>';
print_r($fields);
print_r($resources);
echo '</pre>';


/*$zzz = $pdoFetch->runSnippet('msProducts', [
    'parents' => 0,
    'resources' => implode(',', $resources),
    'return' => 'json',
    //'toPlaceholder' => 'zzz'
    //'returnIds' => 1,
]);
//$placeholderValue = $modx->getPlaceholder('zzz');
var_dump($zzz);
echo '<hr>';*/


/*$default = array(
    'class' => 'msProduct',
    'where' => $where,
    'leftJoin' => [],
    'innerJoin' => $innerJoin,
    'select' => $select,
    'sortby' => 'msProduct.id',
    'sortdir' => 'ASC',
    'groupby' => implode(', ', $groupby),
    'return' => !empty($returnIds)
        ? 'ids'
        : 'data',
    'nestedChunkPrefix' => 'minishop2_',
);
// Merge all properties and run!
$pdoFetch->setConfig(array_merge($default, $scriptProperties), false);
$rows = $pdoFetch->run();*/

$properties = [
    'class' => 'msProduct',
    'where' => [
        'class_key' => 'msProduct',
        'id:IN' => $resources,
    ],
    'leftJoin' => [
        'Data' => ['class' => 'msProductData'],
        'Vendor' => [
            'class' => 'msVendor',
            'on' => 'Data.vendor=Vendor.id',
        ],
    ],
    'innerJoin' => [

    ],
    'select' => [
        'msProduct' => $modx->getSelectColumns('msProduct', 'msProduct', '', ['content'], true),
        'Data' => $modx->getSelectColumns('msProductData', 'Data', '', ['id'], true),
        'Vendor' => $modx->getSelectColumns('msVendor', 'Vendor', 'vendor.', ['id'], true),
    ],
    'return' => 'data',
];


if (!empty($includeThumbs)) {
    $thumbs = array_map('trim', explode(',', $includeThumbs));
    foreach ($thumbs as $thumb) {
        if (empty($thumb)) {
            continue;
        }
        $properties['leftJoin'][$thumb] = [
            'class' => 'msProductFile',
            'on' => "`{$thumb}`.product_id = msProduct.id AND `{$thumb}`.rank = 0 AND `{$thumb}`.path LIKE '%/{$thumb}/%'",
        ];
        $select[$thumb] = "`{$thumb}`.url as `{$thumb}`";
    }
}

$userProperties = [
    'leftJoin',
    'innerJoin',
    'select',
];
foreach ($userProperties as $v) {
    if (!empty($scriptProperties[$v])) {
        $tmp = $modx->fromJSON($scriptProperties[$v]);
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
    }
    unset($scriptProperties[$v]);
}

$properties = array_merge($properties, $scriptProperties);
$pdoFetch->setConfig($properties, false);
$rows = $pdoFetch->run();
foreach ($rows as $row) {
    $row['price'] = $miniShop2->formatPrice($row['price']);
    $row['old_price'] = $miniShop2->formatPrice($row['old_price']);
    $row['weight'] = $miniShop2->formatWeight($row['weight']);
    $row['idx'] = $pdoFetch->idx++;
}

$output = [];

return $pdoFetch->getChunk($tpl, $output);
