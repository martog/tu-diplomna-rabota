<template>
    <div>
        <h1>My Controllers</h1>
        <div class="row mb-5">
            <div v-if="this.loading">
                <p>Loading</p>
            </div>
            <div
                v-else
                v-for="(controller, index) in this.controllers"
                :key="index"
                class="col"
            >
                <controller-list-item
                    :name="controller.name"
                    :status="controller.status"
                    :lastCommunication="controller.last_communication"
                    :devices="controller.devices"
                ></controller-list-item>
            </div>
        </div>
        <div class="row">
            <div class="col text-right">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
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
