import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    created() {
        this.$store.dispatch("LOAD_SECRETCODES_FROM_BACKEND");
    },
    vuetify,
    render: h => h(App)
}).$mount("#app");
