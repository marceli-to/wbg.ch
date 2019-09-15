<template>
    <footer class="data-footer form-buttons">
        <div>
            <button type="submit" class="btn-secondary">Speichern</button>
            <router-link :to="{name: route}">Zurück</router-link>
        </div>
    </footer>
</template>
<script>
export default {
    props: {
        route: String,
    }
}
</script>