<template>
    <v-app>
        <div>
            <v-toolbar>
            <v-toolbar-title><router-link :to="{ name: 'home' }">Virtual Wallet</router-link></v-toolbar-title>

            <v-spacer></v-spacer>

            <v-toolbar-items>
                <v-btn 
                v-if="!loggedIn"
                v-on="on"
                ><router-link :to="{ name: 'login' }">Login</router-link></v-btn>
                <v-btn 
                v-if="!loggedIn"
                v-on="on"
                ><router-link :to="{ name: 'register' }">Register</router-link></v-btn>
                <v-menu offset-y v-if="loggedIn">
                    <template v-slot:activator="{ on }">
                        <v-btn
                        v-on="on"
                        >
                        Movements
                        </v-btn>
                    </template>
                    <v-list>
                        <v-btn text v-if="loggedIn"><router-link :to="{ name: 'movements' }">List</router-link></v-btn>
                    </v-list>
                </v-menu>
                <v-menu offset-y v-if="loggedIn">
                    <template v-slot:activator="{ on }">
                        <v-btn
                        v-on="on"
                        >
                        User
                        </v-btn>
                    </template>
                    <v-list>
                        <v-btn text><router-link :to="{ name: 'edit' }">Edit</router-link></v-btn>
                        <v-btn text><router-link :to="{ name: 'logout' }">Logout</router-link></v-btn>
                    </v-list>
                </v-menu>
            </v-toolbar-items>

            </v-toolbar>
            <router-view></router-view>
        </div>
    </v-app>
</template>
<script>
    export default {
        computed: {
            loggedIn(){
                return this.$store.getters.loggedIn;
            }
        }
    }
</script>