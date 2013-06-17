<?php
chdir(__DIR__);

$dir  = getcwd();
$prev = '.';
while (!is_dir($dir . '/vendor')) {
    $dir = dirname($dir);
    if ($prev === $dir) return false;
    $prev = $dir;
}

// This path needs to be setup correctly.
// If you're using composer, the default is likely correct.
require $dir . '/vendor/spiffy/spiffy-test/src/SpiffyTest/Module.php';