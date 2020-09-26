<template>
    <div class="container">
        <div class="row">
            <div class="col-3" v-if="!this.editModeEnabled">
                <h5>Device:</h5>
                <p>
                    {{ newDeviceName ? newDeviceName : deviceName }}
                    <span
                        @click="enableEditMode(true)"
                        class="material-icons custom-icon"
                    >
                        edit
                    </span>
                </p>
            </div>
            <div class="col-3" v-else>
                <h5>Device:</h5>
                <input type="text" v-model="newDeviceName" />
                <span
                    v-if="!deviceUpdateLoading"
                    @click="save(deviceId)"
                    class="material-icons custom-icon"
                >
                    check
                </span>
                <span
                    v-if="!deviceUpdateLoading"
                    @click="cancelEdit()"
                    class="material-icons custom-icon"
                >
                    clear
                </span>
                <div
                    v-if="deviceUpdateLoading"
                    class="ml-1 spinner-border spinner-border-sm"
                    role="status"
                >
                    <span class="sr-only">Loading...</span>
                </div>
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
                        :id="deviceId"
                        :disabled="deviceStatusLoading"
                        :checked="deviceActive"
                        v-on:click="onDeviceStatusChange(deviceId)"
                    />
                    <label class="custom-control-label" :for="deviceId">
                        <div
                            v-if="deviceStatusLoading"
                            class="spinner-border text-primary spinner-border-sm"
                            role="status"
                        >
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div v-else>
                            {{ getDeviceStatus() }}
                        </div>
                    </label>
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
        deviceLastUpdatedProp: String,
        controllerName: String
    },
    data() {
        return {
            deviceActive: this.isDeviceActive(),
            deviceLastUpdated: "",
            deviceStatusLoading: false,
            deviceUpdateLoading: false,
            deviceStatusNotChangedErrMsg:
                "Device status not changed. Check connection!",
            editModeEnabled: false,
            newDeviceName: null
        };
    },
    created() {
        this.deviceLastUpdated = this.deviceLastUpdatedProp;
        this.newDeviceName = this.deviceName;
    },
    methods: {
        isDeviceActive() {
            return this.deviceStatus === "On" ? true : false;
        },
        getDeviceStatus() {
            return this.deviceActive ? "On" : "Off";
        },
        onDeviceStatusChange(deviceId) {
            this.deviceStatusLoading = true;

            const statusToSet = this.deviceActive ? "Off" : "On";
            const url = `controller/devices/${deviceId}/status/${statusToSet}`;
            const setDeviceStatusRequest = axios.post(url);

            this.deviceActive = !this.deviceActive;
            setDeviceStatusRequest
                .then(response => {
                    this.deviceLastUpdated = response.data.updated_at;
                    this.deviceStatusLoading = false;

                    if (this.getDeviceStatus() !== response.data.status) {
                        throw new Error(this.deviceStatusNotChangedErrMsg);
                    }

                    this.$emit("changedDeviceStatus", {
                        deviceId: deviceId,
                        status: this.deviceActive
                    });
                })
                .catch(error => {
                    this.deviceActive = !this.deviceActive;
                    this.deviceStatusLoading = false;
                });
        },
        save(deviceId) {
            this.deviceUpdateLoading = true;

            const url = `controller/devices/${deviceId}/update`;
            const updateDeviceNameRequest = axios.put(url, {
                device_name: this.newDeviceName
            });

            updateDeviceNameRequest
                .then(response => {
                    this.deviceUpdateLoading = false;
                    this.editModeEnabled = false;
                })
                .catch(error => {
                    this.newDeviceName = this.deviceName;
                    this.deviceUpdateLoading = false;
                    this.editModeEnabled = false;
                });
        },
        enableEditMode(value) {
            this.editModeEnabled = value;
        },
        cancelEdit() {
            this.newDeviceName = this.deviceName;
            this.enableEditMode(false);
        }
    }
};
</script>

<style lang="scss" scoped>
label {
    position: relative;
}
.custom-icon {
    font-size: 18px;
    color: #cab7b7;
}
.custom-icon:hover {
    cursor: pointer !important;
    color: #343a40;
}
</style>
