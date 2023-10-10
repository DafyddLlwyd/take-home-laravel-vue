<template>
    <div class="cart">
        <header class="bg-info">
            <h2>Shopping Cart</h2>
            <button class="btn float-right btn-sm text-white" @click="hideCart">
                <span class="fas fa-times"></span>
            </button>
        </header>
        <div class="text-center text-muted my-5" v-if="items.length === 0">
            <h4>Cart is empty</h4>
            <span class="fas fa-shopping-cart fa-4x mt-2"></span>
        </div>
        <div v-else class="mt-5">
            <ul class="list-unstyled">
                <li v-for="item in groupedItems">
                    <img :src="cartImage(item.photo)" :alt="item.name + ' photo'" class="img-thumbnail">
                    {{ item.name }} - £{{ $getPrice(item.price) }}
                    <span class="float-right">
                        {{ item.quantity }}
                        <i class="fas fa-minus-circle text-danger ml-2" @click="removeItem(item.id)"></i>
                    </span>
                </li>
            </ul>
            <hr>
            <h3>Total: £{{ getTotalPrice() }}</h3>
            <button class="btn btn-primary float-right" @click="buyNow">Buy Now</button>
        </div>
    </div>
</template>


<script>

export default {
    name: "ShoppingCart",
    props: {
        userId: {
            type: Number,
            default: 0,
        },
    },
    data () {
        return {
            items: [],
        }
    },
    methods: {
        hideCart() {
            this.$emit('hide-cart');
        },

        getTotalPrice(formatted = true) {
            let total = 0;
            this.items.forEach(item => {
                total += parseInt(item.price);
            });
            // convert to string
            total = total.toString();

            return formatted ? this.$getPrice(total) : total
        },

        buyNow() {
            Toast.fire({
                icon: 'success',
                title: 'Thank you for your purchase, our team will be in touch with your invoice shortly.'
            });

            // make API call to buy now
            axios.post('/api/public/cart/submit', {
                items: this.groupedItems,
                userId: this.userId,
                total: this.getTotalPrice(false),
            });
            // then clear cart
            this.items = [];
        },

        removeItem(id) {
            // remove only the first item with matching id
            const index = this.items.findIndex(item => item.id === id);
            this.items.splice(index, 1);
        },

        async getCart() {
            try {
                const response = await axios.get("/api/public/cart/" + this.userId);
                this.items = response.data
            } catch (error) {
                this.error = error.response.data.error;
            }
        },

        async saveCart() {
            try {
                const itemIds = this.items.map(item => item.id);

                await axios.post("/api/public/cart", {userId: this.userId, items: itemIds});
            } catch (error) {
                this.error = error.response.data.error;
            }
        },

        cartImage(image) {
            // if url contains 'product' add ../ to the start
            const prefix = window.location.pathname.includes('product') ? '../../' : '';

            return  prefix + this.$imageLink(image);
        },
},

    mounted() {
        // get cart from database
        this.getCart();

        this.$root.$on('add-to-cart', data => {
            this.items.push(data);
        });
    },

    computed: {
        groupedItems() {
            return this.items.reduce((total, item) => {
                if (!total[item.id]) {
                    total[item.id] = {
                        ...item,
                        quantity: 1,
                    };
                } else {
                    total[item.id].quantity++;
                }

                return total;
            }, {});
        },
    },

    watch: {
        items(newValue, oldValue) {
            // save cart to database
            this.saveCart();

            // emit item count to button
            this.$emit('item-count', newValue.length);
        }
    }
}
</script>

<style scoped lang="scss">
.cart {
    header {
        margin: -1rem;
        padding: 1rem 1rem .75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;

    }
    height: calc(100vh - 55px); // subtract navbar height
    background-color: #fff;
    position: absolute;
    width: 300px;
    right: 0;
    top: 55px; // offset by navbar height
    border-left: 1px solid #000;
    z-index: 1000;
    padding: 1rem;
    overflow-y: scroll;
}
</style>

