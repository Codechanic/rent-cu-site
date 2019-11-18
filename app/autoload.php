<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var $loader ClassLoader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));


//Registering Doctrine extensions (yosbel)
$classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', __DIR__.'/../vendor/beberlei/lib');
$classLoader->register();

return $loader;
