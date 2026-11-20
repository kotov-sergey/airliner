// src/js/modules/notification-toast.js

const STORAGE_KEY = 'airliner_pending_toast';

const TOAST_DURATION = 3000;
const ANIMATION_DURATION = 220;

let toastElement = null;
let hideTimer = null;
let removeTimer = null;
let isInitialized = false;

// Получение DOM-элемента уведомления.
function getToastElement() {
    if (!toastElement) {
        toastElement = document.querySelector('.notification-toast');
    }

    return toastElement;
}

// Показ уведомления.
export function showToast({
    message,
    url,
    linkText = 'Перейти',
    duration = TOAST_DURATION
}) {
    const toast = getToastElement();

    if (!toast) return;

    const text = toast.querySelector('.notification-toast__text');
    const link = toast.querySelector('.notification-toast__link');

    if (!text || !link) return;

    clearTimeout(hideTimer);
    clearTimeout(removeTimer);

    text.textContent = message;
    link.href = url;
    link.textContent = linkText;

    toast.hidden = false;

    requestAnimationFrame(() => {
        toast.classList.add('is-visible');
    });

    hideTimer = setTimeout(() => {
        hideToast();
    }, duration);
}

// Скрытие уведомления.
export function hideToast() {
    const toast = getToastElement();

    if (!toast) return;

    clearTimeout(hideTimer);
    clearTimeout(removeTimer);

    toast.classList.remove('is-visible');

    removeTimer = setTimeout(() => {
        toast.hidden = true;
    }, ANIMATION_DURATION);
}

// Сохранение уведомления перед переходом или перезагрузкой.
export function queueToast({
    message,
    url,
    linkText = 'Перейти'
}) {
    try {
        sessionStorage.setItem(
            STORAGE_KEY,
            JSON.stringify({
                message,
                url,
                linkText
            })
        );
    } catch {
        // Если sessionStorage недоступен, уведомление не сохраняется.
    }
}

// Получение и однократный показ сохранённого уведомления.
function showQueuedToast() {
    let data;

    try {
        const storedData = sessionStorage.getItem(STORAGE_KEY);

        if (!storedData) return;

        sessionStorage.removeItem(STORAGE_KEY);

        data = JSON.parse(storedData);
    } catch {
        return;
    }

    if (!data?.message || !data?.url) return;

    showToast(data);
}

// Инициализация уведомления.
export function initNotificationToast() {
    if (isInitialized) return;

    const toast = getToastElement();

    if (!toast) return;

    const closeButton = toast.querySelector(
        '.notification-toast__close'
    );

    if (closeButton) {
        closeButton.addEventListener('click', hideToast);
    }

    isInitialized = true;

    // Показываем уведомление, сохранённое перед перезагрузкой.
    showQueuedToast();
}