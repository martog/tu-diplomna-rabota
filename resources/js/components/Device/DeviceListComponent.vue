<template>
    <div v-if="loading">
        Loding...
    </div>
    <div v-else>
        <ul class="list-group list-group-flush">
            <li
                class="list-group-item"
                v-for="device in devices"
                v-bind:key="device.id"
            >
                <device-list-item
                    :device-id="device.id"
                    :device-name="device.name"
                    :device-status="device.status"
                    :controller-name="device.controllerName"
                ></device-list-item>
            </li>
        </ul>
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
                    status: "On",
                    controllerName: "Controller 1"
                },
                {
                    id: 2,
                    name: "Device 2",
                    status: "Off",
                    controllerName: "Controller 2"
                }
            ];
            this.loading = false;
        }, 1000);
    },
    mounted() {}
};
</script>

<style lang="scss" scoped></style>
