<template>
    <v-form
    ref="form"
    class="jumbotron"
  >
  <h2>Change Password</h2>
    <v-text-field
      v-model="request.oldPassword"
      label="Old Password"
      type="password"
      required
    ></v-text-field>
    <v-text-field
      v-model="request.newPassword"
      :rules="[rules.required, rules.min]"
      label="New Password"
      type="password"
      counter
      required
    ></v-text-field>
    <v-text-field
      v-model="confirmPassword"
      :rules="[rules.required, rules.min]"
      label="Confirm New Password"
      type="password"
      counter
      required
    ></v-text-field>
    <v-btn
      color="success"
      class="mr-4"
      @click="savePassword"
    >
      Save
    </v-btn>
	<v-btn
      color="cancel"
      class="mr-4"
      @click="cancelEdit"
    >
      Cancel
    </v-btn>
  </v-form>
</template>

<script>
export default {
    data() {
        return {
            request: {
                    oldPassword: '',
                    newPassword: '',
            },
            confirmPassword: '',
            user: '',
            rules: {
                required: value => !!value || 'Required.',
                min: v => v.lenght >= 3 || 'Min 3 characters',
            },
        }
    },
    methods: {
        cancelEdit: function(){
	        this.$router.push({name: 'home'});
        }, 
        getUserInfo(){
            axios.get('api/users/me')
            .then(response => {
                this.user = response.data.data;
            })
        },
        savePassword(){
            if(this.confirmPassword === this.request.newPassword) {
                axios.patch('api/user/' + this.user.id + '/edit/password', this.request)
                    .then(response => {
                        Object.assign(this.user, response.data.data);
                        console.log('pass changed!');
                        this.$router.push({name: 'home'});
                    })
                    .catch(error => {
                        console.log(error);
                    })

            } else {
                console.log('pass diferente');
            }

            


        },
    },
    mounted() {
        this.getUserInfo();
    },
}
</script>