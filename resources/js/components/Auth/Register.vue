<template>
    <div class="login-form">
        <h2 class="login-heading">Login</h2>
        <form action="#" @submit.prevent="login">

            <div class="form-control">
                <label for="name">Name</label>
                <input type="name" name="name" id="name" class="login-input" v-model="name">
            </div>

            <div class="form-control">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="login-input" v-model="email">
            </div>

            <div class="form-control mb-more">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="login-input" v-model="password">
            </div>

            <div class="form-control">
                <label for="nif">NIF</label>
                <input type="number" name="nif" id="nif" class="login-input" v-model="nif">
            </div>

            <div class="form-control">
                <button type="submit" class="btn-submit">Login</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                name: '',
                email: '',
                password: '',
                nif: '',
            }
        },
        methods: {
            login(){

            }
        }
    }
</script>

<style scoped>

</style>
