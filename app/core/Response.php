<?php
declare(strict_types=1);

namespace App\Core;

use stdClass;

final class Response
{
    public static function json(int $code, string $message, ?array $data = null): void
    {
        http_response_code($code === 0 ? 200 : $code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'code' => $code,
            'message' => $message,
            'data' => $data ?? new stdClass(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
