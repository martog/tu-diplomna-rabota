<template>
    <div>
        <controller-list :itemsPerPage="4"></controller-list>
    </div>
</template>

<script>
import ControllerList from "./Controller/ControllerListComponent";
export default {
    components: {
        ControllerList
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {}
};
</script>
