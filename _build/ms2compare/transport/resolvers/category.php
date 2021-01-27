<?php

/** @var xPDOTransport $transport */

/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx = &$transport->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            if ($category = $modx->getObject('modCategory', ['category' => 'ms2Compare'])) {
                $chunks = $modx->getCollection('modChunk', [
                    'name:IN' => [
                        'ms2compare.add',
                        'ms2compare.count',
                        'ms2compare.table',
                    ],
                ]);
                foreach ($chunks as $item) {
                    $item->set('category', $category->get('id'));
                    $item->save();
                }

                $snippets = $modx->getCollection('modSnippet', [
                    'name:IN' => [
                        'ms2compareAdd',
                        'ms2compareCount',
                        'ms2compareResources',
                    ],
                ]);
                foreach ($snippets as $item) {
                    $item->set('category', $category->get('id'));
                    $item->save();
                }

                $plugins = $modx->getCollection('modPlugin', [
                    'name:IN' => [
                        'ms2Compare',
                    ],
                ]);
                foreach ($plugins as $item) {
                    $item->set('category', $category->get('id'));
                    $item->save();
                }
            }
            break;
        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;
