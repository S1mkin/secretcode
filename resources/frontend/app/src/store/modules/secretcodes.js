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
        DELETE_SECRETCODE_BY_ID(state, id) {
            let secretcode_id = state.secretcodes.findIndex(element => {
                return element.id == id ? true : false;
            });
            state.secretcodes.splice(secretcode_id, 1);
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
        ADD_SECRETCODES_TO_BACKEND({ commit }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/add", {
                        name: data.name,
                        text: data.text
                    })
                    .then(response => {
                        commit("ADD_SECRETCODES", response.data);
                        resolve(
                            "Secret code with ID " +
                                response.data.id +
                                " has been added"
                        );
                    })
                    .catch(error => {
                        /*
                        let response_error =
                            error.response.data.errors ||
                            error.response.data.message ||
                            error.message;
                        commit("ADD_ERROR", response_error);*/
                        reject(JSON.stringify(error));
                    });
            });
        },
        DELETE_SECRETCODES_FROM_BACKEND({ commit }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/delete", {
                        id: data.id
                    })
                    .then(() => {
                        commit("DELETE_SECRETCODE_BY_ID", data.id);
                        resolve(
                            "Secret code with ID " +
                                data.id +
                                " has been deleted"
                        );
                    })
                    .catch(error => {
                        reject(JSON.stringify(error));
                    });
            });
        },
        FILTER_SECRETCODES({ commit }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/filter", {
                        condition: data.condition,
                        code: data.code
                    })
                    .then(response => {
                        commit("CLEAR_SECRETCODES");
                        commit("ADD_SECRETCODES", response.data);
                        resolve("Filter is success");
                    })
                    .catch(error => {
                        reject(JSON.stringify(error));
                    });
            });
        }
    }
};
