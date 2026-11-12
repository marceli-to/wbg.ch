import { reactive, ref } from 'vue';
import { error as notifyError } from '@/lib/notify';

/**
 * Field error state + tabs for the create/edit forms.
 *
 * fieldTabs maps a field name to the tab it lives on, so a tab can be
 * flagged when one of its fields has an error.
 */
export function useForm({ tabs = ['data'], fieldTabs = {} } = {}) {
    const errors = reactive({});
    const activeTab = ref(tabs[0]);
    const tabErrors = reactive({});

    function setError(field, message = true) {
        errors[field] = message;
        tabErrors[fieldTabs[field] || tabs[0]] = true;
    }

    function clearError(field) {
        delete errors[field];
    }

    function clearErrors() {
        Object.keys(errors).forEach((key) => delete errors[key]);
        Object.keys(tabErrors).forEach((key) => delete tabErrors[key]);
    }

    function changeTab(tab) {
        Object.keys(tabErrors).forEach((key) => delete tabErrors[key]);
        activeTab.value = tab;
    }

    /**
     * Client side "required" check. Returns true when all fields are filled,
     * otherwise flags them and shows the validation notice.
     */
    function validateRequired(model, fields) {
        clearErrors();
        let valid = true;
        for (const field of fields) {
            const value = model[field];
            if (value === null || value === undefined || value === '') {
                setError(field);
                valid = false;
            }
        }
        if (!valid) {
            validationError(false);
        }
        return valid;
    }

    /**
     * Notice + scroll to top. The API interceptor already shows the notice
     * for server side 422s, hence the flag.
     */
    function validationError(serverSide = true) {
        if (!serverSide) {
            notifyError('Bitte markierte Felder prüfen!');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /**
     * Map a 422 response to the fields. Returns true when handled.
     */
    function handleServerErrors(err) {
        const data = err?.response?.data;
        if (err?.response?.status !== 422 || !data?.errors) {
            return false;
        }
        for (const [key, messages] of Object.entries(data.errors)) {
            setError(key.split('.')[0], messages[0]);
        }
        validationError(true);
        return true;
    }

    return {
        errors,
        activeTab,
        tabErrors,
        setError,
        clearError,
        clearErrors,
        changeTab,
        validateRequired,
        handleServerErrors,
    };
}
