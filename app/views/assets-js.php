<?php
declare(strict_types=1);

header('Content-Type: application/javascript; charset=UTF-8');
?>
const loginPanel = document.getElementById('loginPanel');
const adminPanel = document.getElementById('adminPanel');
const loginForm = document.getElementById('loginForm');
const noticeForm = document.getElementById('noticeForm');
const noticeTableWrap = document.getElementById('noticeTableWrap');
const logoutButton = document.getElementById('logoutButton');
const openPasswordModal = document.getElementById('openPasswordModal');
const closePasswordModal = document.getElementById('closePasswordModal');
const cancelPasswordButton = document.getElementById('cancelPasswordButton');
const passwordModal = document.getElementById('passwordModal');
const passwordForm = document.getElementById('passwordForm');
const toastList = document.getElementById('toastList');
const openNoticeModal = document.getElementById('openNoticeModal');
const closeNoticeModal = document.getElementById('closeNoticeModal');
const cancelNoticeButton = document.getElementById('cancelNoticeButton');
const noticeModal = document.getElementById('noticeModal');
const noticeModalTitle = document.getElementById('noticeModalTitle');
const appState = { authenticated: document.body.dataset.authenticated === '1' };
const basePath = document.body.dataset.basePath || '';

function buildUrl(path) {
    const normalizedPath = String(path || '').replace(/^\/+/, '');
    if (!normalizedPath) {
        return basePath || '/';
    }
    return `${basePath}/${normalizedPath}`.replace(/\/{2,}/g, '/');
}

function refreshIcons() {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
}

loginForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const result = await requestJson('POST', buildUrl('api/login'), Object.fromEntries(new FormData(loginForm).entries()));
    if (!result) {
        return;
    }
    appState.authenticated = true;
    loginPanel.classList.add('is-hidden');
    adminPanel.classList.add('is-visible');
    showToast(result.message);
    await loadNotices();
    refreshIcons();
});

openNoticeModal?.addEventListener('click', () => {
    openNoticeEditor();
});

closeNoticeModal?.addEventListener('click', () => {
    closeNoticeEditor();
});

cancelNoticeButton?.addEventListener('click', () => {
    closeNoticeEditor();
});

noticeModal?.addEventListener('click', (event) => {
    if (event.target === noticeModal) {
        closeNoticeEditor();
    }
});

noticeForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(noticeForm);
    const id = String(formData.get('id') || '').trim();
    const payload = {
        title: String(formData.get('title') || '').trim(),
        content: String(formData.get('content') || '').trim(),
        status: String(formData.get('status_value') || '1')
    };

    if (!payload.title || !payload.content) {
        showToast('请完整填写公告标题和内容', true);
        return;
    }

    const method = id ? 'PUT' : 'POST';
    const url = id ? buildUrl(`api/notices/${id}`) : buildUrl('api/notices');
    const result = await requestJson(method, url, payload);
    if (!result) {
        return;
    }

    closeNoticeEditor();
    showToast(result.message);
    await loadNotices();
});

logoutButton?.addEventListener('click', async () => {
    const result = await requestJson('POST', buildUrl('api/logout'), {});
    if (!result) {
        return;
    }
    appState.authenticated = false;
    loginPanel.classList.remove('is-hidden');
    adminPanel.classList.remove('is-visible');
    noticeTableWrap.innerHTML = '';
    showToast(result.message);
});

openPasswordModal?.addEventListener('click', () => {
    passwordModal.classList.add('is-open');
    refreshIcons();
});

closePasswordModal?.addEventListener('click', closePasswordEditor);
cancelPasswordButton?.addEventListener('click', closePasswordEditor);

passwordModal?.addEventListener('click', (event) => {
    if (event.target === passwordModal) {
        closePasswordEditor();
    }
});

passwordForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const payload = Object.fromEntries(new FormData(passwordForm).entries());
    if (payload.new_password !== payload.confirm_password) {
        showToast('两次输入的新密码不一致', true);
        return;
    }
    const result = await requestJson('POST', buildUrl('api/password'), payload);
    if (!result) {
        return;
    }
    closePasswordEditor();
    showToast(result.message);
});

noticeTableWrap?.addEventListener('click', async (event) => {
    const target = event.target;
    if (!(target instanceof Element)) {
        return;
    }

    const actionNode = target.closest('[data-action]');
    if (!(actionNode instanceof HTMLElement)) {
        return;
    }

    const action = actionNode.dataset.action || '';

    if (action === 'edit') {
        openNoticeEditor(parseNoticePayload(actionNode.dataset.notice || ''));
        return;
    }

    if (action === 'toggle') {
        const notice = parseNoticePayload(actionNode.dataset.notice || '');
        const result = await requestJson('PUT', buildUrl(`api/notices/${notice.id}`), {
            title: notice.title,
            content: notice.content,
            status: Number(notice.status) === 1 ? '0' : '1'
        });
        if (!result) {
            return;
        }
        showToast(Number(notice.status) === 1 ? '公告已停用' : '公告已启用');
        await loadNotices();
        return;
    }

    if (action === 'copy') {
        try {
            await navigator.clipboard.writeText(actionNode.dataset.value || '');
            showToast('接口链接已复制');
        } catch (error) {
            showToast('复制失败，请手动复制', true);
        }
        return;
    }

    if (action === 'delete') {
        const id = actionNode.dataset.id;
        if (!id || !window.confirm('确定删除这条公告吗？')) {
            return;
        }
        const result = await requestJson('DELETE', buildUrl(`api/notices/${id}`), {});
        if (!result) {
            return;
        }
        showToast(result.message);
        await loadNotices();
    }
});

