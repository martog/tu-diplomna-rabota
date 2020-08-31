<template>
    <div>
        <h2>Controllers</h2>
        <controller-list :itemsPerPage="3"></controller-list>
        <h2 class="mt-5">Devices</h2>
        <device-list></device-list>
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
    mounted() {
        console.log("Component mounted.");
    },
    created() {}
};
</script>
