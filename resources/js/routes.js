import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "./components/Dashboard.vue";
import Profile from "./components/Profile.vue";
import Product from "./components/Product.vue";
import Users from "./components/Users.vue";
import NotFound from "./components/NotFound.vue";
export const routes = [
    {
        path: "/dashboard",
        component: Dashboard,
    },
    {
        path: "/home",
        component: Dashboard,
    },
    {
        path: "/users",
        component: Users,
    },
    {
        path: "/profile",
        component: Profile,
    },
    {
        path: "/product",
        component: Product,
    },
    //{
    //    path: ".*",
    //    component: NotFound,
    //    component},

    //{
    //    name: 'edit',
    //    path: '/edit/:id',
    //    component: EditPost
    //}
];
const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
});
export default router;
