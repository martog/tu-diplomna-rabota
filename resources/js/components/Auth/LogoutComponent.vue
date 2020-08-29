<<template>
    <div></div>
</template>

<script>
export default {
    beforeCreate() {
        this.$store.dispatch("logout").then(response => {
            this.$router.push({ name: "login" });
        });
    }
};
</script>

<style lang="scss" scoped></style>
