import axios from "axios";

axios.defaults.baseURL = "http://localhost:8000/api";

axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem("access_token");
        if (token) {
            config.headers["Authorization"] = "Bearer " + token;
        }
        return config;
    },
    error => {
        Promise.reject(error);
    }
);

export default {
    state: {
        token: localStorage.getItem("access_token") || null,
        user: JSON.parse(localStorage.getItem("user") || null),
        controllersData: JSON.parse(
            localStorage.getItem("controllers_data") || null
        )
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
        userData(state) {
            return state.user;
        },
        controllersData(state) {
            if (!state.controllersData) {
                return {
                    selectedPage: 1,
                    selectedControllerId: null
                };
            }

            return state.controllersData;
        }
    },
    mutations: {
        retrieveUser(state, user) {
            state.user = user;
        },
        retrieveToken(state, token) {
            state.token = token;
        },
        setControllersData(state, controllersData) {
            state.controllersData = controllersData;
            localStorage.setItem(
                "controllers_data",
                JSON.stringify(controllersData)
            );
        },
        destroyToken(state) {
            state.token = null;
        },
        destroyUser(state) {
            state.user = null;
        },
        destroyControllersData(state) {
            state.controllersData = null;
        }
    },
    actions: {
        register(context, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/user/register", credentials)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        },
        retrieveToken(context, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/auth/login", {
                        username: credentials.username,
                        password: credentials.password
                    })
                    .then(response => {
                        const token = response.data.access_token;
                        const user = response.data.user;

                        localStorage.setItem("access_token", token);
                        localStorage.setItem("user", JSON.stringify(user));

                        context.commit("retrieveToken", token);
                        context.commit("retrieveUser", user);

                        axios.defaults.headers.common["Authorization"] =
                            "Bearer " + context.state.token;
                        resolve(response);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        },
        logout(context) {
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios
                        .post("/auth/logout")
                        .then(response => {
                            localStorage.removeItem("access_token");
                            localStorage.removeItem("user");
                            localStorage.removeItem("controllers_data");

                            context.commit("destroyToken");
                            context.commit("destroyUser");
                            context.commit("destroyControllersData");

                            console.log(response);
                            resolve(response);
                        })
                        .catch(error => {
                            localStorage.removeItem("access_token");
                            localStorage.removeItem("user");
                            localStorage.removeItem("controllers_data");

                            context.commit("destroyToken");
                            context.commit("destroyUser");
                            context.commit("destroyControllersData");

                            console.log(error);
                            reject(error);
                        });
                });
            }
        }
    }
};
