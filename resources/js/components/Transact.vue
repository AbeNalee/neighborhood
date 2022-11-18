<template>
    <div class="card">
        <div class="card-header">
            {{ purpose === 'purchase' ? 'Make Sale' : 'Update Stock' }}
            <span class="bg-info float-end py-0 px-2 rounded">Ksh. {{ amount }}</span>
        </div>
        <div class="card-body">
            <pick-item :purpose="purpose" v-on:add-item="addItem" v-for="num in count" :key="num"></pick-item>
        </div>
        <div class="card-footer" v-if="items.length">
            <button class="btn btn-primary" @click="doTransact">Complete</button>
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
        }
    },
    methods: {
        addItem: function (value) {
            this.items.push(value);
            this.amount += value.amount;
            this.count++;
        },
        doTransact: async function() {
            await axios.post('/transact', {
                items: this.items,
                amount: this.amount,
                purpose: this.purpose
            }).then(r => {
                if (r.data.status === 'success') {
                    alert('purchase completed and stocks updated');
                    this.items = [];
                    this.count = 1;
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
