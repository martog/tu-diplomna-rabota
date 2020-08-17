<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-2">
                <h5>Device:</h5>
                <p>{{ deviceName }}</p>
            </div>
            <div class="col-xl-4 text-right">
                <h5>Controller:</h5>
                <p>{{ controllerName }}</p>
            </div>
            <div class="col-xl-6 custom-control custom-switch">
                <h5>Status:</h5>
                <input
                    type="checkbox"
                    class="custom-control-input"
                    :checked="deviceActive"
                    :id="deviceId"
                    :disabled="deviceStatusLoading"
                    @change="onDeviceStatusChange()"
                />
                <label class="custom-control-label" :for="deviceId">{{
                    getDeviceStatus()
                }}</label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceId: Number,
        deviceName: String,
        deviceStatus: String,
        controllerName: String
    },
    data() {
        return {
            deviceActive: this.isDeviceActive(),
            deviceStatusLoading: false
        };
    },
    mounted() {},
    methods: {
        isDeviceActive() {
            return this.deviceStatus === "On" ? true : false;
        },
        getDeviceStatus(data) {
            return this.deviceActive ? "On" : "Off";
        },
        onDeviceStatusChange() {
            this.deviceStatusLoading = true;
            setTimeout(() => {
                this.deviceActive = !this.deviceActive;
                this.deviceStatusLoading = false;
            }, 2000);
        }
    }
};
</script>

<style lang="scss" scoped>
label {
    position: relative;
    padding-left: 10.5em;
}
</style>