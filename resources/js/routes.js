// const Welcome = () => import('./components/Welcome.vue' /* webpackChunkName: "resource/js/components/welcome" */)
// const CategoryList = () => import('./components/category/List.vue' /* webpackChunkName: "resource/js/components/category/list" */)
// const CategoryCreate = () => import('./components/category/Add.vue' /* webpackChunkName: "resource/js/components/category/add" */)
// const CategoryEdit = () => import('./components/category/Edit.vue' /* webpackChunkName: "resource/js/components/category/edit" */)
// export const routes = [
//     {
//         name: 'home',
//         path: '/',
//         component: Welcome
//     },
//     {
//         name: 'categoryList',
//         path: '/category',
//         component: CategoryList
//     },
//     {
//         name: 'categoryEdit',
//         path: '/category/:id/edit',
//         component: CategoryEdit
//     },
//     {
//         name: 'categoryAdd',
//         path: '/category/add',
//         component: CategoryCreate
//     }
// ]

// Import your components lazily
const Welcome = () => import('./components/Welcome.vue' /* webpackChunkName: "resource/js/components/welcome" */);
const CategoryList = () => import('./components/category/List.vue' /* webpackChunkName: "resource/js/components/category/list" */);
const CategoryCreate = () => import('./components/category/Add.vue' /* webpackChunkName: "resource/js/components/category/add" */);
const CategoryEdit = () => import('./components/category/Edit.vue' /* webpackChunkName: "resource/js/components/category/edit" */);
const Login = () => import('./components/Login.vue' /* webpackChunkName: "resource/js/components/login" */); // Add a login component

// Define your routes
export const routes = [
    {
        name: 'home',
        path: '/',
        component: Welcome,
        meta: { requiresAuth: true }
    },
    {
        name: 'categoryList',
        path: '/category',
        component: CategoryList,
        meta: { requiresAuth: true }
    },
    {
        name: 'categoryEdit',
        path: '/category/:id/edit',
        component: CategoryEdit,
        meta: { requiresAuth: true }
    },
    {
        name: 'categoryAdd',
        path: '/category/add',
        component: CategoryCreate,
        meta: { requiresAuth: true }
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: { requiresGuest: true }
    }
];

// Import Vue Router
import { createRouter, createWebHistory } from 'vue-router';

// Create a Vue Router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Add navigation guard
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const isAuthenticated = !!token;
  
    // Check if the route requires authentication
    if (to.meta.requiresAuth && !isAuthenticated) {
      // If not authenticated, redirect to login
      next({ name: 'login' });
    } else if (to.meta.requiresGuest && isAuthenticated) {
      // If authenticated, prevent access to guest-only routes like login
      next({ name: 'home' });
    } else {
      next(); // Otherwise, allow access
    }
  });

export default router;
