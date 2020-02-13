import Vue from "vue";
import VueRouter from "vue-router";
import Store from "../store";
import AddSecretcode from "../views/Add_secretcode.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Add_secretcode",
        component: AddSecretcode,
        meta: {
            title: "Add secretcode",
            requiresAuth: true
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
        next("/sign_in");
    } else {
        document.title = to.meta.title;
        next();
    }
});

export default router;
