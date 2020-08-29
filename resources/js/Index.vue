<template>
    <div>
        <nav
            class="navbar bg-white border-bottom navbar-light"
            :hidden="!loggedIn"
        >
            <router-link class="navbar-brand mr-auto" :to="{ name: 'home' }"
                >SmartHome</router-link
            >
        </nav>

        <div class="container mt-4 mb-4 pr-4 pl-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        loggedIn() {
            return this.$store.getters.loggedIn;
        }
    }
};
</script>

<style lang="scss" scoped></style>
