<template>
    <div>
        <div class="row">
            <div class="col-xs-2">
                <h2>Controllers</h2>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-outline-dark">
                    <h6 class="mb-0 mt-0">Add</h6>
                </button>
            </div>
        </div>
        <controller-list
            @changedSelectedController="onSelectedControllerChange"
            :device-status-change="this.deviceStatusChange"
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
export default {
    components: {
        ControllerList,
        DeviceList
    },
    data() {
        return {
            selectedControllerId: null,
            deviceStatusChange: null
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
            this.deviceStatusChange = deviceStatus;
        }
    },
    created() {}
};
</script>
