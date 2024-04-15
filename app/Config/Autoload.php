<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{

    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'CodeIgniter\\Database' => APPPATH.'Database',
    ];

    public $classmap = [];

    public $files = [];

    public $helpers = ['menu', 'uri', 'function', 'empresa'];

    public $libraries = ['qr_code'];
}
