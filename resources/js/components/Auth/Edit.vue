<template>
  <v-form
    ref="form"
    class="jumbotron"
    lazy-validation
  >
  <h2>Edit User</h2>
    <v-text-field
      v-model="user.name"
      label="Name"
      required
    ></v-text-field>
    <v-text-field
      v-model="user.nif"
      v-if="user.type == 'u'"
      label="NIF"
      type="number"
      required
    ></v-text-field>
    <v-file-input
        accept="image/png, image/jpeg, image/bmp"
        type="file"
        :placeholder="user.photo"
        prepend-icon="mdi-camera"
        v-on:change="onPhotoChange"
        label="Avatar"
  ></v-file-input>
    <v-btn
      color="success"
      class="mr-4"
      @click="saveUser"
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
				user: '',
				photo: '',
			}
		},
	    methods: {
	      saveUser: function(){
				  this.$store.dispatch('editUser', {
            photo: this.photo,
            user: this.user,
				  })
	      },
	      cancelEdit: function(){
	        this.$router.push({name: 'home'});
		  	}, 
			  onPhotoChange(e){
          this.photo = e;
          this.user.photo = this.photo.name;
  			},
			  getUserInfo(){
				  axios.get('api/users/me')
					.then(response => {
						this.user = response.data.data;
					})
			  }
		},
		mounted() {
			this.getUserInfo();
		}
	}
</script>