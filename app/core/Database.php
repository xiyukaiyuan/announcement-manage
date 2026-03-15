<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $pdo = null;

    public static function initialize(): void
    {
        if (!is_dir(DB_DIR)) {
            mkdir(DB_DIR, 0777, true);
        }

        $pdo = self::connection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )'
        );

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS notices (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                api_token TEXT NOT NULL UNIQUE,
                status INTEGER NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
            )'
        );

        $countStatement = $pdo->query('SELECT COUNT(*) AS total FROM users');
        $total = (int) ($countStatement->fetch()['total'] ?? 0);
        if ($total > 0) {
            return;
        }

        $statement = $pdo->prepare(
            'INSERT INTO users (username, password_hash, created_at, updated_at)
             VALUES (:username, :password_hash, :created_at, :updated_at)'
        );
        $statement->execute([
            ':username' => DEFAULT_USERNAME,
            ':password_hash' => password_hash(DEFAULT_PASSWORD, PASSWORD_DEFAULT),
            ':created_at' => app_now(),
            ':updated_at' => app_now(),
        ]);
    }

    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        self::$pdo = new PDO('sqlite:' . DB_PATH);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return self::$pdo;
    }
}
