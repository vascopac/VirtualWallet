<template>
    <v-form
        ref="form"
        class="jumbotron"
        lazy-validation
    >
        <h2>Create User</h2>
            <v-select
                v-model="type"
                :items="types"
                label="Type of account"
                required
            ></v-select>
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
                @click="create"
            >
                Create
            </v-btn>
    </v-form>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                email: '',
                password: '',
                filename: '',
                photo: '',
                type: '',
                types: [
                    {text: 'Administrator', value: 'a'},
                    {text: 'Operator', value: 'o'},
                ]
            }
        },
        methods: {
            create(){
                this.$store.dispatch('addUser', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    photo: this.photo,
                    type: this.type,
                })
                .then(response => {
                    this.$router.push({name: 'users'});
                })
            },

            onPhotoChange(e){
                this.photo = e;
                this.filename = this.photo.name;
            }
        }
    }
</script>
