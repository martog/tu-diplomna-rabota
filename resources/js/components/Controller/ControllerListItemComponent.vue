<template>
    <div class="card w-100" :class="getBorderClass">
        <div class="card-body">
            <div class="row card-title mb-0">
                <div class="col">
                    <h5>
                        {{ this.name }}
                        <span
                            @click="showEditModal()"
                            class="material-icons custom-icon"
                            v-tooltip:bottom="'Edit'"
                        >
                            edit
                        </span>
                        <span
                            @click="showDeleteDialog()"
                            class="material-icons custom-icon"
                            v-tooltip:bottom="'Delete'"
                        >
                            delete
                        </span>
                    </h5>
                </div>
                <div class="col text-right">
                    <span class="align-text-top badge" :class="getBadgeClass">{{
                        this.status
                    }}</span>
                </div>
            </div>

            <div class="card-subtitle">
                <span class="text-muted"
                    >Last communication: {{ this.lastCommunication }}</span
                >
            </div>

            <div class="card-text"></div>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col text-left">
                    Active devices
                </div>
                <div class="col text-right">
                    {{ this.devices.On }}/{{
                        this.devices.On + this.devices.Off
                    }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddEditModal from "./AddEditControllerModal";
import DeleteDialog from "./DeleteControllerDialog";

export default {
    props: {
        id: Number,
        name: String,
        status: String,
        lastCommunication: String,
        devices: Object,
        selected: Boolean
    },
    directives: {
        tooltip: function(el, binding) {
            $(el).tooltip({
                title: binding.value,
                placement: binding.arg,
                trigger: "hover"
            });
        }
    },
    computed: {
        getBadgeClass() {
            if (this.status === "Online") {
                return ["badge-success"];
            }

            return ["badge-danger"];
        },
        getBorderClass() {
            if (this.selected) {
                return ["border-primary"];
            }
            return [];
        }
    },
    methods: {
        showEditModal() {
            this.$modal.show(
                AddEditModal,
                {
                    type: "edit",
                    controllerId: this.id,
                    controllerNameProp: this.name
                },
                { draggable: false, height: "auto", width: "400px" },
                {
                    "before-close": event => {
                        console.log(event);
                        if (event.params === undefined) {
                            this.$emit("controllerUpdated", null);
                            return;
                        }

                        this.$emit("controllerUpdated", event.params);
                    }
                }
            );
        },
        showDeleteDialog() {
            console.log("here");
            this.$modal.show(
                DeleteDialog,
                { controllerName: this.name, controllerId: this.id },
                { draggable: false, height: "auto", width: "600px" },
                {
                    "before-close": event => {
                        console.log(event);
                        if (event.params === undefined) {
                            return;
                        }
                        this.$emit("controllerUpdated", event.params);
                    }
                }
            );
        }
    }
};
</script>

<style lang="scss" scoped>
.custom-icon {
    font-size: 18px;
    color: #cab7b7;
}
.custom-icon:hover {
    cursor: pointer !important;
    color: #343a40;
}
</style>
