<template>
    <div>
        <div class="row controllers-title">
            <div class="col-xs-2">
                <h2>Controllers</h2>
            </div>
            <div class="col-sm-2">
                <button
                    type="button"
                    class="btn btn-outline-dark"
                    @click="showAddControllerModal()"
                >
                    <h6 class="mb-0 mt-0">Add</h6>
                </button>
            </div>
        </div>
        <controller-list
            @changedSelectedController="onSelectedControllerChange"
            :reload-controllers="this.reloadControllers"
            :itemsPerPage="3"
        ></controller-list>
        <h2 class="mt-5">Devices</h2>
        <device-list
            @changedDeviceStatus="onDeviceStatusChange"
            :controller-id="this.selectedControllerId"
        ></device-list>
    </div>
</template>

<script>
import ControllerList from "./Controller/ControllerListComponent";
import DeviceList from "./Device/DeviceListComponent";
import AddEditModal from "./Controller/AddEditControllerModal";

export default {
    components: {
        ControllerList,
        DeviceList
    },
    data() {
        return {
            selectedControllerId: null,
            reloadControllers: null
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
    methods: {
        onSelectedControllerChange(controllerId) {
            this.selectedControllerId = controllerId;
        },
        onDeviceStatusChange(deviceStatus) {
            this.reloadControllers = deviceStatus;
        },
        showAddControllerModal() {
            this.$modal.show(
                AddEditModal,
                { type: "add" },
                { draggable: false, height: "auto", width: "400px" }
            );
        }
    },
    created() {}
};
</script>

<style lang="scss" scoped>
.controllers-title {
    margin-left: auto;
}
</style>
