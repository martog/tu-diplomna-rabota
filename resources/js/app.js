require("./bootstrap");

import router from "./routes";
import VueRouter from "vue-router";
import Index from "./Index";
import Vue from "vue";
import Vuex from "vuex";
import storeDefinition from "./store/store";

window.Vue = require("vue");

Vue.use(VueRouter);
Vue.use(Vuex);

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.loggedIn) {
            next({
                name: "login"
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        if (store.getters.loggedIn) {
            next({
                name: "home"
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

const store = new Vuex.Store(storeDefinition);
const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        index: Index
    }
});
