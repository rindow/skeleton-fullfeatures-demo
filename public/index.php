<?php
include __DIR__.'/../init_autoloader.php';

$config = require __DIR__.'/../config/webapp.config.php';
/*
$cache = __DIR__.'/../cache/cache.config.php';
if(file_exists($cache)) {
    $config = require $cache;
} else {
    $config = require __DIR__.'/../config/webapp.config.php';
    foreach(Rindow\Stdlib\FileUtil\Dir::factory()->glob(__DIR__.'/../config/local','@\.php$@') as $file) {
        $config = array_replace_recursive($config,require $file);
    }
    file_put_contents($cache, '<?php return '.var_export($config, true).';');
}
*/
$app = new Rindow\Container\ModuleManager($config);
$app->run();
