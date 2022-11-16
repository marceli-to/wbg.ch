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

// News
import NewsIndex from '@/components/news/Index.vue';
import NewsCreate from '@/components/news/Create.vue';
import NewsEdit from '@/components/news/Edit.vue';

// Jobs
import JobIndex from '@/components/job/Index.vue';
import JobCreate from '@/components/job/Create.vue';
import JobEdit from '@/components/job/Edit.vue';

// Home Grids
import HomeGridIndex from '@/components/home/Index.vue';

// Static content
import ContentIndex from '@/components/content/Index.vue';
import ContentCreate from '@/components/content/Create.vue';
import ContentEdit from '@/components/content/Edit.vue';

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

    // Home grid
    {
        name: 'home',
        path: '/admin/home',
        component: HomeGridIndex,
        meta: { requiresAuth: true },
    },

    // News
    {
        name: 'articles',
        path: '/admin/article',
        component: NewsIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'article-create',
        path: '/admin/article/create',
        component: NewsCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'article-edit',
        path: '/admin/article/edit/:id',
        component: NewsEdit,
        meta: { requiresAuth: true },
    },


    // Job
    {
        name: 'jobs',
        path: '/admin/jobs',
        component: JobIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'job-create',
        path: '/admin/job/create',
        component: JobCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'job-edit',
        path: '/admin/job/edit/:id',
        component: JobEdit,
        meta: { requiresAuth: true },
    },

    // Content
    {
        name: 'contents',
        path: '/admin/contents',
        component: ContentIndex,
        meta: { requiresAuth: true },
    },
    {
        name: 'content-create',
        path: '/admin/content/create',
        component: ContentCreate,
        meta: { requiresAuth: true },
    },
    {
        name: 'content-edit',
        path: '/admin/content/edit/:id',
        component: ContentEdit,
        meta: { requiresAuth: true },
    },
];

export default routes