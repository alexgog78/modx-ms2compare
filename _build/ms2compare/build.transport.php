<?php

/**
 * @var modX $modx
 */
require_once dirname(__DIR__) . '/modx.php';
require_once __DIR__ . '/build.config.php';

/** modPackageBuilder $builder */
$builder = $modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);

/** ms2Compare $service */
$service = $modx->getService(PKG_NAME_LOWER, PKG_NAME, PKG_MODEL_PATH);

/** Creating package */
require_once PKG_TRANSPORT_PATH . 'package.inc.php';

/** Files */
require_once PKG_TRANSPORT_PATH . 'files.inc.php';

/** modSystemSetting */
require_once PKG_TRANSPORT_PATH . 'settings.inc.php';

/** modChunk */
require_once PKG_TRANSPORT_PATH . 'chunks.inc.php';

/** modSnippet */
require_once PKG_TRANSPORT_PATH . 'snippets.inc.php';

/** modPlugin */
require_once PKG_TRANSPORT_PATH . 'plugins.inc.php';

/** modCategory */
require_once PKG_TRANSPORT_PATH . 'category.inc.php';

/** Resolvers */
require_once PKG_TRANSPORT_PATH . 'resolvers.inc.php';

/** Create .zip file */
$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package transport  zip created');
exit();
