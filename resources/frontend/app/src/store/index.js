import Vue from "vue";
import Vuex from "vuex";
import Secretcodes from "./modules/secretcodes";
import Auth from "./modules/auth";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        Secretcodes,
        Auth
    }
});
