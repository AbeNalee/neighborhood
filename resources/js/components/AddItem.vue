<template>
    <div class="card">
        <div class="card-header">
            Add To Stock
        </div>
        <div class="card-body" :key="key">
            <div class="row mb-3">
                <div class="form-floating col-lg-7">
                    <input type="text" class="form-control" id="itemInput"
                           v-model="searchTerm" @keyup="searchItem" >
                    <label for="itemInput">Item Name</label>
                    <div class="panel-footer" v-if="searchResults.length">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="(res, i) in searchResults" :key="res.id + i" @click="selectItem(res)">
                                {{ res.name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-floating col-lg-5">
                    <input type="text" class="form-control" id="quantity" min="1"
                           v-model="size">
                    <label for="quantity">Size(in ml)</label>
                </div>
            </div>
            <div class="row form-group mb-2">
                <div class="col-6" v-if="buyPrice !== null">
                    <span>Buy @Ksh {{ buyPrice }}</span>
                </div>
                <div class="col-6" v-if="sellPrice !== null">
                    <span>Sell @Ksh {{ sellPrice }}</span>
                </div>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="alcoholic" id="alcoholCheck">
                <label class="form-check-label" for="alcoholCheck">
                    Alcoholic?
                </label>
            </div>
            <div class="row mb-2" v-if="buyPrice !== null && sellPrice !== null">
                <span>Profit Margin: Ksh. {{ sellPrice - buyPrice}}</span>
            </div>
            <div class="row mb-3">
                <div class="form-floating col-lg-7">
                    <input type="number" class="form-control" id="itemPrice"
                           v-model="newSpend">
                    <label for="itemPrice">Total Spent</label>
                </div>
                <div class="form-floating col-lg-5">
                    <input type="number" class="form-control" id="quantity" min="1"
                           v-model="quantity">
                    <label for="quantity">Quantity</label>
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="itemSell"
                    v-model="sellPrice">
                <label for="itemSell">Sell Price</label>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" @click="addItem()">Complete</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchTerm: null,
            searchResults: [],
            exists: false,
            buyPrice: null,
            sellPrice: null,
            quantity: 1,
            newSpend: null,
            size: null,
            key: 0,
            alcoholic: true,
        }
    },
    methods: {
        searchItem: async function () {
            this.searchResults = [];
            if (this.searchTerm.length > 2) {
                await axios.get('/search', {params: {query: this.searchTerm}})
                    .then(r => {
                        this.searchResults = r.data;
                    })
            }
        },
        selectItem: function(res) {
            this.searchResults = [];
            var nameArr = res.name.split(" ");
            this.size = nameArr.pop();
            this.searchTerm = nameArr.join(" ");
            this.buyPrice = res.buy_price;
            this.sellPrice = res.sell_price;
        },
        addItem() {
            if (this.searchTerm == null || this.buyPrice == null || this.sellPrice == null) {
                return this.$swal({
                    title: 'error',
                    icon: 'error',
                    text: 'You are missing important information'
                })
            }
            axios.post('/stock', {
                name: this.searchTerm,
                size: this.size,
                buy_price: this.buyPrice,
                sell_price: this.sellPrice,
                quantity: this.quantity,
                alcoholic: this.alcoholic,
            }).then(r => {
                this.key += 1;
                this.$forceUpdate();
                this.$swal("added successfully");
            })
        }
    },
    watch: {
        newSpend(val) {
            this.buyPrice = val / this.quantity;
        },
        quantity(val) {
            this.buyPrice = this.newSpend / val;
        }
    }
}
</script>

<style scoped>

</style>
