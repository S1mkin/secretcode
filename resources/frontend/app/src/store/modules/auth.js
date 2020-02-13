import axios from "axios";
export default {
    state: {
        name: null,
        token: localStorage.getItem("api_token") || "",
        status
    },
    getters: {
        IS_AUTHENTICATED: state => !!state.token,
        AUTH_STATUS: state => state.status
    },
    mutations: {
        AUTH_REQUEST(state) {
            state.status = "loading";
        },
        AUTH_SUCCESS(state, token) {
            state.status = "success";
            state.token = token;
        },
        AUTH_LOGOUT(state) {
            state.token = "";
        },
        AUTH_ERROR(state) {
            state.status = "error";
        }
    },
    actions: {
        AUTH_REQUEST({ commit, dispatch }, user) {
            return new Promise((resolve, reject) => {
                // The Promise used for router redirect in login
                commit("AUTH_REQUEST");
                axios
                    .post("/api/auth/login", {
                        email: user.email,
                        password: user.password
                    })
                    .then(response => {
                        const token = response.data.user.api_token;
                        localStorage.setItem("api_token", token);
                        commit("AUTH_SUCCESS", token);
                        dispatch("LOAD_SECRETCODES_FROM_BACKEND");
                        resolve(response);
                    })
                    .catch(error => {
                        commit("AUTH_ERROR", error);
                        localStorage.removeItem("api_token"); // if the request fails, remove any possible user token if possible
                        reject(error);
                    });
            });
        },
        AUTH_LOGOUT({ commit }) {
            return new Promise(resolve => {
                commit("AUTH_LOGOUT");
                localStorage.removeItem("api_token"); // clear your user's token from localstorage
                resolve("Logout success!");
            });
        },
        REGISTER_REQUEST({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("AUTH_REQUEST");
                axios
                    .post("/api/auth/register", {
                        name: user.name,
                        email: user.email,
                        password: user.password,
                        password_confirmation: user.password
                    })
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error.message);
                    });
            });
        }
    }
};
