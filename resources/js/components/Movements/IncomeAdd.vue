<template>
    <v-form
    ref="form"
    class="jumbotron"
    lazy-validation
    >
    <h2>Add Movement</h2>
        <v-text-field
            v-model="email"
            label="E-mail"
            required
        ></v-text-field>
        <v-text-field
            v-model="value"
            type="number"
            label="Value"
            :rules="[valueRules.valueRange]"
            required
        ></v-text-field>
        <v-select
            v-model="typeOfPayment"
            :items="typesPayment"
            label="Select the payment type"
            required
        ></v-select>
        <v-text-field
            v-model="source_description"
            label="Source Description"
            v-if="typeOfPayment == 'bt'"
        ></v-text-field>
        <v-text-field 
            v-model="iban"
            label="IBAN"
            v-if="typeOfPayment == 'bt'"
            :rules="ibanRules"
            required
        ></v-text-field>
        <v-btn
            color="success"
            class="mr-4"
            @click="add"
        >
            Add
        </v-btn>
    </v-form>
</template>

<script>
export default {
    data() {
        return {
            value: '',
            valueRules: {
                valueRange: value => parseFloat(value) >= 0.01 && parseFloat(value) <= 5000 || "Value must be bigger then 0,01€ and lower than 5000€",
            },
            iban: '',
            typeOfPayment: '',
            typesPayment: [
                { text: 'Bank Transfer', value: 'bt'},
                { text: 'Cash', value: 'c'}
            ],
            ibanRules: [
                value => {
                    if(value.match(/^[A-Z]{2}[0-9]{23}$/)) return true
                    return 'IBAN must have 2 capital letters followed by 23 numbers!'
                }
            ],
            email: '',
            source_description: '',
            user: JSON.parse('[' + sessionStorage.getItem("user") + ']'),
        }
    },
    methods: {
        add(){
            axios.post('api/wallet/email', {
                    email: this.email
                })
                .then(response => {
                    console.log(response.data);
                    let end = +response.data.balance + +this.value;
                    if(this.typeOfPayment == 'bt'){
                        axios.post('api/movements/income', {
                            wallet_id: response.data.id,
                            type: 'i',
                            value: this.value,
                            type_payment: this.typeOfPayment,
                            source_description: this.source_description,
                            iban: this.iban,
                            start_balance: response.data.balance,
                            end_balance: end,
                            transfer: 0,
                        })
                        .then(response => {
                            console.log('adicionado');
                        })
                    } else{
                        axios.post('api/movements/income', {
                            wallet_id: response.data.id,
                            type: 'i',
                            value: this.value,
                            type_payment: this.typeOfPayment,
                            start_balance: response.data.balance,
                            end_balance: end,
                            transfer: 0
                        })
                        .then(response => {
                            console.log('adicionado');
                        })
                    }
                    
                })
                .catch(error => {
                    console.log('error')
                })
                
        }  
    },
}
</script>
