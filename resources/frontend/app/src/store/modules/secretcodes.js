import axios from "axios";
export default {
    state: {
        secretcodes: [],
        error: null
    },
    getters: {
        GET_SECRETCODES(state) {
            return state.secretcodes;
        }
    },
    mutations: {
        CLEAR_SECRETCODES(state) {
            state.secretcodes.splice(0, state.secretcodes.length);
        },
        ADD_SECRETCODES(state, data) {
            state.secretcodes.push(...data);
        },
        ADD_ERROR(state, data) {
            state.error = data;
        }
    },
    actions: {
        LOAD_SECRETCODES_FROM_BACKEND({ commit }) {
            axios
                .get("/api/secretcode/get")
                .then(response => {
                    commit("CLEAR_SECRETCODES");
                    commit("ADD_SECRETCODES", response.data);
                })
                .catch(error => {
                    commit(
                        "ADD_ERROR",
                        error.response.data.errors ||
                            error.response.data.message ||
                            error.message
                    );
                });
        },
        ADD_SECRETCODES_TO_BACKEND({ commit, dispatch }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/add", {
                        name: data.name,
                        code: data.code
                    })
                    .then(() => {
                        dispatch("LOAD_SECRETCODES_FROM_BACKEND");
                        resolve("Secret code has been added");
                    })
                    .catch(error => {
                        let response_error =
                            error.response.data.errors ||
                            error.response.data.message ||
                            error.message;
                        commit("ADD_ERROR", response_error);
                        reject(response_error);
                    });
            });
        },
        DELETE_SECRETCODES_FROM_BACKEND({ commit, dispatch }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/delete", {
                        id: data.id
                    })
                    .then(() => {
                        dispatch("LOAD_SECRETCODES_FROM_BACKEND");
                        resolve(
                            "Secret code with ID ${data.id} has been deleted"
                        );
                    })
                    .catch(error => {
                        let response_error =
                            error.response.data.errors ||
                            error.response.data.message ||
                            error.message;
                        commit("ADD_ERROR", response_error);
                        reject(response_error);
                    });
            });
        }
    }
};
