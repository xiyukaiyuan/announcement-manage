<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\AssetController;
use App\Controllers\NoticeController;
use App\Core\Response;
use App\Core\Router;

session_start();

$router = new Router();
$authController = new AuthController();
$noticeController = new NoticeController();
$assetController = new AssetController();

$router->get('', static function (): void {
    require APP_ROOT . '/app/views/layout.php';
});

$router->get('admin', static function (): void {
    require APP_ROOT . '/app/views/layout.php';
});

$router->get('assets/app.css', [$assetController, 'css']);
$router->get('assets/app.js', [$assetController, 'js']);

$router->post('api/login', [$authController, 'login']);
$router->post('api/logout', [$authController, 'logout']);
$router->post('api/password', [$authController, 'changePassword']);

$router->get('api/notices', [$noticeController, 'index']);
$router->post('api/notices', [$noticeController, 'store']);
$router->put('api/notices/{id}', [$noticeController, 'update']);
$router->delete('api/notices/{id}', [$noticeController, 'destroy']);
$router->get('api/announcement/{token}', [$noticeController, 'show']);
$router->post('api/announcement/{token}', [$noticeController, 'show']);

try {
    $router->dispatch(
        strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
        app_request_path()
    );
} catch (Throwable $exception) {
    if (str_starts_with(app_request_path(), 'api/')) {
        Response::json(500, '服务器内部错误');
    }

    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    require APP_ROOT . '/app/views/error.php';
}
