<template>

</template>

<script>
    export default {
        created() {
            this.$store.dispatch('destroyToken')
                .then(response => {
                    this.$toasted.success('Loggout successfull!');
                    this.$router.push({name: 'home'});
                })
        }
    }
</script>

<style scoped>

</style>
