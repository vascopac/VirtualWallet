<template>
  <v-form
    ref="form"
    class="jumbotron"
    lazy-validation
  >
  <h2>Register</h2>
    <v-text-field
      v-model="name"
      label="Name"
      required
    ></v-text-field>
    <v-text-field
      v-model="email"
      label="E-mail"
      type="email"
      required
    ></v-text-field>
    <v-text-field
      v-model="password"
      label="Password"
      type="password"
      required
    ></v-text-field>
    <v-text-field
      v-model="nif"
      label="NIF"
      type="number"
      required
    ></v-text-field>
    <v-file-input
        accept="image/png, image/jpeg, image/bmp"
        type="file"
        placeholder="Pick an avatar"
        prepend-icon="mdi-camera"
        v-on:change="onPhotoChange"
        label="Avatar"
  ></v-file-input>
    <v-btn
      color="success"
      class="mr-4"
      @click="register"
    >
      Register
    </v-btn>
  </v-form>
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
                this.photo = e;
                this.filename = this.photo.name;
            }
        }
    }
</script>

<style scoped>

</style>
