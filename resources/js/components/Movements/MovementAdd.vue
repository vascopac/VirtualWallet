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
    <v-combobox
          v-model="category"
          :items="categories"
          item-text="name"
          item-value="id"
          label="Select the category of expense"
          required
    ></v-combobox>
    <v-combobox
          v-model="typeOfMovement"
          :items="typesMovement"
          label="Select the movement type"
          required
    ></v-combobox>
    <template v-if="typeOfMovement.value == '0'">
        <v-combobox
          v-model="typeOfPayment"
          :items="typesPayment"
          label="Select the payment type"
          required
        ></v-combobox>
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
        }
    },
    created() {
        this.getCategories();
    },
}
</script>
