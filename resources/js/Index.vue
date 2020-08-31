<template>
    <div>
        <nav
            :hidden="!loggedIn || !currentUser"
            class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark"
        >
            <a class="navbar-brand">SmartHome</a>

            <div class="collapse navbar-collapse">
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
        </nav>

        <div class="container-fluid mt-6 mb-4 pr-10 pl-10">
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
        }
    }
};
</script>

<style lang="scss" scoped></style>
