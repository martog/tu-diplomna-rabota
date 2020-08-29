import axios from "axios";

axios.defaults.baseURL = "http://localhost:8000/api";

export default {
    state: {
        token: localStorage.getItem("access_token") || null
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        }
    },
    mutations: {
        retrieveToken(state, token) {
            state.token = token;
        },
        destroyToken(state) {
            state.token = null;
        }
    },
    actions: {
        retrieveToken(context, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/auth/login", {
                        username: credentials.username,
                        password: credentials.password
                    })
                    .then(response => {
                        const token = response.data.access_token;

                        localStorage.setItem("access_token", token);
                        context.commit("retrieveToken", token);
                        console.log(response);
                        resolve(response);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        }
    }
};
