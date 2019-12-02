<template>
    <div class="login-form">
        <h2 class="login-heading">Login</h2>
        <form action="#" @submit.prevent="register">

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

            <div class="input-group">
                <label class="input-group-btn">
                  <span class="btn btn-primary">
                    Browse:
                    <input
                      type="file"
                      class="custom-file-input"
                      v-on:change="onPhotoChange"
                      accept=".jpg, .jpeg, .png"
                      style="display: none;"
                    >
                  </span>
                </label>
                <input type="text" class="form-control" :value="filename" readonly>
              </div>

            <div class="form-control">
                <button type="submit" class="btn-submit">Register</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "Register",
        data() {
            return {
                name: '',
                email: '',
                password: '',
                nif: '',
                filename: '',
                photo: '',
            }
        },
        methods: {
            register(){
                this.$store.dispatch('register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    nif: this.nif,
                    photo: this.photo,
                })
                    .then(response => {
                        this.$router.push({name: 'login'});
                    })
            },

            onPhotoChange(e){
                this.photo = e.target.files[0];
                this.filename = this.photo.name;
            }
        }
    }
</script>

<style scoped>

</style>
