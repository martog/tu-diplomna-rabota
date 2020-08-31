<template>
    <div>
        <h2>Controllers</h2>
        <controller-list
            @changedSelectedController="onSelectedControllerChange"
            :itemsPerPage="3"
        ></controller-list>
        <h2 class="mt-5">Devices</h2>
        <device-list :controller-id="this.selectedControllerId"></device-list>
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
            selectedControllerId: null
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
    methods: {
        onSelectedControllerChange(controllerId) {
            console.log("selectedControllerChanged");
            this.selectedControllerId = controllerId;
            console.log(this.selectedControllerId);
        }
    },
    created() {}
};
</script>
