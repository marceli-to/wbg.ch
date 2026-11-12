import { ref } from 'vue';
import api from '@/lib/api';
import { success } from '@/lib/notify';
import { confirmDelete, saveOrder, withLoading } from '@/lib/helpers';

/**
 * Shared behaviour of the index screens: load, publish toggle, clone,
 * delete and drag sorting.
 *
 * endpoint: e.g. "team" -> GET team, PATCH team/{id}/publish, …
 */
export function useResourceList(endpoint, { cloneAt = 'end', refetchAfterClone = false } = {}) {
    const items = ref([]);
    const loaded = ref(false);

    async function fetch() {
        const { data } = await api.get(endpoint);
        items.value = data;
        loaded.value = true;
    }

    function find(id) {
        return items.value.find((item) => item.id === id);
    }

    function toggle(id, event) {
        return withLoading(event, async () => {
            const { data } = await api.patch(`${endpoint}/${id}/publish`);
            const item = find(id);
            if (item) item.publish = data.value;
            success('Status angepasst');
        });
    }

    function clone(id, event) {
        return withLoading(event, async () => {
            const { data } = await api.post(`${endpoint}/${id}/clone`);
            if (refetchAfterClone) {
                await fetch();
            } else if (cloneAt === 'start') {
                items.value.unshift(data);
            } else {
                items.value.push(data);
            }
            success('Eintrag kopiert');
        });
    }

    function destroy(id, event) {
        if (!confirmDelete()) return;
        return withLoading(event, async () => {
            await api.delete(`${endpoint}/${id}`);
            items.value = items.value.filter((item) => item.id !== id);
            success('Eintrag gelöscht');
        });
    }

    async function updateOrder(list = items.value) {
        await saveOrder(`${endpoint}/order`, list);
        success('Reihenfolge angepasst');
    }

    return { items, loaded, fetch, toggle, clone, destroy, updateOrder };
}
