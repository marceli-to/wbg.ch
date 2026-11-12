import { onBeforeUnmount, ref, watch } from 'vue';

// Number of open overlays; <html class="has-overlay"> prevents double scrollbars
let open = 0;

function sync() {
    document.documentElement.classList.toggle('has-overlay', open > 0);
}

/**
 * Visibility state for a full screen overlay (closes on Escape).
 */
export function useOverlay() {
    const visible = ref(false);

    const onKeydown = (event) => {
        if (event.key === 'Escape') visible.value = false;
    };

    watch(visible, (value, old) => {
        if (value === old) return;
        open += value ? 1 : -1;
        sync();
        if (value) {
            document.addEventListener('keydown', onKeydown);
        } else {
            document.removeEventListener('keydown', onKeydown);
        }
    });

    onBeforeUnmount(() => {
        if (visible.value) {
            open -= 1;
            sync();
            document.removeEventListener('keydown', onKeydown);
        }
    });

    return {
        visible,
        show: () => (visible.value = true),
        hide: () => (visible.value = false),
        toggle: () => (visible.value = !visible.value),
    };
}
