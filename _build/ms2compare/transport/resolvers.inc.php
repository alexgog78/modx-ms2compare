<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$source = PKG_BUILD_TRANSPORT_RESOLVERS_PATH . 'category.php';
$vehicle = $builder->createVehicle([
    'source' => $source,
], [
    'vehicle_class' => 'xPDOScriptVehicle',
]);
$builder->putVehicle($vehicle);
$modx->log(modX::LOG_LEVEL_INFO, 'Added resolver: ' . $source);
