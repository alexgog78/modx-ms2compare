<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$classKey = 'modSnippet';
$snippets = include PKG_BUILD_TRANSPORT_DATA_PATH . 'snippets.php';
foreach ($snippets as $data) {
    $snippet = $modx->newObject($classKey);
    if ($data['static_file']) {
        $fileContent = trim(file_get_contents(PKG_ELEMENTS_PATH . 'snippets/' . $data['static_file']));
        preg_match('#\<\?php(.*)#is', $fileContent, $code);
        $data['snippet'] = rtrim(rtrim(trim($code[1]), '?>'));
    }
    $snippet->fromArray(array_merge([
        'id' => 0,
        'category' => 0,
    ], $data, [
        'static_file' => '',
    ]), '', true, true);

    $properties = [];
    $propertiesData = include PKG_BUILD_TRANSPORT_PROPERTIES_PATH . 'snippet.' . $data['static_file'];
    foreach ($propertiesData as $propertyData) {
        $properties[] = array_merge([
            'desc' => PKG_NAME_LOWER . '_property_' . strtolower($propertyData['name']),
            'lexicon' => PKG_NAME_LOWER . ':property',
        ], $propertyData);
    }
    $snippet->setProperties($properties);

    $vehicle = $builder->createVehicle($snippet, [
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'name',
    ]);
    $builder->putVehicle($vehicle);
    $modx->log(modX::LOG_LEVEL_INFO, 'Added package ' . $classKey . ': ' . $data['name']);
}
