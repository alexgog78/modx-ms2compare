<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$source = MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER;
$vehicle = $builder->createVehicle([
    'source' => $source,
    'target' => "return MODX_CORE_PATH . 'components/';",
], [
    'vehicle_class' => 'xPDOFileVehicle',
]);
$builder->putVehicle($vehicle);
$modx->log(modX::LOG_LEVEL_INFO, 'Added files: ' . $source);

$source = MODX_ASSETS_PATH . 'components/' . PKG_NAME_LOWER;
$vehicle = $builder->createVehicle([
    'source' => $source,
    'target' => "return MODX_ASSETS_PATH . 'components/';",
], [
    'vehicle_class' => 'xPDOFileVehicle',
]);
$builder->putVehicle($vehicle);
$modx->log(modX::LOG_LEVEL_INFO, 'Added files: ' . $source);
