<template>
    <div class="card px-0" :key="key">
        <div class="card-header">
            {{ purpose === 'purchase' ? 'Make Sale' : 'Update Stock' }}
            <span class="bg-info float-end py-0 px-2 rounded">Ksh. {{ amount }}</span>
        </div>
        <div class="card-body">
            <pick-item :purpose="purpose" v-on:add-item="addItem" v-for="num in count" :key="num"></pick-item>
        </div>
        <div class="card-footer" v-if="items.length">
            <div class="row d-flex justify-content-between my-2">
                <div class="col-5">
                    <select class="form-select" v-model="payMethod">
                        <option value="Cash">Cash</option>
                        <option value="Mpesa">Mpesa</option>
                    </select>
                </div>
                <div class="col-5 d-flex justify-content-end align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" v-model="paid" id="flexSwitchCheckChecked">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Paid</label>
                    </div>
                </div>
            </div>
            <div class="row px-2 my-2" v-if="!paid">
                <div class="form-floating mb-2 px-1">
                    <input type="text" class="form-control" id="nameInput" v-model="debtorName">
                    <label for="nameInput">Debtor Name</label>
                </div>
                <div class="form-floating mb-2 px-1">
                    <input type="text" class="form-control" id="phoneInput" v-model="debtorPhone">
                    <label for="phoneInput">Debtor Phone (optional)</label>
                </div>
            </div>
            <div class="row px-2 mt-4 d-flex justify-content-end">
                <div class="col-6">
                    <span class="btn btn-secondary w-100" v-if="processing">Processing....</span>
                    <button class="btn btn-primary w-100" @click="doTransact" v-else>Complete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PickItem from "./PickItem";
export default {
    components: {PickItem},
    props: [
        'purpose'
    ],
    data: function () {
        return {
            items: [],
            count: 1,
            amount: 0,
            key: 1,
            processing: false,
            payMethod: 'Cash',
            paid: true,
            debtorName: null,
            debtorPhone: null,
        }
    },
    methods: {
        addItem: function (value) {
            this.items.push(value);
            this.amount += value.amount;
            this.count++;
        },
        doTransact: async function() {
            this.processing = true;
            var postData = {
                items: this.items,
                amount: this.amount,
                purpose: this.purpose,
                payMethod: this.payMethod,
                paid: this.paid
            };

            if (!this.paid) {
                postData = {
                    ...postData,
                    debtorName: this.debtorName,
                    debtorPhone: this.debtorPhone
                }
            }
            await axios.post('/transact', postData)
                .then(r => {
                if (r.data.status === 'success') {
                    this.$swal('purchase completed and stocks updated');
                    this.items = [];
                    this.count = 1;
                    this.key += 1;
                    this.amount = 0;
                    this.processing = false
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