function openNoticeEditor(notice = null) {
    if (notice) {
        document.getElementById('noticeId').value = notice.id;
        document.getElementById('noticeTitle').value = notice.title;
        document.getElementById('noticeContent').value = notice.content;
        document.getElementById('noticeStatusValue').value = String(notice.status);
        noticeModalTitle.textContent = '编辑公告';
    } else {
        noticeForm.reset();
        document.getElementById('noticeId').value = '';
        document.getElementById('noticeStatusValue').value = '1';
        noticeModalTitle.textContent = '添加公告';
    }
    noticeModal.classList.add('is-open');
    refreshIcons();
}

function closeNoticeEditor() {
    noticeModal.classList.remove('is-open');
    noticeForm.reset();
    document.getElementById('noticeId').value = '';
    document.getElementById('noticeStatusValue').value = '1';
    noticeModalTitle.textContent = '添加公告';
}

function closePasswordEditor() {
    passwordModal.classList.remove('is-open');
    passwordForm.reset();
}

async function loadNotices() {
    if (!appState.authenticated) {
        return;
    }
    const result = await requestJson('GET', buildUrl('api/notices'));
    if (!result) {
        return;
    }
    renderNotices(result.data.notices || []);
    refreshIcons();
}

function renderNotices(notices) {
    if (!notices.length) {
        noticeTableWrap.innerHTML = '<div class="empty-state">暂无公告，点击右上角按钮创建第一条公告。</div>';
        return;
    }

    const rows = notices.map((notice) => {
        const statusText = Number(notice.status) === 1 ? '启用中' : '已停用';
        const statusClass = Number(notice.status) === 1 ? 'status-pill status-pill--on' : 'status-pill status-pill--off';
        const toggleClass = Number(notice.status) === 1 ? 'icon-button icon-button--danger' : 'icon-button icon-button--success';
        const toggleIcon = Number(notice.status) === 1 ? 'power-off' : 'power';
        const toggleLabel = Number(notice.status) === 1 ? '停用公告' : '启用公告';

        return `
            <tr>
                <td>
                    <h3 class="notice-title">${escapeHtml(notice.title)}</h3>
                </td>
                <td>
                    <span class="${statusClass}">${statusText}</span>
                </td>
                <td>
                    <div class="notice-meta">${escapeHtml(notice.updated_at)}</div>
                </td>
                <td>
                    <div class="notice-actions">
                        <button class="${toggleClass}" type="button" data-action="toggle" data-notice="${encodeNoticePayload(notice)}" aria-label="${toggleLabel}">
                            <i data-lucide="${toggleIcon}"></i>
                        </button>
                        <button class="icon-button icon-button--neutral" type="button" data-action="edit" data-notice="${encodeNoticePayload(notice)}" aria-label="编辑公告">
                            <i data-lucide="pencil"></i>
                        </button>
                        <button class="icon-button" type="button" data-action="copy" data-value="${escapeAttr(notice.api_url)}" aria-label="复制链接">
                            <i data-lucide="copy"></i>
                        </button>
                        <button class="icon-button icon-button--danger" type="button" data-action="delete" data-id="${notice.id}" aria-label="删除公告">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    noticeTableWrap.innerHTML = `
        <table class="notice-table">
            <thead>
                <tr>
                    <th>公告</th>
                    <th>状态</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>
    `;
}

function encodeNoticePayload(notice) {
    return encodeURIComponent(JSON.stringify(notice));
}

function parseNoticePayload(value) {
    try {
        return JSON.parse(decodeURIComponent(String(value || '')));
    } catch (error) {
        return {};
    }
}

async function requestJson(method, url, payload = null) {
    try {
        const options = {
            method,
            headers: { Accept: 'application/json' },
            credentials: 'same-origin'
        };
        if (method !== 'GET') {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(payload || {});
        }
        const response = await fetch(url, options);
        const result = await response.json();
        if (!response.ok || Number(result.code) !== 0) {
            showToast(result.message || '请求失败', true);
            return null;
        }
        return result;
    } catch (error) {
        showToast('请求失败，请检查服务状态', true);
        return null;
    }
}

function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.className = isError ? 'toast toast--error' : 'toast';
    toast.textContent = message;
    toastList.appendChild(toast);
    window.setTimeout(() => {
        toast.remove();
    }, 2800);
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;')
        .replaceAll('\n', '<br>');
}

function escapeAttr(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;');
}

window.addEventListener('load', () => {
    refreshIcons();
    if (appState.authenticated) {
        loadNotices();
    }
});
