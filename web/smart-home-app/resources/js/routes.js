import VueRouter from "vue-router";
import HomeComponent from "./components/HomeComponent";
import Example2 from "./components/Example2";

const routes = [
    {
        path: "/",
        component: HomeComponent,
        name: "home"
    },
    {
        path: "/second",
        component: Example2,
        name: "second"
    }
];

const router = new VueRouter({
    routes, // short from routes: routes
    mode: "history"
});

export default router;
