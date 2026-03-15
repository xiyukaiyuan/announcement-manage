<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->map('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->map('DELETE', $path, $handler);
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['params'] as $name) {
                $params[] = $matches[$name] ?? null;
            }

            call_user_func_array($route['handler'], $params);
            return;
        }

        if (str_starts_with($path, 'api/')) {
            Response::json(404, '接口不存在');
        }

        http_response_code(404);
        header('Content-Type: text/html; charset=UTF-8');
        require APP_ROOT . '/app/views/not-found.php';
        exit;
    }

    private function map(string $method, string $path, callable $handler): void
    {
        $params = [];
        $pattern = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $matches) use (&$params): string {
            $params[] = $matches[1];
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $path);

        $this->routes[$method][] = [
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
            'params' => $params,
        ];
    }
}
