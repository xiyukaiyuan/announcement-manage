<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

final class AuthService
{
    public function attempt(string $username, string $password): array
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $statement->execute([':username' => $username]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, (string) $user['password_hash'])) {
            return [];
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = (string) $user['username'];
        return $user;
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword): bool
    {
        $statement = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $userId]);
        $user = $statement->fetch();

        if (!$user || !password_verify($oldPassword, (string) $user['password_hash'])) {
            return false;
        }

        $updateStatement = Database::connection()->prepare(
            'UPDATE users SET password_hash = :password_hash, updated_at = :updated_at WHERE id = :id'
        );
        $updateStatement->execute([
            ':password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':updated_at' => app_now(),
            ':id' => $userId,
        ]);
        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
    }
}
