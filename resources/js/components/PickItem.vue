<template>
    <div class="">
        <div class="bg-success bg-opacity-25 p-2 rounded my-2 row" v-if="added">
            <span class="col-7 text-dark">
                {{ item.name }}<small class="text-dark">x{{ item.quantity }}</small>
            </span>
            <span class="col-5 float-end">@{{ item.sell_price }}</span>
            <span class="fw-bold text-center h5 col-12 mb-0">{{ item.quantity * item.sell_price }}</span>
        </div>
        <div v-else>
            <div class="form-floating col-lg-9 mb-3">
                <input type="text" class="form-control" id="itemInput"
                       v-model="searchTerm" @keyup="searchItem" >
                <label for="itemInput">Item Name</label>
                <div class="panel-footer" v-if="searchResults.length">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="res in searchResults" :key="res.id" @click="selectItem(res)">
                            {{ res.name }}(Ksh.{{ res.sell_price }})
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2" v-if="item !== null">
                <input id="qtyInput" type="number" class="form-floating form-control mb-3"
                       v-model="quantity" :max="item.stock" min="1">
            </div>
            <div v-if="canAdd">
                <button class="btn btn-primary" @click="addItem">Add</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            searchTerm: null,
            searchResults: [],
            quantity: 1,
            canAdd: false,
            item: null,
            added: false
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
            this.searchTerm = res.name + " (Ksh." + res.sell_price + ")";
            this.item = res;
            this.canAdd = true;
        },
        addItem: function () {
            this.$set(this.item, 'quantity', parseInt(this.quantity));
            this.$emit('add-item', this.item);
            this.added = true;
        }
    },
}
</script>

<style scoped>

</style>
