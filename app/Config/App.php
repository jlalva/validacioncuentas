<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

class App extends BaseConfig
{

    public string $baseURL = '';
    public function __construct()
    {
        parent::__construct();

        // Construcción dinámica de la baseURL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->baseURL = $protocol . $host . '/validacioncuentas/';
    }

    public array $allowedHostnames = [];

    public string $indexPage = 'index.php';

    public string $uriProtocol = 'PATH_INFO';

    public string $defaultLocale = 'es';

    public bool $negotiateLocale = false;

    public array $supportedLocales = ['en'];

    public string $appTimezone = 'America/Lima';

    public string $charset = 'UTF-8';

    public bool $forceGlobalSecureRequests = false;

    public string $sessionDriver = FileHandler::class;

    public string $sessionCookieName = 'ci_session';

    public int $sessionExpiration = 7200;

    public string $sessionSavePath = WRITEPATH . 'session';

    public bool $sessionMatchIP = false;

    public int $sessionTimeToUpdate = 300;

    public bool $sessionRegenerateDestroy = false;

    public ?string $sessionDBGroup = null;

    public string $cookiePrefix = '';

    public string $cookieDomain = '';

    public string $cookiePath = '/';

    public bool $cookieSecure = false;

    public bool $cookieHTTPOnly = true;

    public ?string $cookieSameSite = 'Lax';

    public array $proxyIPs = [];

    public string $CSRFTokenName = 'csrf_test_name';

    public string $CSRFHeaderName = 'X-CSRF-TOKEN';

    public string $CSRFCookieName = 'csrf_cookie_name';

    public int $CSRFExpire = 7200;

    public bool $CSRFRegenerate = true;

    public bool $CSRFRedirect = false;

    public string $CSRFSameSite = 'Lax';

    public bool $CSPEnabled = false;
}
