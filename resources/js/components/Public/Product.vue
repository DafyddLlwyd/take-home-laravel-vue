<template>
    <div>
        <div v-if="!product && !error" class="spinner">
            <span class="fas fa-spinner fa-spin fa-5x text-muted"></span>
        </div>
        <div v-if="error" class="spinner">
            <span class="fas fa-sad-tear fa-4x mb-2 text-muted"></span><br>
            <p class="lead">{{error}}</p>
        </div>
        <div class="container" v-else>
            <div v-if="product" class="row p-2 align-items-center">
                <div class="col-md-7 image-wrapper">
                    <img class="img-thumbnail" :src="'../../' + $imageLink(product.photo)" alt="...">
                </div>
                <div class="col-md-5">
                    <h1 class="display-5 fw-bolder">
                        {{product.name}}
                    </h1>
                    <div class="mb-3">
                        <span>£{{$getPrice(product.price)}}</span>
                    </div>
                    <p class="lead">
                        {{product.description}}
                    </p>
                        <button class="btn btn-primary float-right mt-3" type="button" @click="$root.$emit('add-to-cart', product)">
                            Add to cart
                        </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "PublicProduct",
        data () {
            return {
                product: null,
                error: null,
            }
        },
        methods: {
            // get product by id
            async getProduct() {
                // get id from url
                let id = this.$route.params.id;
                try {
                    const response = await axios.get("/api/public/product/" + id);
                    this.product = response.data
                } catch (error) {
                    this.error = error.response.data.error;
                }
            },
        },

        async mounted() {
            await this.getProduct();
        },
    }
</script>

<style scoped lang="scss">
.image-wrapper {
    img {
        min-height: 350px;
        object-fit: cover;
    }
}

</style>
