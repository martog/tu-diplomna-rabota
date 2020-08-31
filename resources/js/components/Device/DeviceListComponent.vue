<template>
    <div v-if="loading">
        Loding...
    </div>
    <div v-else>
        <ul class="list-group list-group-flush">
            <li
                class="list-group-item custom-item text-center"
                v-if="!controllerId"
            >
                <h3>Please select controller.</h3>
            </li>
            <div v-if="controllerId">
                <li
                    class="list-group-item custom-item"
                    v-for="device in devices"
                    :key="device.id"
                >
                    <device-list-item
                        :device-id="device.id"
                        :device-name="device.name"
                        :device-status="device.status"
                        :device-last-updated-prop="device.last_updated"
                        :controller-name="device.controller_name"
                    ></device-list-item>
                </li>
            </div>
        </ul>
    </div>
</template>

<script>
import DeviceListItem from "./DeviceListItemComponent";
export default {
    components: {
        DeviceListItem
    },
    props: {
        controllerId: Number
    },
    watch: {
        controllerId: function(newVal, oldVal) {
            this.retrieveDevices(newVal);
        }
    },
    data() {
        return {
            devices: null,
            loading: false
        };
    },
    methods: {
        retrieveDevices(controllerId) {
            this.loading = true;
            if (!controllerId) {
                this.loading = false;
                return;
            }

            const url = "/controller/" + this.controllerId + "/devices";
            const devicesRequest = axios.get(url);

            devicesRequest.then(response => {
                this.devices = response.data;
                this.loading = false;
            });
        }
    },
    created() {
        this.retrieveDevices(this.controllerId);
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
