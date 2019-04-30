<?php

use Doctrine\Common\Annotations\AnnotationRegistry;


$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer', __DIR__.'/../vendor/jms/serializer/src/');


if (!$loader) {
    echo "You must install the dev dependencies using:\n";
    echo "    composer install --dev\n";
    exit(1);
}
