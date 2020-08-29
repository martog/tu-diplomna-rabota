<template>
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <div class="justify-content-center">
                <form @submit.prevent="register">
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <div class="form-group">
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
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
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
                    this.showError = true;
                    this.errorMsg =
                        "Server error. Please contact administrator.";
                });
        }
    }
};
</script>

<style lang="scss" scoped></style>
