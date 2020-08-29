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
        const devicesRequest = axios.get("/controller/36/devices");

        devicesRequest.then(response => {
            this.devices = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    status: item.status,
                    controllerName: item.controller_id.toString()
                };
            });
            console.log(this.devices);
            this.loading = false;
        });

        // setTimeout(() => {
        //     this.devices = [
        //         {
        //             id: 1,
        //             name: "Device 1 test",
        //             status: "On",
        //             controllerName: "Controller 1"
        //         },
        //         {
        //             id: 2,
        //             name: "Device 2",
        //             status: "Off",
        //             controllerName: "Controller 2"
        //         }
        //     ];
        //     this.loading = false;
        // }, 1000);
    },
    mounted() {}
};
</script>

<style lang="scss" scoped></style>
