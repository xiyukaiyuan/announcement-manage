<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

final class AuthController
{
    public function __construct(
        private readonly AuthService $authService = new AuthService()
    ) {
    }

    public function login(): void
    {
        $payload = Request::json();
        $username = trim((string) ($payload['username'] ?? ''));
        $password = (string) ($payload['password'] ?? '');

        if ($username === '' || $password === '') {
            Response::json(422, '账号和密码不能为空');
        }

        $user = $this->authService->attempt($username, $password);
        if ($user === []) {
            Response::json(401, '账号或密码错误');
        }

        Response::json(0, '登录成功', [
            'username' => (string) $user['username'],
        ]);
    }

    public function logout(): void
    {
        app_require_login();
        $this->authService->logout();
        Response::json(0, '已退出登录');
    }

    public function changePassword(): void
    {
        app_require_login();
        $payload = Request::json();
        $oldPassword = (string) ($payload['old_password'] ?? '');
        $newPassword = (string) ($payload['new_password'] ?? '');
        $confirmPassword = (string) ($payload['confirm_password'] ?? '');

        if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
            Response::json(422, '请完整填写密码信息');
        }

        if ($newPassword !== $confirmPassword) {
            Response::json(422, '两次输入的新密码不一致');
        }

        if (mb_strlen($newPassword) < 6) {
            Response::json(422, '新密码长度不能少于 6 位');
        }

        if (!$this->authService->changePassword((int) $_SESSION['user_id'], $oldPassword, $newPassword)) {
            Response::json(401, '旧密码错误');
        }

        Response::json(0, '密码修改成功');
    }
}
