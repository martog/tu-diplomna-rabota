<template>
    <div class="card w-100" :class="getBorderClass">
        <div class="card-body">
            <div class="row card-title mb-0">
                <div class="col">
                    <h5>{{ this.name }}</h5>
                </div>
                <div class="col text-right">
                    <span class="align-text-top badge" :class="getBadgeClass">{{
                        this.status
                    }}</span>
                </div>
            </div>

            <div class="card-subtitle">
                <span class="text-muted"
                    >Last communication: {{ this.lastCommunication }}</span
                >
            </div>

            <div class="card-text"></div>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col text-left">
                    Active devices
                </div>
                <div class="col text-right">
                    {{ this.devices.On }}/{{
                        this.devices.On + this.devices.Off
                    }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        status: String,
        lastCommunication: String,
        devices: Object,
        selected: Boolean
    },
    computed: {
        getBadgeClass() {
            if (this.status === "Online") {
                return ["badge-success"];
            }

            return ["badge-danger"];
        },
        getBorderClass() {
            if (this.selected) {
                return ["border-primary"];
            }

            // return ["border-dark"];
        }
    }
};
</script>

<style lang="scss" scoped></style>
