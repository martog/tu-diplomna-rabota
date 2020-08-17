<template>
    <div>
        <h1>{{ deviceName }}</h1>
        <div class="row">
            <div class="col-6">
                <p>{{ deviceStatus }}</p>
            </div>
            <div class="col-6">
                <p>{{ controllerName }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        deviceName: String,
        deviceStatus: Boolean,
        controllerName: String
    }
};
</script>

<style lang="scss" scoped></style>
