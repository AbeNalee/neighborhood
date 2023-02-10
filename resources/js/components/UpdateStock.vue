<template>
    <div class="card">
        <div class="card-header">
            <h3>{{purpose.toUpperCase() + " " + product.name }}</h3>
        </div>
        <div class="card-body">
            <div class="form-floating col-lg-5">
                <input type="text" class="form-control" :id="'qty'+ product.id" min="1"
                       v-model="quantity">
                <label :for="'qty'+ product.id">Quantity</label>
            </div>
        </div>
        <div class="card-footer">
            <span class="btn btn-secondary" v-if="processing">Processing....</span>
            <button class="btn btn-primary" @click="reduceStock()" v-else>{{ purpose }}</button>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'product', 'purpose'
    ],
    data() {
        return {
            quantity: null,
            processing: false,
        }
    },
    methods: {
        reduceStock() {
            this.$swal({
                icon: "warning",
                text: "This action will " + this.purpose + " the stock. Are you sure you wish to proceed?",
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((v) => {
                if (v.isConfirmed) {
                    this.processing = true;
                    axios.post('/stock/' + this.product.id, {
                        _method: 'put',
                        quantity: this.quantity,
                        purpose: this.purpose,
                    }).then(r => {
                        this.quantity = null;
                        this.processing = false;
                        this.$swal("Stock has been updated")
                    })
                }
            })
        }
    }
}
</script>

<style scoped>
.my-actions { margin: 0 2em; }
.order-1 { order: 1; }
.order-2 { order: 2; }
.order-3 { order: 3; }

.right-gap {
    margin-right: auto;
}
</style>
