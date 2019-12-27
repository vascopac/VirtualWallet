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
    <template v-slot:item.type="{ item }">
      <div
      v-if="item.type == 'a'"
      >
        {{ 'Admin' }}
      </div>
      <div
      v-if="item.type == 'o'"
      >
        {{ 'Operator' }}
      </div>
      <div
      v-if="item.type == 'u'"
      >
        {{ 'User' }}
      </div>
    </template>
    <template v-slot:item.active="{ item }">
      <div
      v-if="item.active == '1'"
      >
        {{ 'Active' }}
      </div>
      <div
      v-if="item.active == '0'"
      >
        {{ 'Inactive' }}
      </div>
    </template>
    <template v-slot:item.wallet="{ item }">
      <div
      v-if="!item.wallet"
      >
        {{ '-' }}
      </div>
      <div
      v-if="item.wallet == '0'"
      >
        {{ 'Empty' }}
      </div>
      <div
      v-if="item.wallet > '0'"
      >
        {{ 'Has money' }}
      </div>
    </template>
    <template v-slot:item.photo="{ item }">
      <v-img
        :src="'/storage/fotos/' + item.photo"
        v-if="item.photo != null"
        max-width="100"
        max-height="100"
        ></v-img>
    </template>
    <template v-slot:body.append>
        <tr>
          <td>
            <v-text-field v-model="name" label="Search Name"></v-text-field>
          </td>
          <td>
            <v-text-field v-model="email" label="Search Email"></v-text-field>
          </td>
          <td>
            <v-combobox
              v-model="type"
              :items="types"
              label="Search Type"
            ></v-combobox>
          </td>
          <td>
            <v-combobox
              v-model="status"
              :items="active"
              label="Search Status"
            ></v-combobox>
          </td>
          <td colspan="2"></td>
        </tr>
    </template>
  </v-data-table>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            headers: [
                { 
                  text: 'Name', 
                  value: 'name',
                  filter: value => {
                    if(!this.name) return true

                    return value.toString().includes(this.name, 0)
                  }
                },
                { 
                  text: 'E-mail', 
                  value: 'email',
                  filter: value => {
                    if(!this.email) return true

                    return value.toString().includes(this.email, 0)
                  }
                },
                { 
                  text: 'Type', 
                  value: 'type',
                  filter: value => {
                    if(!this.type) return true

                    if(this.type == 'Admin') return value == 'a'

                    if(this.type == 'Operator') return value == 'o'

                    if(this.type == 'User') return value == 'u'
                  }  
                },
                { 
                  text: 'Account Status', 
                  value: 'active',
                  filter: value => {
                    if(!this.status) return true

                    if(this.status == 'Active') return value == '1'

                    if(this.status == 'Inactive') return value == '0'
                  }  
                },
                { text: 'Wallet Balance', value: 'wallet'},
                { text: 'Photo', value: 'photo'},
            ],
            name: '',
            email: '',
            type: '',
            status: '',
            types: [
              '',
              'Admin',
              'Operator',
              'User'
            ],
            active: [
              '',
              'Active',
              'Inactive'
            ]
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