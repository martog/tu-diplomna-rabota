<template>
    <div>
        <div class="row full-screen align-items-center justify-content-center">
            <div class="col-6 col-border-right text-center">
                <img
                    src="../../../img/smart-home-index.png"
                    class="img-fluid"
                />
            </div>
            <div class="col-3 pl-5">
                <div>
                    <h3>Log in</h3>
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
                    <div
                        :hidden="!showError"
                        class="alert alert-danger mt-2"
                        role="alert"
                    >
                        {{ errorMsg }}
                    </div>
                </div>
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
            password: "",
            showError: false,
            errorMsg: "Server error. Please contact administrator."
        };
    },
    methods: {
        login() {
            this.$store
                .dispatch("retrieveToken", {
                    username: this.username,
                    password: this.password
                })
                .then(response => {
                    this.showError = false;
                    this.$router.push({ name: "home" });
                })
                .catch(error => {
                    if (error.response.status) {
                        this.errorMsg = "Invalid credentials! Try again.";
                        this.showError = true;
                    }
                });
        }
    },
    created() {}
};
</script>

<style lang="scss" scoped>
.full-screen {
    height: 100vh;
}
.col-border-right {
    border-right: 2px solid #212529;
    border-radius: 1px;
}
.img-fluid {
    max-width: 100%;
    height: auto;
}
</style>
