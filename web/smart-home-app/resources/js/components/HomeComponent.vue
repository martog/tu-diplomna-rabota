<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Home Component</div>

                    <div class="card-body">I'm the Home component.</div>
                    <device-list></device-list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DeviceList from "./Device/DeviceListComponent";
export default {
    components: {
        DeviceList
    },

    mounted() {
        console.log("Component mounted.");
    }
};
</script>
