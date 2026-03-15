<?php
declare(strict_types=1);

header('Content-Type: text/css; charset=UTF-8');
?>
:root {
    --bg: #fafafa;
    --panel: #ffffff;
    --panel-soft: #fcfcfc;
    --text: #09090b;
    --muted: #71717a;
    --border: #e4e4e7;
    --border-strong: #d4d4d8;
    --accent: #18181b;
    --accent-soft: #f4f4f5;
    --danger: #dc2626;
    --danger-soft: #fef2f2;
    --success: #16a34a;
    --success-soft: #f0fdf4;
    --shadow: 0 10px 30px rgba(9, 9, 11, 0.06);
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    background: var(--bg);
    color: var(--text);
    font-family: "Segoe UI", "Microsoft YaHei", sans-serif;
}

button,
input,
select,
textarea {
    font: inherit;
}

.page {
    min-height: 100vh;
}

.login-screen,
.admin-screen {
    display: none;
}

.login-screen {
    min-height: 100vh;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.login-screen:not(.is-hidden) {
    display: flex;
}

.admin-screen.is-visible {
    display: block;
}

.login-card,
.panel-card,
.modal__panel,
.toast {
    background: var(--panel);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}

.login-card {
    width: 100%;
    max-width: 420px;
    border-radius: 18px;
    padding: 28px;
}

.login-card__badge {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 0 10px;
    border-radius: 999px;
    background: var(--accent-soft);
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
}

.login-card__title,
.admin-header__title,
.panel-card__title,
.modal__title {
    margin: 16px 0 8px;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.login-card__desc,
.admin-header__desc,
.panel-card__desc,
.modal__desc,
.empty-state,
.notice-meta {
    margin: 0;
    color: var(--muted);
    line-height: 1.65;
}

.admin-shell {
    width: 100%;
    max-width: 1040px;
    margin: 0 auto;
    padding: 40px 24px 56px;
}

.admin-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.admin-header__actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.panel-card {
    border-radius: 20px;
    overflow: hidden;
}

.panel-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid var(--border);
}

.table-wrap {
    padding: 0 24px 24px;
}

.form-field {
    margin-bottom: 16px;
}

.form-field__label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
}

.form-field__control {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--panel);
    color: var(--text);
    padding: 12px 14px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-field__control:focus {
    border-color: var(--border-strong);
    box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.06);
}

.form-field__control--textarea {
    min-height: 180px;
    resize: vertical;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid transparent;
    border-radius: 12px;
    padding: 10px 14px;
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease, opacity 0.2s ease;
}

.button:hover {
    opacity: 0.92;
}

.button--primary {
    background: var(--accent);
    border-color: var(--accent);
    color: #ffffff;
}

.button--secondary {
    background: var(--panel);
    border-color: var(--border);
    color: var(--text);
}

.button--block {
    width: 100%;
}

.icon-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--panel);
    color: var(--text);
    cursor: pointer;
}

.icon-button--success {
    background: var(--success-soft);
    color: var(--success);
    border-color: #bbf7d0;
}

.icon-button--danger {
    background: var(--danger-soft);
    color: var(--danger);
    border-color: #fecaca;
}

.icon-button--neutral {
    background: var(--accent-soft);
    color: var(--text);
}

.notice-table {
    width: 100%;
    border-collapse: collapse;
}

.notice-table thead th {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    background: var(--panel-soft);
    color: var(--muted);
    text-align: left;
    font-size: 12px;
    font-weight: 600;
}

.notice-table thead th:last-child {
    text-align: left;
    width: 180px;
}

.notice-table tbody td {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    vertical-align: top;
    font-size: 14px;
}

.notice-table th:nth-child(2),
.notice-table th:nth-child(3),
.notice-table td:nth-child(2),
.notice-table td:nth-child(3) {
    white-space: nowrap;
}

.notice-table tbody tr:last-child td {
    border-bottom: 0;
}

.notice-title {
    margin: 0 0 6px;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.5;
}

.notice-content {
    color: var(--muted);
    line-height: 1.7;
    white-space: pre-wrap;
    word-break: break-word;
}

.notice-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    white-space: nowrap;
}

.notice-meta {
    white-space: nowrap;
}

.notice-cell--actions {
    width: 180px;
    text-align: right;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 0 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status-pill--on {
    background: var(--success-soft);
    color: var(--success);
}

.status-pill--off {
    background: var(--accent-soft);
    color: var(--muted);
}

.text-button {
    border: 0;
    background: transparent;
    color: var(--muted);
    cursor: pointer;
    padding: 0;
}

.text-button--danger {
    color: var(--danger);
}

.empty-state {
    padding: 56px 12px 44px;
    text-align: center;
}

.modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(9, 9, 11, 0.4);
}

.modal.is-open {
    display: flex;
}

.modal__panel {
    width: 100%;
    max-width: 480px;
    border-radius: 18px;
    padding: 24px;
}

.modal__panel--wide {
    max-width: 640px;
}

.modal__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}

.modal__actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.toast-list {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 20;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-end;
}

.toast {
    width: fit-content;
    min-width: 0;
    max-width: min(360px, calc(100vw - 32px));
    border: 1px solid #bbf7d0;
    border-radius: 16px;
    padding: 14px 16px;
    background: rgba(240, 253, 244, 0.98);
    color: #166534;
    box-shadow: 0 18px 40px rgba(22, 101, 52, 0.14);
    backdrop-filter: blur(10px);
    line-height: 1.6;
    white-space: nowrap;
    animation: toast-in 0.18s ease-out;
}

.toast--error {
    background: rgba(127, 29, 29, 0.96);
    border-color: rgba(248, 113, 113, 0.22);
    color: #fef2f2;
}

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateY(-6px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.is-hidden {
    display: none !important;
}

[data-lucide] {
    width: 16px;
    height: 16px;
    stroke-width: 2;
}

@media (max-width: 860px) {
    .admin-header,
    .panel-card__header {
        grid-template-columns: 1fr;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .admin-header__actions,
    .notice-actions {
        align-items: flex-start;
        justify-content: flex-start;
    }

    .notice-table,
    .notice-table thead,
    .notice-table tbody,
    .notice-table tr,
    .notice-table th,
    .notice-table td {
        display: block;
        width: 100%;
    }

    .notice-table thead {
        display: none;
    }

    .notice-table tbody tr {
        border-bottom: 1px solid var(--border);
    }

    .notice-table tbody td {
        border-bottom: 0;
        padding: 10px 0;
    }
}
