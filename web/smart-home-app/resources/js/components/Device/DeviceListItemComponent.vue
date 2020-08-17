<template>
    <li class="list-group-item">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h4>{{ deviceName }}</h4>
                </div>
                <div class="col custom-control custom-switch">
                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="deviceStatus"
                        :checked="deviceStatus == 'On' ? true : false"
                    />
                    <label class="custom-control-label" for="deviceStatus"
                        >Status</label
                    >
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <p>{{ controllerName }}</p>
                </div>
                <div class="col-lg-6">
                    <p>{{ deviceStatus }}</p>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
export default {
    props: {
        deviceName: String,
        deviceStatus: String,
        controllerName: String
    }
};
</script>

<style lang="scss" scoped></style>
