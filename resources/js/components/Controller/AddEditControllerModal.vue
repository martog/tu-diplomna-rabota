<template>
    <div role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{
                        type === "add"
                            ? "Add new controller"
                            : "Edit controller"
                    }}
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                    @click="closeModal()"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="addEditForm" @submit.prevent="save">
                    <div class="form-group">
                        <label for="inputControllerName">Name</label>
                        <input
                            type="text"
                            id="inputControllerName"
                            class="form-control"
                            :placeholder="
                                type === 'add'
                                    ? 'Enter controller\'s name'
                                    : 'Enter new name'
                            "
                            minlength="3"
                            v-model="controllerName"
                            required
                        />
                    </div>
                    <div class="form-group" v-if="type === 'add'">
                        <label for="inputControllerSerial">Serial</label>
                        <input
                            type="text"
                            id="inputControllerSerial"
                            class="form-control"
                            placeholder="Enter controller's serial number"
                            minlength="16"
                            maxlength="16"
                            v-model="controllerSerial"
                        />
                    </div>
                </form>
                <div class="alert alert-danger" v-if="showError" role="alert">
                    {{ errorMsg }}
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="submit"
                    class="btn btn-outline-primary"
                    :disabled="this.loading"
                    @click="save()"
                >
                    Save changes
                    <div
                        v-if="this.loading"
                        class="ml-1 spinner-border spinner-border-sm"
                        role="status"
                    >
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                    @click="closeModal()"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        type: String
    },
    data() {
        return {
            controllerName: "",
            controllerSerial: "",
            errorMsg: "",
            showError: false,
            loading: false
        };
    },
    methods: {
        closeModal() {
            this.$emit("close");
        },
        addController() {
            this.loading = true;

            const url = "controller/add";
            const addControllerRequest = axios.post(url, {
                controller_serial: this.controllerSerial,
                controller_name: this.controllerName
            });

            addControllerRequest
                .then(response => {
                    this.loading = false;
                    // emit to reload controllers
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                    this.errorMsg =
                        "Error adding controller. Please contact administrator.";

                    if (error.response.status === 408) {
                        this.errorMsg = "Cannot connect to controller.";
                    }
                    this.showError = true;
                });
        },
        editController() {},
        save() {
            this.showError = false;
            if (
                !this.controllerName.length ||
                (this.controllerName.length && this.controllerName.length < 3)
            ) {
                this.showError = true;
                this.errorMsg =
                    "The controller name field must contain at least 3 characters.";
                return;
            }

            if (
                this.type != "add" &&
                (!this.controllerSerial.length ||
                    (this.controllerSerial.length &&
                        this.controllerSerial.length !== 16))
            ) {
                this.showError = true;
                this.errorMsg =
                    "Тhe controller serial must be 16 characters long.";
                return;
            }

            if (this.type === "add") {
                this.addController();
            } else {
                this.editController();
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
