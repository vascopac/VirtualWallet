<template>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>ID</th>
                <th>Type</th>
                <th>Transfer Email</th>
                <th>Type of payment</th>
                <th>Category</th>
                <th>Start Balance</th>
                <th>End Balance </th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="movement in movements"  :key="movement.id">
                <td>{{ movement.date }}</td>
                <td>{{ movement.id }}</td>
                <td>{{ movement.type }}</td>
                <td>{{  }}</td>
                <td> {{ movement.type_payment }}</td>
                <td>{{  }}</td>
                <td>{{ movement.start_balance }}</td>
                <td>{{ movement.end_balance }}</td>
                <td>{{ movement.value }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data: function(){
        return {
            wallet: '',
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

<style scoped>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        border: 1px solid #ddd;
    }
</style>