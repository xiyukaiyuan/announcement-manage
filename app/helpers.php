<?php
declare(strict_types=1);

function app_is_logged_in(): bool
{
    return isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] > 0;
}

function app_require_login(): void
{
    if (!app_is_logged_in()) {
        \App\Core\Response::json(401, '请先登录');
    }
}

function app_now(): string
{
    return gmdate('Y-m-d\TH:i:s\Z');
}

function app_base_path(): string
{
    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = str_replace('\\', '/', dirname($scriptName));
    if ($basePath === '/' || $basePath === '.') {
        return '';
    }

    return rtrim($basePath, '/');
}

function app_request_path(): string
{
    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = (string) parse_url($requestUri, PHP_URL_PATH);
    $basePath = app_base_path();

    if ($basePath !== '' && str_starts_with($path, $basePath)) {
        $path = substr($path, strlen($basePath));
    }

    return trim($path, '/');
}

function app_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host . app_base_path();
}

function app_url(string $path = ''): string
{
    $normalizedPath = trim($path, '/');
    if ($normalizedPath === '') {
        return app_base_path() === '' ? '/' : app_base_path() . '/';
    }

    return (app_base_path() === '' ? '' : app_base_path()) . '/' . $normalizedPath;
}

function app_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
