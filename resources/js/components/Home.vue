<template>
    <v-app class="blue lighten-2">
        <v-content>
            <v-container class="fill-height" fluid>
                <v-flex class="text-xl-center">
                    <h1 class="white--text display-4">Welcome To Virtual Wallet!</h1>
                    <p class="white--text display-1">We Currently Have {{this.numWallets}} Virtual Wallets</p>
                    <p class="white--text display-1">We Currently Have {{this.number}} €</p>
                </v-flex>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: "Home.vue",
        data() {
            return {
                numWallets: '',
                number: '',
            }
        },
        methods: {
            wallets(){
                axios.get('/api/wallets')
                .then(response => {
                    this.numWallets = response.data;
                    console.log(response);
                });
            },
            count(){
                axios.get('/api/walletsvalue')
                .then(response => {
                    this.number = response.data;
                    console.log(response);
                });
            },
        },
        created() {
            this.wallets();
            this.count();
        },
    }
</script>

