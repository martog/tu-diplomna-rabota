<template>
    <div>
        <div v-if="loading">
            Loding...
        </div>
        <div v-else>
            <device-list-item
                v-for="device in devices"
                :key="device.key"
                :device-name="device.name"
                :device-status="device.status"
                :controller-name="device.controllerName"
            ></device-list-item>
        </div>
    </div>
</template>

<script>
import DeviceListItem from "./DeviceListItemComponent";
export default {
    components: {
        DeviceListItem
    },
    data() {
        return {
            devices: null,
            loading: false
        };
    },
    created() {
        this.loading = true;

        setTimeout(() => {
            this.devices = [
                {
                    id: 1,
                    name: "Device 1 test",
                    status: true,
                    controllerName: "Controller 1"
                },
                {
                    id: 2,
                    name: "Device 2",
                    status: false,
                    controllerName: "Controller 2"
                }
            ];
            this.loading = false;
        }, 3000);
    },
    mounted() {}
};
</script>

<style lang="scss" scoped></style>
