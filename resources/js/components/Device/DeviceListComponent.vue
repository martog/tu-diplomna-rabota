<template>
    <div v-if="loading">
        Loding...
    </div>
    <div v-else>
        <ul class="list-group list-group-flush">
            <li
                class="list-group-item custom-item"
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
    methods: {},
    created() {
        this.loading = true;
        const url = "/controller/" + 36 + "/devices";
        const devicesRequest = axios.get(url);

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
    },
    mounted() {}
};
</script>

<style lang="scss" scoped>
.custom-item {
    background-color: rgba(0, 0, 0, 0.03);
    border: 1px solid rgb(255 255 255);
}
</style>
