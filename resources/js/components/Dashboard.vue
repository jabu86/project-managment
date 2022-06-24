<template>
    <div class="container mt-5">
        <h1 class="h2">Dashboard</h1>
        <p>
            <span class="h3">Welcome</span>{{ " " }}
            <strong>{{ this.$gate.user.name }}</strong>
        </p>

        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ product_count }}</h3>

                        <p>All Products</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <router-link to="/product" class="small-box-footer"
                        >More info <i class="fas fa-arrow-circle-right"></i
                    ></router-link>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ users_count }}</h3>

                        <p>User Registrations</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <router-link to="/users" class="small-box-footer"
                        >More info <i class="fas fa-arrow-circle-right"></i
                    ></router-link>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Price</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products.data" :key="product">
                            <td>{{ product.name }}</td>
                            <td>{{ product.sku }}</td>
                            <td>{{ product.price }}</td>
                            <td>
                                {{
                                    $filters.stringTrancate(
                                        product.description,
                                        20
                                    )
                                }}
                            </td>
                            <td>{{ $filters.dateFormat(product) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <Pagination
                            :data="products"
                            @pagination-change-page="loadDashboardInfo"
                        />
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            product_count: "",
            users_count: "",
            products: {},
        };
    },
    created() {
        this.loadDashboardInfo();
    },
    methods: {
        loadDashboardInfo(page = 1) {
            axios.get(`api/dashboard?page=${page}`).then((data) => {
                this.product_count = data.data.products.length;
                this.users_count = data.data.users.length;
                this.products = data.data.userProducts;
                console.log(data.data.userProducts);
            });
        },
    },
    mounted() {
        console.log("This is a component dashboard");
    },
};
</script>
