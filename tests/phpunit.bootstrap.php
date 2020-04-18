<?php
date_default_timezone_set('UTC');
#ini_set('short_open_tag',true);
include __DIR__.'/../init_autoloader.php';
putenv('UNITTEST=1');
define('TEST_TARGET_APPLICATION_PATH',__DIR__.'/..');
define('RINDOW_TEST_CACHE',     __DIR__.'/../cache');
define('RINDOW_TEST_CLEAR_CACHE_INTERVAL',100000);
