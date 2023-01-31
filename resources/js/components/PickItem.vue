<template>
    <div class="">
        <div class="bg-success bg-opacity-25 p-2 rounded my-2 row" v-if="added">
            <span class="col-7 text-dark">
                {{ item.name }}<small class="text-dark fw-bolder"> x{{ item.quantity }}</small>
            </span>
            <span class="col-5 float-end">@{{ purpose === 'purchase' ? item.sell_price : item.buy_price }}</span>
            <span class="fw-bold text-center h5 col-12 mb-0">{{ amount }}</span>
        </div>
        <div v-else>
            <div class="form-floating col-lg-9 mb-3">
                <input type="text" class="form-control" id="itemInput"
                       v-model="searchTerm" @keyup="searchItem" >
                <label for="itemInput">Item Name</label>
                <div class="panel-footer" v-if="searchResults.length">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="res in searchResults" :key="res.id" @click="selectItem(res)">
                            {{ res.name }}(Ksh.{{ purpose === 'purchase' ? res.sell_price : res.buy_price }})
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2" v-if="item !== null">
                <div class="form-floating">
                    <input type="number" class="form-control" id="quantity" min="1"
                           v-model="quantity" :max="item.stock_count">
                    <label for="quantity">Quantity</label>
                </div>
            </div>
            <div v-if="canAdd">
                <button class="btn btn-primary" @click="addItem">Add</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'purpose'
    ],
    data: function () {
        return {
            searchTerm: null,
            searchResults: [],
            quantity: 1,
            canAdd: false,
            item: null,
            added: false,
            amount: null,
        }
    },
    methods: {
        searchItem: async function () {
            this.searchResults = [];
            if (this.searchTerm.length > 2) {
                await axios.get('/search', {params: {query: this.searchTerm, sale: this.purpose === 'purchase'}})
                    .then(r => {
                        this.searchResults = r.data;
                    })
            }
        },
        selectItem: function(res) {
            this.searchResults = [];
            this.searchTerm = res.name + " (Ksh." + (this.purpose === 'purchase' ? res.sell_price : res.buy_price) + ")";
            this.item = res;
            this.canAdd = true;
        },
        addItem: function () {
            if (parseInt(this.quantity) > parseInt(this.item.stock_count)) {
                return this.$swal({
                    icon: 'error',
                    text: 'Quantity picked cannot be more than the available stock'
                })
            }
            this.amount = this.purpose === 'purchase' ? (this.item.sell_price * this.quantity) : (this.item.buy_price * this.quantity);
            this.$set(this.item, 'quantity', parseInt(this.quantity));
            this.$set(this.item, 'amount', parseInt(this.amount));
            this.$emit('add-item', this.item);
            this.added = true;
        }
    },
}
</script>

<style scoped>

</style>
