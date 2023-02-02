<template>
    <div class="card">
        <div class="card-body">
            <div class="form-floating col-lg-5">
                <input type="text" class="form-control" :id="'qty'+ product.id" min="1"
                       v-model="quantity">
                <label :for="'qty'+ product.id">Quantity</label>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" @click="reduceStock()">Reduce</button>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'product'
    ],
    data() {
        return {
            quantity: null,
        }
    },
    methods: {
        reduceStock() {
            this.$swal({
                icon: "warning"
            }).then((v) => {
                if (v === true) {
                    axios.post('/stock/' + this.product.id, {
                        quantity: this.quantity
                    }).then(r => {
                        this.quantity = null;
                        this.$swal("Stock has been updated")
                    })
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
