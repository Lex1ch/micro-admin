<?php

namespace App\Modules;

class Config
{
    protected $config = [];

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/app.php';
    }

    public function get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}