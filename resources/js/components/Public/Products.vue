<template>
    <div class="mt-5">
        <div v-if="products == null" class="spinner">
            <span class="fas fa-spinner fa-spin fa-5x text-muted"></span>
        </div>
        <div class="card-container" v-else>
            <div v-for="product in products">
                <div class="card">
                    <header>
                        <img :src="'../' + $imageLink(product.photo)" class="card-img-top img-fluid" :alt="product.name + ' Image'">
                    </header>

                    <div class="card-body">
                        <div class="title-section">
                            <h5 class="card-title">
                                {{ product.name }}
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <!-- assuming pounds as currency for demo -->
                                £{{ $getPrice(product.price) }}
                            </h6>
                        </div>

                        <hr>

                        <p class="card-text">
                            {{ $shortDescription(product.description) }}
                        </p>

                        <hr>

                        <div class="footer">
                            <a :href="'store/product/' + product.id" class="btn btn-outline-dark">
                                See more
                            </a>
                            <button class="btn btn-primary" @click="$root.$emit('add-to-cart', product)">
                                Quick Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PublicProducts",
        data () {
            return {
                products: null,
            }
        },
        methods: {
            // get products
            async getProducts() {
                try {
                    const response = await axios.get("/api/public/products");
                    this.products = response.data;
                } catch (error) {
                    console.error("Error fetching products:", error);
                }
            },
        },

        async mounted() {
            await this.getProducts();
        },
    }
</script>

<style scoped lang="scss">

    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        height: calc(100vh - 100px);
        overflow-y: scroll;
    }

    .card {
        margin: 1rem;
        width: 22rem;

        hr {
            margin: 0 -1rem 1rem -1rem;
        }

        .title-section{
            align-items: baseline;
        }
        .title-section, .footer {
            display: flex;
            justify-content: space-between;
        }
        header {
            height: 150px;

            // set image to fill header
            img {
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
        }
        .card-text {
            min-height: 68px;
        }
    }

</style>
