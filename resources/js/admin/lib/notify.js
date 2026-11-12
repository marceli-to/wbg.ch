import { reactive } from 'vue';

/**
 * Minimal toast notifications (replaces vue-notification).
 * Rendered by components/ui/Notifications.vue.
 */
export const notifications = reactive([]);

let nextId = 1;

export function notify({ type = 'success', text, duration = 3000 }) {
    const id = nextId++;
    notifications.push({ id, type, text });
    setTimeout(() => dismiss(id), duration);
}

export function dismiss(id) {
    const index = notifications.findIndex((n) => n.id === id);
    if (index !== -1) {
        notifications.splice(index, 1);
    }
}

export const success = (text) => notify({ type: 'success', text });
export const error = (text) => notify({ type: 'error', text, duration: 5000 });
