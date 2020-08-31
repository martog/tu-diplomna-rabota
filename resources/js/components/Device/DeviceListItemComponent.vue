<template>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <h5>Device:</h5>
                <p>{{ deviceName }}</p>
            </div>
            <div class="col-4">
                <h5>Controller:</h5>
                <p>{{ controllerName }}</p>
            </div>
            <div class="col-4">
                <h5>Last updated:</h5>
                <p>{{ deviceLastUpdated }}</p>
            </div>
            <div class="col-1">
                <h5>Status:</h5>
                <div class="custom-control custom-switch">
                    <input
                        type="checkbox"
                        class="custom-control custom-switch custom-control-input"
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
    </div>
</template>

<script>
export default {
    props: {
        deviceId: Number,
        deviceName: String,
        deviceStatus: String,
        deviceLastUpdated: String,
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
    // padding-left: 10.5em;
}
</style>
