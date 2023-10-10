<template>
    <div class="p-2">
        <div v-if="!invoices" class="spinner">
            <span class="fas fa-spinner fa-spin fa-5x text-muted"></span>
        </div>
        <div v-if="invoices && !invoices.data.data.length">
            <h1 class="text-center">No invoices found</h1>
        </div>
        <div v-if="invoices && invoices.data.data.length">
            <h1>Invoices</h1>
        </div>

        <div v-if="invoices && invoices.data.data.length">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Order Number</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="invoice in invoices.data.data" :key="invoice.id">
                    <td>{{invoice.id}}</td>
                    <td>{{invoice.order_id}}</td>
                    <td>{{invoice.amount}}</td>
                    <td>
                        <span class="text-capitalize">
                            {{invoice.status}}
                        </span>
                    </td>
                    <td>{{invoice.created_at}}</td>
                    <td>
                        <div class="btn-group">
                            <a :href="'/order/' + invoice.order_id" class="btn btn-outline-dark btn-sm">
                                <span class="fas fa-eye"></span>
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination :data="invoices.data" @pagination-change-page="getInvoices"></pagination>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
export default {
    name: "Invoices",
    components: {
    },
    data () {
        return {
            invoices: null,
        }
    },
    methods: {
        async getInvoices(page = 1) {
            try {
                this.invoices = await axios.get('/api/public/invoices?page=' + page);
            } catch (error) {
                console.error(error);
            }
        },
    },
    mounted() {
        this.getInvoices();
    },
};
</script>

<style scoped lang="scss">
</style>
