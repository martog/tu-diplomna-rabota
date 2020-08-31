<template>
    <div>
        <h1>My Controllers</h1>
        <div class="row mb-2">
            <div class="col" v-if="this.loading">
                <p>Loading</p>
            </div>
            <div
                v-else
                v-for="(controller, index) in this.getControllers()"
                :key="index"
                class="col-3"
            >
                <controller-list-item
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
        itemsPerPage: Number
    },
    data() {
        return {
            controllers: null,
            loading: false,
            currentPage: 1,
            totalPages: 1
        };
    },
    methods: {
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
        setCurrentPage(page) {
            this.$store.commit("setControllersData", {
                selectedPage: page
            });
            this.currentPage = page;
        }
    },
    created() {
        const controllersData = this.$store.getters.controllersData;

        if (controllersData.selectedPage) {
            this.currentPage = controllersData.selectedPage;
        }

        const controllersRequest = axios.get("/controllers");

        this.loading = true;
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
    }
};
</script>

<style lang="scss" scoped>
.page-link:hover {
    cursor: pointer;
}
</style>
