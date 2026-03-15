<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
define('APP_NAME', '公告管理平台');
define('DB_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'data');
define('DB_PATH', DB_DIR . DIRECTORY_SEPARATOR . 'database.sqlite');
define('DEFAULT_USERNAME', 'admin');
define('DEFAULT_PASSWORD', '123456');

date_default_timezone_set('Asia/Shanghai');

require APP_ROOT . '/app/helpers.php';
require APP_ROOT . '/app/core/Database.php';
require APP_ROOT . '/app/core/Request.php';
require APP_ROOT . '/app/core/Response.php';
require APP_ROOT . '/app/core/Router.php';
require APP_ROOT . '/app/services/AuthService.php';
require APP_ROOT . '/app/services/NoticeService.php';
require APP_ROOT . '/app/controllers/AuthController.php';
require APP_ROOT . '/app/controllers/NoticeController.php';
require APP_ROOT . '/app/controllers/AssetController.php';

\App\Core\Database::initialize();
