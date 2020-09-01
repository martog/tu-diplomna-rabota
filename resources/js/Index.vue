<template>
    <div>
        <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
            <a class="navbar-brand" :class="!loggedIn ? ['mr-auto'] : []"
                >SmartHome</a
            >

            <div v-if="loggedIn" class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/home"
                            >Home <span class="sr-only"></span
                        ></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="navbarDropdown"
                            role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            {{ currentUser }}
                        </a>
                        <div
                            class="dropdown-menu"
                            aria-labelledby="navbarDropdown"
                        >
                            <a class="dropdown-item" href="#">Edit profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout">Log out</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else>
                <ul class="navbar-nav">
                    <li
                        class="ml-auto nav-item"
                        :class="{ active: currentRouteName === 'login' }"
                    >
                        <a class="nav-link" href="/login" role="button">
                            Log in
                        </a>
                    </li>
                    <li
                        class="ml-auto nav-item"
                        :class="{ active: currentRouteName === 'register' }"
                    >
                        <a class="nav-link" href="/register" role="button">
                            Register
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div
            :class="
                loggedIn
                    ? ['container', 'mt-6', 'mb-4', 'pr-10', 'pl-10']
                    : ['container-fluid', 'pr-10', 'pl-10']
            "
        >
            <v-dialog />
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn;
        },
        currentUser() {
            const userData = this.$store.getters.userData;
            if (userData) {
                return userData.first_name + " " + userData.last_name;
            }
        },
        currentRouteName() {
            return this.$route.name;
        }
    }
};
</script>

<style lang="scss" scoped></style>
