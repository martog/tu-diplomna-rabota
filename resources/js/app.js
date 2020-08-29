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

const store = new Vuex.Store(storeDefinition);
const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        index: Index
    }
});
