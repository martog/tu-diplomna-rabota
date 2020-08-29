<template>
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <div class="justify-content-center">
                <form>
                    <div class="form-group">
                        <label for="inputFirstName">First Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="inputFirstName"
                            placeholder="Enter first name"
                        />
                    </div>
                    <div class="form-group">
                        <label for="inputLastName">Last Name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="inputLastName"
                            placeholder="Enter last name"
                        />
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email address</label>
                        <input
                            type="email"
                            class="form-control"
                            id="inputEmail"
                            placeholder="Enter email"
                        />
                    </div>
                    <div class="form-group">
                        <label for="inputUsername">Username</label>
                        <input
                            type="text"
                            id="inputUsername"
                            class="form-control"
                            placeholder="Enter username"
                        />
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="inputPassword"
                            placeholder="Password"
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
            </div>
        </div>
    </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped></style>
