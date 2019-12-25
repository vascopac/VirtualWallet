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
      :rules="valueRules"
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
          required
    ></v-select>
    <v-select
          v-model="typeOfMovement"
          :items="typesMovement"
          label="Select the movement type"
          required
    ></v-select>
    <template v-if="typeOfMovement.value == '0'">
        <v-select
          v-model="typeOfPayment"
          :items="typesPayment"
          label="Select the payment type"
          required
        ></v-select>
        <v-text-field 
            v-model="iban"
            label="IBAN"
            v-if="typeOfPayment.value == 'bt'"
            :rules="ibanRules"
            required
        ></v-text-field>
        <template v-if="typeOfPayment.value == 'mb'">
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
    <template v-if="typeOfMovement.value == '1'">
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
            valueRules: [
                value => parseFloat(value) >= 0.01 && parseFloat(value) <= 5000 || "Value must be bigger then 0,01€ and lower than 5000€"
            ],
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
            if(this.typeOfMovement.text == 'Transfer'){
                
                /*axios.post('', {
                    wallet_id: this.user[0].id,
                    type: 'e',
                    value: this.value,
                    description: this.description,
                    category_id: this.category.id,
                    transfer: this.typesMovement.id,

                })*/
            } else if (this.typeOfMovement.text == 'Payment to external entity'){
                console.log('2');
            }
        }
        
    },
    created() {
        this.getCategories();
    },
}
</script>
