<template>
    <v-form
    ref="form"
    class="jumbotron"
    lazy-validation
    >
    <h2>Add Movement</h2>
        <v-text-field
            v-model="value"
            type="number"
            label="Value"
            :rules="[valueRules.balance, valueRules.valueRange]"
            required
        ></v-text-field>
        <v-text-field
            v-model="description"
            label="Description"
        ></v-text-field>
        <v-select
            v-model="category"
            :items="categories"
            item-text="name"
            item-value="id"
            label="Select the category of expense"
        ></v-select>
        <v-select
            v-model="typeOfMovement"
            :items="typesMovement"
            label="Select the movement type"
            required
        ></v-select>
        <template v-if="typeOfMovement == '0'">
            <v-select
            v-model="typeOfPayment"
            :items="typesPayment"
            label="Select the payment type"
            required
            ></v-select>
            <v-text-field 
                v-model="iban"
                label="IBAN"
                v-if="typeOfPayment == 'bt'"
                :rules="ibanRules"
                required
            ></v-text-field>
            <template v-if="typeOfPayment == 'mb'">
                <v-text-field
                    v-model="mb_entity_code"
                    :rules="[mbRules.entityCode]"
                    label="MB Entity Code"
                ></v-text-field>
                <v-text-field
                    v-model="mb_payment_reference"
                    :rules="[mbRules.paymentReference]"
                    label="MB Payment Reference"
                ></v-text-field>
            </template>
        </template>
        <template v-if="typeOfMovement == '1'">
            <v-text-field
                v-model="email"
                label="E-mail"
                required
            ></v-text-field>
            <v-text-field
                v-model="source_description"
                label="Source Description"
            ></v-text-field>
        </template>
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
            typeOfMovement: '',
            typesMovement: [
                { text: 'Transfer', value: '1'},
                { text: 'Payment to external entity', value: '0'}
            ],
            value: '',
            valueRules: {
                valueRange: value => parseFloat(value) >= 0.01 && parseFloat(value) <= 5000 || "Value must be bigger then 0,01€ and lower than 5000€",
                balance: value => parseFloat(value) <= parseFloat(this.user[0].wallet) || "You only have " + this.user[0].wallet + "€ in your wallet!"
            },
            description: '',
            category: '',
            categories: [],
            iban: '',
            typeOfPayment: '',
            typesPayment: [
                { text: 'Bank Transfer', value: 'bt'},
                { text: 'MB Payment', value: 'mb'}
            ],
            ibanRules: [
                value => {
                    if(value.match(/^[A-Z]{2}[0-9]{23}$/)) return true
                    return 'IBAN must have 2 capital letters followed by 23 numbers!'
                }
            ],
            mbRules: {
                entityCode: value => value.length == 5 || 'MB Entity Code must have 5 digits',
                paymentReference: value => value.length == 9 || 'MB Payment Reference must have 9 digits',
            },
            mb_entity_code: '',
            mb_payment_reference: '',
            email: '',
            source_description: '',
            user: JSON.parse('[' + sessionStorage.getItem("user") + ']'),
        }
    },
    methods: {
        getCategories(){
            axios.get('/api/categories')
            .then(response => {
                var cats = response.data.data;
                cats.forEach(element => {
                    if(element.type === 'e'){
                        this.categories.push(element);
                    }
                });
            })
        },
        add(){
            let end = +this.user[0].wallet - +this.value;
            if(this.typeOfMovement == '1'){
                axios.post('api/wallet/email', {
                    email: this.email
                })
                .then(response => {
                    axios.post('api/movements', {
                        wallet_id: this.user[0].id,
                        type: 'e',
                        value: this.value,
                        description: this.description,
                        source_description: this.source_description,
                        category_id: this.category,
                        transfer: this.typeOfMovement,
                        start_balance: this.user[0].wallet,
                        end_balance: end,
                        transfer_wallet_id: response.data.id 
                    })
                    .then(response => {
                        this.$router.push({name: 'movements'});
                        this.$socket.emit('movement_created', response.data.data);
                    })
                })
                .catch(error => {
                    this.$toasted.error('Something went wrong!');
                })
                
            } else if (this.typeOfMovement == '0'){
                if(this.typeOfPayment == 'bt'){
                    axios.post('api/movements', {
                        wallet_id: this.user[0].id,
                        type: 'e',
                        value: this.value,
                        description: this.description,
                        category_id: this.category,
                        transfer: this.typeOfMovement,
                        start_balance: this.user[0].wallet,
                        end_balance: end,
                        type_payment: this.typeOfPayment,
                        iban: this.iban,
                    })
                    .then(response => {
                        this.$router.push({name: 'movements'});
                        this.$socket.emit('movement_created', response.data.data);
                    })
                    .catch(error => {
                        this.$toasted.error('Something went wrong!');
                    })
                } else{
                    axios.post('api/movements', {
                        wallet_id: this.user[0].id,
                        type: 'e',
                        value: this.value,
                        description: this.description,
                        category_id: this.category,
                        transfer: this.typeOfMovement,
                        start_balance: this.user[0].wallet,
                        end_balance: end,
                        type_payment: this.typeOfPayment,
                        mb_entity_code: this.mb_entity_code,
                        mb_payment_reference: this.mb_payment_reference,
                    })
                    .then(response => {
                        this.$router.push({name: 'movements'});
                        this.$socket.emit('movement_created', response.data.data);
                    })
                    .catch(error => {
                        this.$toasted.error('Something went wrong!');
                    })
                }
            }
        }
        
    },
    created() {
        this.getCategories();
    },
    sockets: {
        connect() {
            console.log("socket connected (socket ID =  " + this.$socket.id + ")");
        }
    }
}
</script>
