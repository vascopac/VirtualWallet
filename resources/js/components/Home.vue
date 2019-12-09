<template>
    <div>
        {{this.numWallets}}
    </div>
</template>

<script>
    export default {
        name: "Home.vue",
        data() {
            return {
                numWallets: '',
            }
        },
        methods: {
            wallets(){
                axios.get('/api/wallets')
                .then(response => {
                    this.numWallets = response.data;
                    console.log(response);
                });
            }
        },
        created() {
            this.wallets();
        },
    }
</script>

