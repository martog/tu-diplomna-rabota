<template>
    <div role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Delete controller
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
                <h6>
                    Are you sure you want to delete controller '{{
                        this.controllerName
                    }}' ?
                </h6>
                <div class="alert alert-danger" v-if="showError" role="alert">
                    {{ errorMsg }}
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-outline-danger"
                    :disabled="this.loading"
                    @click="deleteController()"
                >
                    Delete
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
                    @click="closeModal(false)"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        controllerName: String,
        controllerId: Number
    },
    data() {
        return {
            loading: false,
            showError: false,
            errorMsg: ""
        };
    },
    methods: {
        closeModal(deleted = false) {
            this.$emit("close", { controllerDeleted: deleted });
        },
        deleteController() {
            this.loading = true;

            const url = `controller/${this.controllerId}/remove`;
            const deleteControllerRequest = axios.delete(url);

            deleteControllerRequest
                .then(response => {
                    this.loading = false;
                    this.closeModal(true);
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                    this.errorMsg =
                        "Error deleting controller. Please contact administrator.";
                    this.showError = true;
                });
        }
    }
};
</script>

<style lang="scss" scoped></style>
