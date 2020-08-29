<template>
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <div class="justify-content-center">
                <form action="#" @submit.prevent="login">
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input
                            type="text"
                            id="inputUsername"
                            class="form-control"
                            placeholder="Enter username"
                            v-model="username"
                        />
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="inputPassword"
                            placeholder="Password"
                            v-model="password"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                    <span
                        >You don't have an account?
                        <a href="register">Sign up</a></span
                    >
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "login",
    data() {
        return {
            username: "",
            password: ""
        };
    },
    methods: {
        login() {
            console.log("here");
            this.$store
                .dispatch("retrieveToken", {
                    username: this.username,
                    password: this.password
                })
                .then(response => {
                    this.$router.push({ name: "home" });
                });
        }
    },
    created() {}
};
</script>

<style lang="scss" scoped></style>
