import VueRouter from "vue-router";
import HomeComponent from "./components/HomeComponent";
import DeviceListComponent from "./components/Device/DeviceListComponent";
import Example2 from "./components/Example2";

const routes = [
    {
        path: "/",
        component: HomeComponent,
        name: "home"
    },
    {
        path: "/device-list",
        component: DeviceListComponent,
        name: "device-list"
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
