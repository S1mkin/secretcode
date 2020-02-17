import axios from "axios";
export default {
    state: {
        secretcodes: [],
        filter: false
    },
    getters: {
        GET_SECRETCODES: state => state.secretcodes,
        GET_FILTER: state => state.filter
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
        SET_FILTER: (state, filter) => (state.filter = filter)
    },
    actions: {
        async LOAD_SECRETCODES_FROM_BACKEND({ getters, commit }) {
            try {
                let response = await axios.post("/api/secretcode/get", {
                    api_token: getters.GET_API_TOKEN
                });
                commit("SET_FILTER", false);
                commit("CLEAR_SECRETCODES");
                commit("ADD_SECRETCODES", response.data);
            } catch {
                throw new Error("Error loading data");
            }
        },
        ADD_SECRETCODES_TO_BACKEND({ getters, commit, dispatch }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/add", {
                        name: data.name,
                        text: data.text,
                        api_token: getters.GET_API_TOKEN
                    })
                    .then(response => {
                        if (getters.GET_FILTER) {
                            dispatch("LOAD_SECRETCODES_FROM_BACKEND");
                        }
                        commit("ADD_SECRETCODES", [response.data]);
                        resolve(
                            "Secret code with ID " +
                                response.data.id +
                                " has been added"
                        );
                    })
                    .catch(error => {
                        reject(error.response.data.errors);
                    });
            });
        },
        DELETE_SECRETCODES_FROM_BACKEND({ getters, commit }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/delete", {
                        id: data.id,
                        api_token: getters.GET_API_TOKEN
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
        FILTER_SECRETCODES({ getters, commit }, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/secretcode/filter", {
                        condition: data.condition,
                        code: data.code,
                        api_token: getters.GET_API_TOKEN
                    })
                    .then(response => {
                        commit(
                            "SET_FILTER",
                            `Code ${data.condition} ${data.code}`
                        );
                        commit("CLEAR_SECRETCODES");
                        commit("ADD_SECRETCODES", response.data);
                        resolve(getters.GET_FILTER);
                    })
                    .catch(error => {
                        reject(error.response.data.errors);
                    });
            });
        }
    }
};
