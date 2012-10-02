<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->name('*.phtml')
    ->notName('autoload_classmap.php')
    ->notName('README.md')
    ->notName('.php_cs')
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->finder($finder);
