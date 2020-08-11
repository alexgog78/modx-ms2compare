<?php

if (empty($_REQUEST['ms2_compare'])) {
    die('Access denied');
}

/** @noinspection PhpIncludeInspection */
require dirname(dirname(dirname(__DIR__))) . '/index.php';
