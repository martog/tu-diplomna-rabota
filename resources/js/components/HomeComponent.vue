<template>
    <div class="container">
        <div class="justify-content-center">
            <device-list></device-list>
        </div>
    </div>
</template>

<script>
import DeviceList from "./Device/DeviceListComponent";
export default {
    components: {
        DeviceList
    },

    mounted() {
        console.log("Component mounted.");
    }
};
</script>
