<template>
  <v-form
    ref="form"
    class="jumbotron"
    lazy-validation
  >
  <h2 class="login-heading">Login</h2>
    <v-text-field
      v-model="email"
      label="E-mail"
      required
    ></v-text-field>
    <v-text-field
      v-model="password"
      label="Password"
      type="password"
      required
    ></v-text-field>
    <v-btn
      color="success"
      class="mr-4"
      @click="login"
    >
      Login
    </v-btn>
  </v-form>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                email: '',
                password: '',
            }
        },
        methods: {
            login(){
                this.$store.dispatch('retrieveToken', {
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                        this.$router.push({name: 'home'});
                    })
            }
        }
    }
</script>
