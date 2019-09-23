// Team
import TeamIndex from '@/components/team/Index.vue';
import TeamCreate from '@/components/team/Create.vue';
import TeamEdit from '@/components/team/Edit.vue';

// Clients
import ClientIndex from '@/components/client/Index.vue';
import ClientCreate from '@/components/client/Create.vue';
import ClientEdit from '@/components/client/Edit.vue';

// Categories
import CategoryIndex from '@/components/category/Index.vue';
import CategoryCreate from '@/components/category/Create.vue';
import CategoryEdit from '@/components/category/Edit.vue';

// Competences
import CompetenceIndex from '@/components/competence/Index.vue';
import CompetenceCreate from '@/components/competence/Create.vue';
import CompetenceEdit from '@/components/competence/Edit.vue';

// Projects
import ProjectIndex from '@/components/project/Index.vue';
import ProjectCreate from '@/components/project/Create.vue';
import ProjectEdit from '@/components/project/Edit.vue';

// Project Grids
import ProjectGridIndex from '@/components/project/grid/Index.vue';

// Page
import PageComponent from '@/layout/Page.vue';

// Auth 
import LoginComponent from '@/components/auth/LoginComponent.vue';
import LogoutComponent from '@/components/auth/LogoutComponent.vue';

const routes = [
    {
        path: '/',
        redirect: { name: 'login' }
    },
    {
        path: '/admin',
        name: 'admin',
        component: PageComponent,
        meta: { requiresAuth: true },
    },
    {
        path: '/admin/dashboard',
        name: 'dashboard',
        component: PageComponent,
        meta: { requiresAuth: true },
    },
    {
        path: '/admin/login',
        name: 'login',
        component: LoginComponent
    },
    {
        path: '/admin/logout',
        name: 'logout',
        component: LogoutComponent
    },
    
    // Team
    {
        name: 'team',
        path: '/admin/team',
        component: TeamIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'team-create',
        path: '/admin/team/create',
        component: TeamCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'team-edit',
        path: '/admin/team/edit/:id',
        component: TeamEdit,
        meta: { requiresAuth: true },
    },

    // Clients
    {
        name: 'clients',
        path: '/admin/clients',
        component: ClientIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'client-create',
        path: '/admin/client/create',
        component: ClientCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'client-edit',
        path: '/admin/client/edit/:id',
        component: ClientEdit,
        meta: { requiresAuth: true },
    },

    // Categories
    {
        name: 'categories',
        path: '/admin/categories',
        component: CategoryIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'category-create',
        path: '/admin/category/create',
        component: CategoryCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'category-edit',
        path: '/admin/category/edit/:id',
        component: CategoryEdit,
        meta: { requiresAuth: true },
    },

    // Competences
    {
        name: 'competences',
        path: '/admin/competences',
        component: CompetenceIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'competence-create',
        path: '/admin/competence/create',
        component: CompetenceCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'competence-edit',
        path: '/admin/competence/edit/:id',
        component: CompetenceEdit,
        meta: { requiresAuth: true },
    },

    // Projects
    {
        name: 'projects',
        path: '/admin/projects',
        component: ProjectIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'project-create',
        path: '/admin/project/create',
        component: ProjectCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'project-edit',
        path: '/admin/project/edit/:id',
        component: ProjectEdit,
        meta: { requiresAuth: true },
    },
    {
        name: 'project-grids',
        path: '/admin/project/grid/:id',
        component: ProjectGridIndex,
        meta: { requiresAuth: true },
    },
];

export default routes