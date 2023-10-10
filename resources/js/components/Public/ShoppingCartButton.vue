<template>
    <div>
        <button class="float-right btn btn-outline-light" @click="toggleCart">
            <span class="fas fa-shopping-cart"></span>
        </button>
        <span class="badge badge-light">{{ itemCount }}</span>
        <aside v-show="cartVisible">
            <shopping-cart :user-id="user.id" @item-count="updateItemCount" @hide-cart="toggleCart"></shopping-cart>
        </aside>
    </div>
</template>

<script>
import ShoppingCart from "./ShoppingCart.vue";

export default {
    name: "ShoppingCartButton",
    components: {
        ShoppingCart,
    },
    props: {
        user: {
            type: Object,
            default: 0,
        },
    },
    data () {
        return {
            cartVisible: false,
            itemCount: 0,
        };
    },
    methods: {
        toggleCart() {
            this.cartVisible = !this.cartVisible;
        },
        updateItemCount(count) {
            this.itemCount = count;
        },
    },
};
</script>

<style scoped lang="scss">
button {
    border-radius: 50%;
}
.badge {
    position: absolute;
    top: 10px;
    right: 10px;
}
</style>
