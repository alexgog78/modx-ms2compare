<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$classKey = 'modPlugin';
$plugins = include PKG_BUILD_TRANSPORT_DATA_PATH . 'plugins.php';
foreach ($plugins as $data) {
    $plugin = $modx->newObject($classKey);
    if ($data['static_file']) {
        $fileContent = trim(file_get_contents(PKG_ELEMENTS_PATH . 'plugins/' . $data['static_file']));
        preg_match('#\<\?php(.*)#is', $fileContent, $code);
        $data['plugincode'] = rtrim(rtrim(trim($code[1]), '?>'));
    }
    $plugin->fromArray(array_merge([
        'id' => 0,
        'category' => 0,
    ], $data, [
        'static_file' => '',
    ]), '', true, true);

    $events = [];
    if (!empty($data['events'])) {
        foreach ($data['events'] as $pluginEvent) {
            $event = $modx->newObject('modPluginEvent');
            $event->fromArray([
                'event' => $pluginEvent,
                'priority' => 0,
                'propertyset' => 0,
            ], '', true, true);
            $events[] = $event;
        }
        $plugin->addMany($events);
    }

    $vehicle = $builder->createVehicle($plugin, [
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'name',
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => [
            'PluginEvents' => [
                xPDOTransport::PRESERVE_KEYS => true,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => [
                    'pluginid',
                    'event',
                ],
            ],
        ],
    ]);
    $builder->putVehicle($vehicle);
    $modx->log(modX::LOG_LEVEL_INFO, 'Added package ' . $classKey . ': ' . $data['name']);
}
