<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Encryption extends BaseConfig
{

    public string $key = '*#e!ncu3st@s*2023*!#';

    public string $driver = 'OpenSSL';

    public int $blockSize = 16;

    public string $digest = 'SHA512';

    public bool $rawData = true;

    public string $encryptKeyInfo = '';

    public string $authKeyInfo = '';

    public string $cipher = 'AES-256-CTR';
}
