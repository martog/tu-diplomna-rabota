<template>
    <div>
        <div class="row mb-2">
            <div class="col text-center" v-if="this.loading">
                <div class="spinner-border text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div
                v-else
                v-for="(controller, index) in this.getControllers()"
                :key="index"
                @click="setSelectedController(controller.id)"
                class="col-4"
            >
                <controller-list-item
                    class="custom-controller"
                    :selected="selectedControllerId === controller.id"
                    :name="controller.name"
                    :status="controller.status"
                    :lastCommunication="controller.last_communication"
                    :devices="controller.devices"
                ></controller-list-item>
            </div>
        </div>
        <div v-if="!this.loading" class="row justify-content-end">
            <div class="col text-right">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li
                            class="page-item"
                            :class="{
                                disabled: currentPage === 1
                            }"
                        >
                            <a
                                class="page-link"
                                @click="setCurrentPage(currentPage - 1)"
                                aria-label="Previous"
                            >
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <li
                            class="page-item"
                            :class="{ active: page === currentPage }"
                            v-for="page in totalPages"
                            :key="page"
                        >
                            <a
                                class="page-link"
                                @click="setCurrentPage(page)"
                                >{{ page }}</a
                            >
                        </li>
                        <li
                            class="page-item"
                            :class="{
                                disabled: currentPage === totalPages
                            }"
                        >
                            <a
                                class="page-link"
                                @click="setCurrentPage(currentPage + 1)"
                                aria-label="Next"
                            >
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
    props: {
        itemsPerPage: Number,
        deviceStatusChange: {
            deviceId: Number,
            status: Boolean
        }
    },
    watch: {
        deviceStatusChange: function(newVal, oldVal) {
            this.retrieveControllers();
        }
    },
    data() {
        return {
            controllers: null,
            loading: true,
            currentPage: 1,
            totalPages: 1,
            selectedControllerId: null
        };
    },
    methods: {
        retrieveControllers() {
            const controllersRequest = axios.get("/controllers");

            controllersRequest
                .then(response => {
                    this.controllers = response.data;

                    if (this.controllers.length) {
                        this.totalPages = Math.ceil(
                            this.controllers.length / this.itemsPerPage
                        );
                    }

                    console.log(this.controllers);
                    this.loading = false;
                })
                .catch(error => {
                    this.controllers = [];
                    this.loading = false;
                    console.log(error);
                });
        },
        getControllers() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const stop = start + this.itemsPerPage;
            console.log(
                "start " +
                    start +
                    " stop " +
                    stop +
                    " total pages " +
                    this.totalPages
            );
            return this.controllers.slice(start, stop);
        },
        getSelectedControllersData() {
            return this.$store.getters.controllersData;
        },
        setCurrentPage(page) {
            let data = this.getSelectedControllersData();
            data.selectedPage = page;

            this.$store.commit("setControllersData", data);
            this.currentPage = page;
        },
        setSelectedController(controllerId) {
            let data = this.getSelectedControllersData();
            data.selectedControllerId = controllerId;

            this.$store.commit("setControllersData", data);
            this.$emit("changedSelectedController", controllerId);
            this.selectedControllerId = controllerId;
        },
        setup() {
            const controllersData = this.getSelectedControllersData();
            if (!controllersData) {
                return;
            }

            if (controllersData.selectedPage) {
                this.currentPage = controllersData.selectedPage;
            }

            if (controllersData.selectedControllerId) {
                this.selectedControllerId =
                    controllersData.selectedControllerId;
                this.$emit(
                    "changedSelectedController",
                    controllersData.selectedControllerId
                );
            }
        }
    },
    created() {
        this.setup();
        this.loading = true;
        this.retrieveControllers();
    }
};
</script>

<style lang="scss" scoped>
.page-link:hover {
    cursor: pointer;
}
.custom-controller:hover {
    cursor: pointer;
}
.page-item .page-link {
    //background-color: #343a40;

    color: #212529;
}
.page-item.active .page-link {
    color: #fff;
    background-color: #53a5db;
    border-color: #53a5db;
}
</style>
