<template>
    <div class="row full-screen align-items-center justify-content-center">
        <div class="col-6 col-border-right text-center">
            <img src="../../../img/index.png" class="img-fluid" />
        </div>
        <div class="col-3 pl-5">
            <div>
                <h3>Register</h3>
                <form @submit.prevent="register">
                    <div class="row">
                        <div class="form-group col">
                            <label for="inputFirstName">First Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="inputFirstName"
                                placeholder="Enter first name"
                                v-model="firstName"
                                required
                            />
                        </div>
                        <div class="form-group col">
                            <label for="inputLastName">Last Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="inputLastName"
                                placeholder="Enter last name"
                                v-model="lastName"
                                required
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="inputUsername">Username</label>
                            <input
                                type="text"
                                id="inputUsername"
                                class="form-control"
                                placeholder="Enter username"
                                v-model="username"
                                required
                            />
                        </div>
                        <div class="form-group col">
                            <label for="inputEmail">Email address</label>
                            <input
                                type="email"
                                class="form-control"
                                id="inputEmail"
                                placeholder="Enter email"
                                v-model="email"
                                required
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="inputPassword">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="inputPassword"
                                placeholder="Password"
                                v-model="password"
                                required
                            />
                        </div>
                        <div class="form-group col">
                            <label for="inputPasswordConfirm"
                                >Confirm Password</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                id="inputPasswordConfirm"
                                placeholder="Password"
                                v-model="confirmPassword"
                                required
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="inputPhoneNumber">Phone number</label>
                            <input
                                type="tel"
                                class="form-control"
                                id="inputPhoneNumber"
                                placeholder="Enter phone number"
                                v-model="phoneNumber"
                                required
                            />
                        </div>
                        <div class="col padding-top-31">
                            <button
                                id="submitBtn"
                                type="submit"
                                class="btn w-100 btn-primary"
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                    <span
                        >You already have an account?
                        <a href="login">Sign in</a></span
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
</template>

<script>
export default {
    data() {
        return {
            firstName: "",
            lastName: "",
            username: "",
            email: "",
            password: "",
            confirmPassword: "",
            phoneNumber: "",
            showError: false,
            errorMsg: "Server error. Please contact administrator."
        };
    },
    methods: {
        register() {
            if (this.password !== this.confirmPassword) {
                this.showError = true;
                this.errorMsg = "The passwords does not match.";
                return;
            }
            this.$store
                .dispatch("register", {
                    username: this.username,
                    password: this.password,
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    phone_number: this.phoneNumber
                })
                .then(response => {
                    this.showError = false;
                    this.$router.push({ name: "login" });
                })
                .catch(error => {
                    this.errorMsg =
                        "Server error. Please contact administrator.";
                    if (error.response.status === 409) {
                        this.errorMsg = error.response.data;
                    }
                    this.showError = true;
                });
        }
    }
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
.padding-top-31 {
    padding-top: 31px;
}
</style>
