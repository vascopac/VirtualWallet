<template>
    <div class="jumbotron">
	    <h2>Edit User</h2>
	    <div class="form-control">
            <label for="name">Name</label>
            <input type="name" name="name" id="name" class="login-input" v-model="user.name">
        </div>

        <div class="form-control">
            <label for="nif">NIF</label>
            <input type="number" name="nif" id="nif" class="login-input" v-model="user.nif">
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
                <input type="text" class="form-control" :value="user.photo" readonly>
        </div>

	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				user: sessionStorage.getItem('user'),
				photo: '',
			}
		},
	    methods: {
	        saveUser: function(){
				this.$store.dispatch('edit', {
					photo: this.photo,
					user: this.user,
				})
	        },
	        cancelEdit: function(){
	            this.$router.push({name: 'home'});
			}, 
			onPhotoChange(e){
                this.photo = e.target.files[0];
                this.user.photo = this.photo.name;
            }
		},
		mounted() {
			this.user = JSON.parse(this.user);
		}
	}
</script>