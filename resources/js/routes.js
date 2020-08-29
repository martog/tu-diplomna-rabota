import VueRouter from "vue-router";
import HomeComponent from "./components/HomeComponent";
import DeviceListComponent from "./components/Device/DeviceListComponent";
import LoginComponent from "./components/Auth/LoginComponent";
import LogoutComponent from "./components/Auth/LogoutComponent";
import RegistrationComponent from "./components/Auth/RegistrationComponent";

const routes = [
    {
        path: "/home",
        component: HomeComponent,
        name: "home",
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/device-list",
        component: DeviceListComponent,
        name: "device-list",
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/login",
        component: LoginComponent,
        name: "login",
        meta: {
            requiresVisitor: true
        }
    },
    {
        path: "/logout",
        component: LogoutComponent,
        name: "logout",
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/register",
        component: RegistrationComponent,
        name: "register",
        meta: {
            requiresVisitor: true
        }
    }
];

const router = new VueRouter({
    routes, // short from routes: routes
    mode: "history"
});

export default router;
