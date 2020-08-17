<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-2">
                <h4>{{ deviceName }}</h4>
            </div>
            <div class="col-xl-10 custom-control custom-switch">
                <!-- <div
                    v-if="deviceStatusLoading"
                    class="spinner-border text-primary"
                    role="status"
                > -->
                <span class="sr-only">Loading...</span>
                <!-- </div> -->
                <!-- <div v-else> -->
                <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="deviceActive"
                    :id="deviceId"
                    :disabled="deviceStatusLoading"
                    @click="onDeviceStatusChange()"
                />
                <label class="custom-control-label" :for="deviceId"></label>
                <!-- </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col-xl-2">
                <p>{{ controllerName }}</p>
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
                this.deviceStatusLoading = false;
            }, 2000);
        }
    }
};
</script>

<style lang="scss" scoped>
input {
    text-align: left;
}
</style>
