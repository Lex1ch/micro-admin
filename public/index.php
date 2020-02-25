<?php

declare(strict_types=1);

define('CMS_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$container = require_once __DIR__.'/../app/bootstrap.php';

/** Запускаем роуты */
$container->get('App\Modules\Route')->run();