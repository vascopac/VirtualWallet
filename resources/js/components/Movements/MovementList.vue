<template>
<div>
  <v-data-table
    :headers="headers"
    :items="movements"
    :items-per-page="5"
    class="jumbotron"
  >
    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Movements</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="white" class="mb-2"><router-link :to="{ name: 'movementAdd' }">New Movement</router-link></v-btn>
        <v-dialog v-model="details">
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
        <v-dialog v-model="edit">
          <v-card>
            <v-card-title>
              <span class="headline">{{ "Edit Movement" }}</span>
            </v-card-title>
            <v-card-text>
              <v-select
                v-if="movement.type == 'Expense'"
                v-model="movement.category_id"
                :items="categoriesE"
                item-text="name"
                item-value="id"
                label="Category"
              ></v-select>
              <v-select
                v-if="movement.type == 'Income'"
                v-model="movement.category_id"
                :items="categoriesI"
                item-text="name"
                item-value="id"
                label="Category"
              ></v-select>
              <v-text-field
                v-model="movement.description"
                label="Description"
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="save">Save</v-btn>
              <v-btn color="blue darken-1" text @click="close">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-row>
        <v-col cols="10" sm="6" md="2">
        <v-menu
          v-model="menu1"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="dateStart"
              label="Date Start"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="dateStart" @input="menu1 = false"></v-date-picker>
        </v-menu>
        </v-col>
        <v-col cols="10" sm="6" md="2">
        <v-menu
          v-model="menu2"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="dateEnd"
              label="Date End"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="dateEnd" @input="menu2 = false"></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
    </template>
    <template v-slot:body.append>
        <tr>
          <td>
          </td>
          <td>
            <v-text-field v-model="id" type="number" label="Search ID"></v-text-field>
          </td>
          <td>
            <v-combobox
              v-model="searchType"
              :items="types"
              label="Search Type"
            ></v-combobox>
          </td>
          <td>
            <v-text-field v-model="searchEmail" label="Search Email"></v-text-field>
          </td>
          <td>
            <v-combobox
              v-model="searchTypePayment"
              :items="typesPayment"
              label="Search Type of Payment"
            ></v-combobox>
          </td>
          <td>
            <v-text-field v-model="searchCategory" label="Search Category"></v-text-field>
          </td>
          <td colspan="4"></td>
        </tr>
    </template>
    <template v-slot:item.details="{ item }">
      <v-icon
        small
        class="md-2"
        @click="moreDetails(item)"
      >
        info
      </v-icon>
    </template>
    <template v-slot:item.edit="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editMovement(item)"
      >
        edit
      </v-icon>
    </template>
  </v-data-table>
</div>
</template>

<script>
  export default {
    data () {
      return {
        details: false,
        edit: false,
        id: '',
        searchType: '',
        searchEmail: '',
        searchTypePayment: '',
        searchCategory: '',
        dateEnd: '',
        dateStart: '',
        editedIndex: -1,
        headers: [
          {
            text: 'Edit',
            value: 'edit',
            sortable: false,
          },
          { 
              text: 'Date', 
              value: 'date',
              filter: value => {
                if(!this.dateStart || !this.dateEnd) return true

                return Date.parse(value) > Date.parse(this.dateStart) && Date.parse(value) < Date.parse(this.dateEnd)
              } 
            },
            { 
              text: 'ID', 
              value: 'id' ,
              filter: value => {
                if(!this.id) return true

                return value == parseInt(this.id)
              },
              sortable: false
            },
            { 
              text: 'Type', 
              value: 'type', 
              sortable: false,
              filter: value => {
                if(!this.searchType) return true

                return value == this.searchType
              }
            },
            { 
              text: 'Transfer Email', 
              value: 'transfer_email', 
              sortable: false,
              filter: value => {
                if(!this.searchEmail) return true

                return value.toString().includes(this.searchEmail, 0)
              }
            },
            { 
              text: 'Type of payment', 
              value: 'type_payment', 
              sortable: false,
              filter: value => {
                if(!this.searchTypePayment) return true

                return value == this.searchTypePayment
              } 
            },
            { 
              text: 'Category', 
              value: 'category', 
              sortable: false,
              filter: value => {
                if(!this.searchCategory) return true

                return value.toString().includes(this.searchCategory, 0)
              }
            },
            { text: 'Start Balance', value: 'start_balance', sortable: false },
            { text: 'End Balance', value: 'end_balance', sortable: false },
            { text: 'Value', value: 'value', sortable: false},
            { text: '', value: 'details', sortable: false}
        ],
        movements: [],
        movement: '',
        isTransfer: false,
        types: [
          '',
          'Income',
          'Expense'
        ],
        typesPayment: [
          '',
          '-',
          'MB',
          'Cash',
          'Bank Transfer',
        ],
        menu1: false,
        menu2: false,
        categoriesE: [],
        categoriesI: [],
        editedMovement: '',
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
          this.details = true;
          if(this.movement.transfer == 1){
            this.isTransfer = true;
          }
        },
        close(){
          this.details = false;
          this.isTransfer = false;
          this.edit = false;
        },
        editMovement: function(item) {
          this.editedIndex = this.movements.indexOf(item)
          this.movement = Object.assign({}, item)
          this.edit = true
        },
        getCategories(){
            axios.get('/api/categories')
            .then(response => {
                var cats = response.data.data;
                cats.forEach(element => {
                    if(element.type === 'e'){
                      this.categoriesE.push(element);
                    } else {
                      this.categoriesI.push(element);
                    }
                });
            })
        },
        save(){
          Object.assign(this.movements[this.editedIndex], this.movement)
          axios.put('api/movements/' + this.movement.id, this.movement)
            .then(response => {
              console.log(response);
              this.getMovements();
              this.edit = false; 
              this.$toasted.success('Movement ' + this.movement.id + ' has been successfully added!');
            })
          
        }
    },
    watch: {
      details (val) {
        val || this.close()
      },
      editMovement (val) {
        val || this.close()
      },
    },
    created() {
        this.getWallet();
        this.getCategories();
    },
    sockets: {
      connect() {
        console.log("socket connected (socket ID =  " + this.$socket.id + ")");
      },
      movement_created(movement){
        this.getMovements();
        this.$toasted.show('Movement with id ' + movement.id + ' added to you account!');
      }
    }
  }
</script>
