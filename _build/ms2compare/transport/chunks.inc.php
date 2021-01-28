<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$classKey = 'modChunk';
$chunks = include PKG_BUILD_TRANSPORT_DATA_PATH . 'chunks.php';
foreach ($chunks as $data) {
    $chunk = $modx->newObject($classKey);
    if ($data['static_file']) {
        $data['snippet'] = file_get_contents(PKG_ELEMENTS_PATH . 'chunks/' . $data['static_file']);
    }
    $chunk->fromArray(array_merge([
        'id' => 0,
        'category' => 0,
    ], $data, [
        'static_file' => '',
    ]), '', true, true);

    $vehicle = $builder->createVehicle($chunk, [
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'name',
    ]);
    $builder->putVehicle($vehicle);
    $modx->log(modX::LOG_LEVEL_INFO, 'Added package ' . $classKey . ': ' . $data['name']);
}
