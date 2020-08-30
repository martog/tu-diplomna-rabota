<template>
    <div>
        {{ this.name }} {{ this.status }} Devices active: {{ this.devices.On }}/
        {{ this.devices.On + this.devices.Off }}, {{ this.lastCommunication }}
    </div>
</template>

<script>
export default {
    props: {
        name: String,
        status: String,
        lastCommunication: String,
        devices: Object
    }
};
</script>

<style lang="scss" scoped></style>
