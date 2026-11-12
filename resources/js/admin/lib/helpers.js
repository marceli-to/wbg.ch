import api from '@/lib/api';

export const PANOPTIKUM_ID = 3;

export function truncate(text, length, suffix = '...') {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + suffix : text;
}

/**
 * Toggle the "is-loading" class on the clicked element while fn runs.
 * Use with @click="withLoading($event, () => …)".
 */
export async function withLoading(event, fn) {
    const el = event?.currentTarget || event?.target || null;
    el?.classList.add('is-loading');
    try {
        return await fn();
    } finally {
        el?.classList.remove('is-loading');
    }
}

export function confirmDelete() {
    return window.confirm('Bitte löschen bestätigen!');
}

/**
 * Persist the sort order of a list: PUT {endpoint} {"items":[{id, order}]}.
 */
export function saveOrder(endpoint, list) {
    const items = list.map((item, index) => ({ id: item.id, order: index }));
    list.forEach((item, index) => (item.order = index));
    return api.put(endpoint, { items });
}

export const media = {
    thumbnail: (name) => `/media/thumbnail/${name}`,
    source: (name) => `/media/source/${name}`,
    size: (name, size) => `/media/${name}/${size}`,
    layoutIcon: (key) => `/assets/admin/img/icons/grid-layout-${key}.svg`,
};
