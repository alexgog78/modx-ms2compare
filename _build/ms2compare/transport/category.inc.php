<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$classKey = 'modCategory';
$category = $modx->newObject($classKey);
$category->set('id', 1);
$category->set('category', PKG_NAME);
$vehicle = $builder->createVehicle($category, [
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
]);
$builder->putVehicle($vehicle);
$modx->log(modX::LOG_LEVEL_INFO, 'Added package ' . $classKey . ': ' . PKG_NAME);
