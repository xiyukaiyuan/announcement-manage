<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\NoticeService;

final class NoticeController
{
    public function __construct(
        private readonly NoticeService $noticeService = new NoticeService()
    ) {
    }

    public function index(): void
    {
        app_require_login();
        Response::json(0, '获取成功', [
            'notices' => $this->noticeService->all(),
        ]);
    }

    public function store(): void
    {
        app_require_login();
        $payload = Request::json();
        $title = trim((string) ($payload['title'] ?? ''));
        $content = trim((string) ($payload['content'] ?? ''));
        $status = ((string) ($payload['status'] ?? '1')) === '1' ? 1 : 0;

        if ($title === '' || $content === '') {
            Response::json(422, '公告标题和内容不能为空');
        }

        $this->noticeService->create($title, $content, $status);
        Response::json(0, '公告创建成功');
    }

    public function update(string $id): void
    {
        app_require_login();
        $payload = Request::json();
        $title = trim((string) ($payload['title'] ?? ''));
        $content = trim((string) ($payload['content'] ?? ''));
        $status = ((string) ($payload['status'] ?? '1')) === '1' ? 1 : 0;

        if ($title === '' || $content === '') {
            Response::json(422, '公告标题和内容不能为空');
        }

        $this->noticeService->update((int) $id, $title, $content, $status);
        Response::json(0, '公告更新成功');
    }

    public function destroy(string $id): void
    {
        app_require_login();
        $this->noticeService->delete((int) $id);
        Response::json(0, '公告删除成功');
    }

    public function show(string $token): void
    {
        $notice = $this->noticeService->findPublishedByToken($token);
        if ($notice === []) {
            Response::json(404, '公告不存在或已停用');
        }

        Response::json(0, '获取成功', [
            'id' => (int) $notice['id'],
            'title' => (string) $notice['title'],
            'content' => (string) $notice['content'],
            'updated_at' => (string) $notice['updated_at'],
        ]);
    }
}
