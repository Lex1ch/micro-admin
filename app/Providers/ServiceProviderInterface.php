<?php

namespace App\Providers;

use UltraLite\Container\Container;

interface ServiceProviderInterface
{
    public function register(Container $container);
}