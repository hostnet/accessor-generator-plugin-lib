<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerLoader(
    function ($class_name) {
        return class_exists($class_name, true);
    }
);
