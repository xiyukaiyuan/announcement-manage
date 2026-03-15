<?php
declare(strict_types=1);

$authenticated = app_is_logged_in();
$username = $authenticated ? (string) ($_SESSION['username'] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= app_escape(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= app_escape(app_url('assets/app.css')) ?>">
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js" defer></script>
    <script defer src="<?= app_escape(app_url('assets/app.js')) ?>"></script>
</head>
<body data-authenticated="<?= $authenticated ? '1' : '0' ?>" data-base-path="<?= app_escape(app_base_path()) ?>">
<div class="page">
    <section class="login-screen<?= $authenticated ? ' is-hidden' : '' ?>" id="loginPanel">
        <div class="login-card">
            <div class="login-card__badge">公告后台</div>
            <h1 class="login-card__title"><?= app_escape(APP_NAME) ?></h1>
            <p class="login-card__desc">使用管理员账号登录后即可管理公告与访问链接。</p>
            <form id="loginForm">
                <div class="form-field">
                    <label class="form-field__label" for="username">账号</label>
                    <input class="form-field__control" id="username" name="username" autocomplete="username">
                </div>
                <div class="form-field">
                    <label class="form-field__label" for="password">密码</label>
                    <input class="form-field__control" id="password" name="password" type="password" autocomplete="current-password">
                </div>
                <button class="button button--primary button--block" type="submit">登录</button>
            </form>
        </div>
    </section>

    <section class="admin-screen<?= $authenticated ? ' is-visible' : '' ?>" id="adminPanel">
        <div class="admin-shell">
            <header class="admin-header">
                <div>
                    <h1 class="admin-header__title"><?= app_escape(APP_NAME) ?></h1>
                    <p class="admin-header__desc">集中管理公告内容与对外访问地址。</p>
                </div>
                <div class="admin-header__actions">
                    <button class="button button--secondary" type="button" id="openPasswordModal">修改密码</button>
                    <button class="button button--secondary" type="button" id="logoutButton">退出登录</button>
                </div>
            </header>

            <main class="panel-card">
                <div class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">公告列表</h2>
                        <p class="panel-card__desc">支持新增、编辑、启用、停用和复制独立接口地址。</p>
                    </div>
                    <button class="button button--primary" type="button" id="openNoticeModal">
                        <i data-lucide="plus"></i>
                        <span>添加公告</span>
                    </button>
                </div>
                <div class="table-wrap" id="noticeTableWrap"></div>
            </main>
        </div>
    </section>
</div>

<div class="modal" id="noticeModal">
    <div class="modal__panel modal__panel--wide">
        <div class="modal__header">
            <div>
                <h2 class="modal__title" id="noticeModalTitle">添加公告</h2>
                <p class="modal__desc">填写公告标题和正文，保存后立即生成独立访问地址。</p>
            </div>
            <button class="icon-button" type="button" id="closeNoticeModal" aria-label="关闭公告弹窗">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form id="noticeForm">
            <input type="hidden" id="noticeId" name="id" value="">
            <input type="hidden" id="noticeStatusValue" name="status_value" value="1">
            <div class="form-field">
                <label class="form-field__label" for="noticeTitle">公告标题</label>
                <input class="form-field__control" id="noticeTitle" name="title" maxlength="120" required>
            </div>
            <div class="form-field">
                <label class="form-field__label" for="noticeContent">公告内容</label>
                <textarea class="form-field__control form-field__control--textarea" id="noticeContent" name="content" required></textarea>
            </div>
            <div class="modal__actions">
                <button class="button button--secondary" type="button" id="cancelNoticeButton">取消</button>
                <button class="button button--primary" type="submit" id="saveNoticeButton">保存公告</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="passwordModal">
    <div class="modal__panel">
        <div class="modal__header">
            <div>
                <h2 class="modal__title">修改密码</h2>
                <p class="modal__desc">请填写旧密码与新密码。</p>
            </div>
            <button class="icon-button" type="button" id="closePasswordModal" aria-label="关闭密码弹窗">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form id="passwordForm">
            <div class="form-field">
                <label class="form-field__label" for="oldPassword">旧密码</label>
                <input class="form-field__control" id="oldPassword" name="old_password" type="password" required>
            </div>
            <div class="form-field">
                <label class="form-field__label" for="newPassword">新密码</label>
                <input class="form-field__control" id="newPassword" name="new_password" type="password" required>
            </div>
            <div class="form-field">
                <label class="form-field__label" for="confirmPassword">确认新密码</label>
                <input class="form-field__control" id="confirmPassword" name="confirm_password" type="password" required>
            </div>
            <div class="modal__actions">
                <button class="button button--secondary" type="button" id="cancelPasswordButton">取消</button>
                <button class="button button--primary" type="submit">确认修改</button>
            </div>
        </form>
    </div>
</div>

<div class="toast-list" id="toastList"></div>
</body>
</html>
