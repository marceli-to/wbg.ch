<template>
    <div>
        <header class="site-header">
            <div>
                <a href="/admin/" class="brand">WBG</a>
                <a href="javascript:;" @click="toggleMenu()" class="icon-menu"></a>
            </div>
        </header>
        <nav class="site-nav" :class="{'is-visible': menuVisible}">
            <div>
                <a href="javascript:;" @click="toggleMenu()" class="icon-close"></a>
            </div>
            <ul>
                <li>
                    <router-link :to="{ name: 'home' }">Home</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'articles' }">Artikel</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'projects' }">Projekte</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'team' }">Team</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'clients' }">Kunden</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'categories' }">Kategorien</router-link>
                </li>
                <li>
                    <router-link :to="{ name: 'competences' }">Kompetenzen</router-link>
                </li>
            </ul>
            <router-link :to="{ name: 'logout' }" class="icon-logout">Logout</router-link>
        </nav>
    </div>
</template>
<script>
export default {
    data() {
        return {
            menuVisible: false
        }
    },
    methods: {
        toggleMenu() {
            this.menuVisible = !this.menuVisible;
        }
    }
}
</script>