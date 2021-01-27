<?php

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $list
 * @var string $fields
 * @var string $fieldsToExclude
 * @var string $fieldsToShowIfEmpty
 * @var string $tpl
 * @var string $tplEmpty
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

$fields = (!empty($fields)) ? explode(',', $fields) : [];
$fieldsMeta = $modx->getFieldMeta('msProductData');
if (empty($fields)) {
    $fieldsToExclude = (!empty($fieldsToExclude)) ? explode(',', $fieldsToExclude) : [];
    $fields = array_diff(array_keys($fieldsMeta), $fieldsToExclude);
}

$resources = $ms2Compare->resourcesHandler->get($list);
$total = $ms2Compare->resourcesHandler->getListTotal($list);
if (!$total) {
    return $pdoFetch->getChunk($tplEmpty);
}

$output = [
    'list' => $list,
    'fields' => $fields,
    'total' => $total,
    'products' => [],
    'rows' => [],
];

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

$pdoProperties = array_merge($properties, $scriptProperties);
$pdoFetch->setConfig($pdoProperties, false);


$products = $pdoFetch->run();
if (!empty($products) && is_array($products)) {
    foreach ($products as $product) {
        $product['price'] = $miniShop2->formatPrice($product['price']);
        $product['old_price'] = $miniShop2->formatPrice($product['old_price']);
        $product['weight'] = $miniShop2->formatWeight($product['weight']);

        $output['products'][] = $product;
        foreach ($fields as $field) {
            if (!isset($output['rows'][$field])) {
                $output['rows'][$field] = [
                    'same' => false,
                    'values' => [],
                ];
            }
            $output['rows'][$field]['values'][$product['id']] = $product[$field];
        }
    }
}

$fieldsToShowIfEmpty = (!empty($fieldsToShowIfEmpty)) ? explode(',', $fieldsToShowIfEmpty) : [];
foreach ($output['rows'] as $field => $values) {
    $values = $values['values'];
    foreach ($values as $index => $value) {
        if ($fieldsMeta[$field]['phptype'] == 'float') {
            $values[$index] = (float)$value;
        }
        if (is_array($value)) {
            $values[$index] = implode(',', $value);
        }
    }

    $uniqueValues = array_unique($values);
    $countValues = count($uniqueValues);
    if ($countValues == 1) {
        $output['rows'][$field]['same'] = true;
        $value = current($uniqueValues);
        if ((!$value) && !in_array($field, $fieldsToShowIfEmpty)) {
            unset($output['rows'][$field]);
        }
    }
}

$modx->lexicon->load('minishop2:product');
$modx->setPlaceholder('ms2compare_count_' . $list, $total);
return $pdoFetch->getChunk($tpl, $output);
