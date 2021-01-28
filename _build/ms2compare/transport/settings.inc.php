<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$classKey = 'modSystemSetting';
$settings = include PKG_BUILD_TRANSPORT_DATA_PATH . 'settings.php';
foreach ($settings as $data) {
    $setting = $modx->newObject($classKey);
    $setting->fromArray(array_merge([
        'namespace' => PKG_NAME_LOWER,
    ], $data), '', true, true);

    $vehicle = $builder->createVehicle($setting, [
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => false,
        xPDOTransport::UNIQUE_KEY => 'key',
    ]);
    $builder->putVehicle($vehicle);
    $modx->log(modX::LOG_LEVEL_INFO, 'Added package ' . $classKey . ': ' . $data['key']);
}
