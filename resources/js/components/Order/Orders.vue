<template>
    <div class="p-2">
        <div v-if="!orders" class="spinner">
            <span class="fas fa-spinner fa-spin fa-5x text-muted"></span>
        </div>
        <div v-if="orders && !filteredOrders.length">
            <h1 class="text-center">No Orders found</h1>
        </div>
        <div v-if="orders && filteredOrders.length">
            <h1>{{orderId ? 'Order ' + orderId : 'Orders'}}</h1>
        </div>

        <div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter
                </button>
                <div class="dropdown-menu">
                    <div class="dropdown-item">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="pending" v-model="filter.pending">
                            <label class="custom-control-label" for="pending">Pending</label>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="invoiced" v-model="filter.invoiced">
                            <label class="custom-control-label" for="invoiced">Invoiced</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="orders && filteredOrders.length">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>id</th>
                    <th>User</th>
                    <th>User Email</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="order in filteredOrders" :key="order.id" :class="order.status">
                    <td>{{order.id}}</td>
                    <td>
                         <span class="text-capitalize">
                            {{order.user.name}}
                         </span>
                    </td>
                    <td>{{order.user.email}}</td>
                    <td>{{order.total_price}}</td>
                    <td>
                        <span class="text-capitalize">
                            {{order.status}}
                        </span>
                    </td>
                    <td>
                        <span v-for="(item, id) in order.items">
                           <b>{{item.product.name}}</b> ({{item.quantity}}){{ id < order.items.length - 1 ? ', ' : '' }}
                        </span>
                    </td>
                    <td>{{formattedDate(order.created_at)}}</td>
                    <td class="d-flex">
                        <a href="#" @click="generateInvoice(order)" class="btn btn-outline-dark btn-sm">
                            <span class="fas fa-file-pdf fa-fw"></span>
                        </a>
                        <a v-show="order.status === 'invoiced'"
                           :href="downloadInvoiceUrl(order)"
                              class="btn btn-outline-dark btn-sm ml-2">
                            <span class="fas fa-download fa-fw"></span>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination v-show="!orderId" :data="orders.data" @pagination-change-page="getOrders"></pagination>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
export default {
    name: "Orders",
    components: {
    },
    data () {
        return {
            orders: null,
            filter: {
                pending: true,
                invoiced: true,
            },
            invoicePath: null,
        }
    },
    methods: {
        async getOrders(page = 1) {
            try {
                this.orders = await axios.get('/api/public/orders?page=' + page);
            } catch (error) {
                console.error(error);
            }
        },
        async generateInvoice(order) {
            try {
                await axios.post('/api/public/order/' + order.id + '/invoice/generate');

                Toast.fire({
                    icon: 'success',
                    title: 'Your invoice has been generated.'
                });

                // refresh the orders
                this.getOrders();

            } catch (error) {
                console.error(error);
            }
        },
        formattedDate(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        downloadInvoiceUrl(order) {
           return '/api/public/order/' + order.id + '/invoice/download'
        }
    },
    mounted() {
        this.getOrders();
    },

    computed: {
        filteredOrders() {
            //  if orderId then only return that order
            if (this.orderId) {
                return this.orders.data.data.filter(order => {
                    return order.id === parseInt(this.orderId);
                });
            }

            // map this.filter to an array of keys with true values
            const filters = Object.keys(this.filter).filter(key => this.filter[key]);

            return this.orders.data.data.filter(order => {
                return filters.includes(order.status);
            });
        },
        orderId() {
            return this.$route.params.id;
        }
    }
};
</script>

<style scoped lang="scss">
</style>
