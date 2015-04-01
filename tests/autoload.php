<?php

$autoloader = include_once __DIR__ . '/../vendor/autoload.php';
$autoloader->addPsr4("GitTest\\", __DIR__ . '/src');