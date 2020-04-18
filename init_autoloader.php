<?php
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    $loader = include __DIR__.'/vendor/autoload.php';
    return $loader;
} else {
    throw new \Exception("Loader is not found.");
}
