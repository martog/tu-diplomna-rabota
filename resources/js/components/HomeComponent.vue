<template>
    <div style="border:1px solid black;">
        <h1>Home</h1>
        <controller-list></controller-list>
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
