<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Core\Response;

final class NoticeService
{
    public function all(): array
    {
        $statement = Database::connection()->query('SELECT * FROM notices ORDER BY id DESC');
        $items = [];
        foreach ($statement->fetchAll() as $notice) {
            $items[] = $this->format($notice);
        }
        return $items;
    }

    public function create(string $title, string $content, int $status): void
    {
        $statement = Database::connection()->prepare(
            'INSERT INTO notices (title, content, api_token, status, created_at, updated_at)
             VALUES (:title, :content, :api_token, :status, :created_at, :updated_at)'
        );
        $statement->execute([
            ':title' => $title,
            ':content' => $content,
            ':api_token' => bin2hex(random_bytes(12)),
            ':status' => $status,
            ':created_at' => app_now(),
            ':updated_at' => app_now(),
        ]);
    }

    public function update(int $id, string $title, string $content, int $status): void
    {
        $this->ensureExists($id);
        $statement = Database::connection()->prepare(
            'UPDATE notices
             SET title = :title, content = :content, status = :status, updated_at = :updated_at
             WHERE id = :id'
        );
        $statement->execute([
            ':title' => $title,
            ':content' => $content,
            ':status' => $status,
            ':updated_at' => app_now(),
            ':id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $this->ensureExists($id);
        $statement = Database::connection()->prepare('DELETE FROM notices WHERE id = :id');
        $statement->execute([':id' => $id]);
    }

    public function findPublishedByToken(string $token): array
    {
        $statement = Database::connection()->prepare(
            'SELECT * FROM notices WHERE api_token = :api_token AND status = 1 LIMIT 1'
        );
        $statement->execute([':api_token' => $token]);
        return $statement->fetch() ?: [];
    }

    private function ensureExists(int $id): void
    {
        $statement = Database::connection()->prepare('SELECT id FROM notices WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        if (!$statement->fetch()) {
            Response::json(404, '公告不存在');
        }
    }

    private function format(array $notice): array
    {
        return [
            'id' => (int) $notice['id'],
            'title' => (string) $notice['title'],
            'content' => (string) $notice['content'],
            'status' => (int) $notice['status'],
            'api_token' => (string) $notice['api_token'],
            'api_url' => app_base_url() . '/api/announcement/' . rawurlencode((string) $notice['api_token']),
            'created_at' => (string) $notice['created_at'],
            'updated_at' => (string) $notice['updated_at'],
        ];
    }
}
