import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import axios from "axios";

Vue.config.productionTip = false;

const token = localStorage.getItem("api_token");
if (token) {
    axios.defaults.headers.common["Authorization"] = token;
}

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount("#app");
