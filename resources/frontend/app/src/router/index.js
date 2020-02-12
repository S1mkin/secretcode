import Vue from "vue";
import VueRouter from "vue-router";
import AddSecretcode from "../views/Add_secretcode.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Add_secretcode",
        component: AddSecretcode,
        meta: {
            title: "Add secretcode",
            requiresAuth: false
        }
    },
    {
        path: "/sign_in",
        name: "Sign_in",
        component: () => import("../views/Sign_in.vue"),
        meta: {
            title: "Sign in",
            requiresAuth: false
        }
    },
    {
        path: "/secretcodes",
        name: "Secretcodes",
        component: () => import("../views/Secretcodes.vue"),
        meta: {
            title: "All secretcode",
            requiresAuth: false
        }
    },
    {
        path: "*",
        name: "404",
        component: () => import("../views/404.vue"),
        meta: {
            title: "Page not found",
            requiresAuth: false
        }
    }
];

const router = new VueRouter({
    mode: "history",
    base: "/",
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
});

export default router;
