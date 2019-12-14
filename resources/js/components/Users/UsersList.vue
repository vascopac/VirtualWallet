<template>
    <v-data-table
    :headers="headers"
    :items="users"
    :items-per-page="5"
    class="jumbotron"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Users</v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
    </template>
    <template v-slot:item.photo="{ item }">
      <v-img
        :src="'/storage/fotos/' + item.photo"
        v-if="item.photo != null"
        max-width="100"
        max-height="100"
        ></v-img>
    </template>
  </v-data-table>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            headers: [
                { text: 'Name', value: 'name'},
                { text: 'E-mail', value: 'email'},
                { text: 'Type', value: 'type'},
                { text: 'Photo', value: 'photo'},
            ],
        }
    },
    methods: {
        getUsers() {
            axios.get('api/users')
            .then(response => {
                console.log(response);
                this.users = response.data.data;
            })
        },
    },
    created() {
        this.getUsers();
    },
}
</script>