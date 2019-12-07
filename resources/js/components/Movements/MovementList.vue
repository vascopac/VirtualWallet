<template>
  <v-data-table
    :headers="headers"
    :items="movements"
    :items-per-page="5"
    class="elevation-1"
  ></v-data-table>
</template>

<script>
  export default {
    data () {
      return {
        headers: [
            { text: 'Date', value: 'date' },
            { text: 'ID', value: 'id' },
            { text: 'Type', value: 'type' },
            { text: 'Transfer Email', value: 'transfer_email' },
            { text: 'Type of payment', value: 'type_payment' },
            { text: 'Category', value: 'category' },
            { text: 'Start Balance', value: 'start_balance' },
            { text: 'End Balance', value: 'end_balance' },
            { text: 'Value', value: 'value' },
        ],
        movements: [],
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
                    console.log(this.movements);
                })
                .catch(error =>{
                    console.log(error);
                })
        },
    },
    created() {
        this.getWallet();
    },
  }
</script>
