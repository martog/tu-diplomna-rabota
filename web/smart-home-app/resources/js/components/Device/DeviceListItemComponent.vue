<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ deviceName }}</h4>
            </div>
            <div class="col custom-control custom-switch">
                <input
                    type="checkbox"
                    class="custom-control-input"
                    :id="'device' + deviceId"
                    v-model="deviceActive"
                    @change="onDeviceStatusChange()"
                />
                <label class="custom-control-label" :for="'device' + deviceId"
                    >Status</label
                >
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <p>{{ controllerName }}</p>
            </div>
            <div class="col-lg-6">
                <p>{{ getDeviceStatus() }}</p>
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
            deviceActive: this.isDeviceActive()
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
            console.log(this.deviceActive);
        }
    }
};
</script>

<style lang="scss" scoped></style>
