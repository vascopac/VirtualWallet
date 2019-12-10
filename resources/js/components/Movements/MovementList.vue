<template>
  <v-data-table
    :headers="headers"
    :items="movements"
    :items-per-page="5"
    class="jumbotron"
    :search="search"
    :custom-filter="filterOnlyCapsText"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Movements</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog">
          <v-card>
            <v-card-title>
              <span class="headline">{{ "More Details" }}</span>
            </v-card-title>

            <v-card-text>
              <v-subheader class="blue">Description</v-subheader>
              <div>{{ movement.description || '-' }}</div>
              <v-subheader class="blue">Source Description</v-subheader>
              <div>{{ movement.source_description || '-' }}</div>
              <v-subheader class="blue">IBAN</v-subheader>
              <div>{{ movement.iban || '-' }}</div>
              <v-subheader class="blue">MB Entity Code</v-subheader>
              <div>{{ movement.mb_entity_code || '-' }}</div>
              <v-subheader class="blue">MB Payment Reference</v-subheader>
              <div>{{ movement.mb_payment_reference || '-' }}</div>
              <div v-if="isTransfer == true">
                <v-subheader class="blue">Photo</v-subheader>
                <v-img
                  :src="'/storage/fotos/' + movement.photo"
                  max-width="500"
                  max-height="300"
                ></v-img>
              </div>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-text-field v-model="search" label="Search (UPPER CASE ONLY)" class="mx-4"></v-text-field>
    </template>
    <template v-slot:body.append>
        <tr>
          <td></td>
          <td>
            <v-text-field v-model="id" type="number" label="Search ID"></v-text-field>
          </td>
          <td colspan="8"></td>
        </tr>
    </template>
    <template v-slot:item.details="{ item }">
      <v-icon
        small
        class="md-2"
        @click="moreDetails(item)"
      >
        +info
      </v-icon>
    </template>
  </v-data-table>
</template>

<script>
  export default {
    data () {
      return {
        dialog: false,
        search: '',
        id: '',
        headers: [
            { text: 'Date', value: 'date' },
            { 
              text: 'ID', 
              value: 'id' ,
              filter: value => {
                if(!this.id) return true

                return value == parseInt(this.id)
              },
            },
            { text: 'Type', value: 'type' },
            { text: 'Transfer Email', value: 'transfer_email' },
            { text: 'Type of payment', value: 'type_payment' },
            { text: 'Category', value: 'category' },
            { text: 'Start Balance', value: 'start_balance' },
            { text: 'End Balance', value: 'end_balance' },
            { text: 'Value', value: 'value' },
            { text: '', value: 'details', sortable: false}
        ],
        movements: [],
        movement: '',
        isTransfer: false,
      }
    },
    methods: {
        getWallet: function(){
				axios.get('api/users/me')
					.then(response => {
                        this.wallet = response.data.data;
                        this.getMovements();
					})
		    },
        getMovements: function(){
            axios.get('api/movements/' + this.wallet.id)
                .then(response =>{
                    this.movements = response.data.data;
                })
                .catch(error =>{
                    console.log(error);
                })
        },
        moreDetails: function(item){
          this.movement = Object.assign({}, item);
          this.dialog = true;
          if(this.movement.transfer == 1){
            this.isTransfer = true;
          }
          console.log(item);
        },
        close(){
          this.dialog = false;
          this.isTransfer = false;
        },

        filterOnlyCapsText (value, search, item) {
        return value != null &&
          search != null &&
          typeof value === 'string' &&
          value.toString().toLocaleUpperCase().indexOf(search) !== -1
      },
    },
    watch: {
      dialog (val) {
        val || this.close()
      },
    },
    created() {
        this.getWallet();
    },
  }
</script>
