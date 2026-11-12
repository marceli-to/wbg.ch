import { createRouter, createWebHistory } from 'vue-router';
import AdminLayout from '@/layout/AdminLayout.vue';

const auth = { requiresAuth: true };

/**
 * Index / create / edit routes of a resource.
 * e.g. resource('team', 'team', 'team') -> /team, /team/create, /team/edit/:id
 */
function resource(listName, listPath, singular, dir) {
    return [
        { name: listName, path: listPath, component: () => import(`./views/${dir}/Index.vue`), meta: auth },
        { name: `${singular}-create`, path: `${singular}/create`, component: () => import(`./views/${dir}/Form.vue`), props: { type: 'create' }, meta: auth },
        { name: `${singular}-edit`, path: `${singular}/edit/:id`, component: () => import(`./views/${dir}/Form.vue`), props: { type: 'edit' }, meta: auth },
    ];
}

const routes = [
    { name: 'login', path: '/login', component: () => import('./views/auth/Login.vue') },
    { name: 'logout', path: '/logout', component: () => import('./views/auth/Logout.vue') },
    {
        path: '/',
        component: AdminLayout,
        meta: auth,
        children: [
            // The old dashboard was an empty page; land on the projects instead
            { path: '', redirect: { name: 'projects' } },
            { name: 'dashboard', path: 'dashboard', redirect: { name: 'projects' } },

            ...resource('team', 'team', 'team', 'team'),
            ...resource('clients', 'clients', 'client', 'client'),
            ...resource('categories', 'categories', 'category', 'category'),
            ...resource('competences', 'competences', 'competence', 'competence'),
            ...resource('projects', 'projects', 'project', 'project'),
            { name: 'project-grids', path: 'project/grid/:id', component: () => import('./views/project/Grid.vue'), meta: auth },
            { name: 'home', path: 'home', component: () => import('./views/home/Index.vue'), meta: auth },
            ...resource('articles', 'article', 'article', 'news'),
            ...resource('jobs', 'jobs', 'job', 'job'),
            ...resource('contents', 'contents', 'content', 'content'),

            { path: ':pathMatch(.*)*', redirect: { name: 'projects' } },
        ],
    },
];

export function createAdminRouter(authStore) {
    const router = createRouter({
        history: createWebHistory('/admin/'),
        routes,
        scrollBehavior: () => ({ top: 0 }),
    });

    router.beforeEach(async (to) => {
        await authStore.check();

        if (to.matched.some((r) => r.meta.requiresAuth) && !authStore.user) {
            return { name: 'login', query: to.fullPath !== '/' ? { redirect: to.fullPath } : {} };
        }

        if (to.name === 'login' && authStore.user) {
            return { name: 'projects' };
        }
    });

    return router;
}
