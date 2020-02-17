import Vue from "vue";
import VueRouter from "vue-router";
import Store from "../store";
import Sign_in from "../views/Sign_in.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Sign_in",
        component: Sign_in,
        meta: {
            title: "Sign in",
            requiresAuth: false
        }
    },
    {
        path: "/sign_up",
        name: "Sign_up",
        component: () => import("../views/Sign_up.vue"),
        meta: {
            title: "Sign up",
            requiresAuth: false
        }
    },
    {
        path: "/add_secretcode",
        name: "Add_secretcode",
        component: () => import("../views/Add_secretcode.vue"),
        meta: {
            title: "Add secretcode",
            requiresAuth: true
        }
    },
    {
        path: "/secretcodes",
        name: "Secretcodes",
        component: () => import("../views/Secretcodes.vue"),
        meta: {
            title: "All secretcode",
            requiresAuth: true
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
    if (to.meta.requiresAuth && !Store.getters.IS_AUTHENTICATED) {
        next("/");
    } else {
        document.title = to.meta.title;
        next();
    }
});

export default router;
