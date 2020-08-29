<template>
    <div style="border:1px solid black;">
        <h1>Home</h1>
        <devices-list></devices-list>
    </div>
</template>

<script>
import DevicesList from "./Device/DeviceListComponent";
export default {
    components: {
        DevicesList
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {}
};
</script>
