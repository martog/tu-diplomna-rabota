<template>
    <div>
        <div v-if="this.loading">
            <p>Loading</p>
        </div>
        <div
            v-else
            v-for="(controller, index) in this.controllers"
            :key="index"
        >
            <controller-list-item
                :name="controller.name"
                :status="controller.status"
                :lastCommunication="controller.last_communication"
                :devices="controller.devices"
            ></controller-list-item>
        </div>
    </div>
</template>

<script>
import ControllerListItem from "./ControllerListItemComponent";
export default {
    components: {
        ControllerListItem
    },
    data() {
        return {
            controllers: null,
            loading: false
        };
    },
    created() {
        const controllersRequest = axios.get("/controllers");

        this.loading = true;
        controllersRequest
            .then(response => {
                this.controllers = response.data;
                console.log(this.controllers);
                this.loading = false;
            })
            .catch(error => {
                this.controllers = [];
                this.loading = false;
                console.log(error);
            });
    }
};
</script>

<style lang="scss" scoped></style>
