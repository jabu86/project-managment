<template>
    <div class="container mt-5">
        <div v-if="$gate.isAdmin()">
            <h1 class="h2">Products</h1>
            <!-- Button trigger modal -->
            <button
                type="button"
                class="btn btn-primary float-right mb-2"
                @click="openModal"
            >
                Add Product<i class="fas fa-shopping-cart fa-fw dark"></i>
            </button>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Price</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                            <th scope="col">Modify</th>
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
                            <td>
                                <a href="#" @click="editModal(product)">
                                    <i class="fa fa-edit blue"></i> </a
                                >/
                                <a href="#" @click="deleteUser(product.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <Pagination :data="products" @pagination-change-page="loadProducts" />
                    </tfoot>
                </table>
            </div>
        </div>
        <div v-if="!$gate.isAdmin()"><NotFound /></div>
        <!-- Modal -->
        <div
            class="modal fade"
            id="userModal"
            tabindex="-1"
            aria-labelledby="userModal"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="newUserModal"
                            v-show="!editmode"
                        >
                            Create Product
                        </h5>
                        <h5
                            class="modal-title"
                            id="newUserModal"
                            v-show="editmode"
                        >
                            Edit Product
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <form
                        @submit.prevent="
                            editmode ? updateProducts() : createProduct()
                        "
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <AlertError :form="form" />
                            <!-- <AlertErrors :form="form" /> -->
                            <!-- <AlertSuccess :form="form" message="Your changes have beend saved!" /> -->

                            <div class="mb-3">
                                <label for="name" class="form-label"
                                    >Name</label
                                >
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                />
                                <HasError :form="form" field="name" />
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label"
                                    >Price</label
                                >
                                <input
                                    id="price"
                                    v-model="form.price"
                                    type="text"
                                    name="price"
                                    class="form-control"
                                />
                                <HasError :form="form" field="price" />
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label"
                                    >Description</label
                                >
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    name="description"
                                    class="form-control"
                                ></textarea>
                                <HasError :form="form" field="description" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Close
                            </button>

                            <button
                                v-show="!editmode"
                                type="submit"
                                class="btn btn-success"
                            >
                                Save
                            </button>
                            <button
                                v-show="editmode"
                                type="submit"
                                class="btn btn-info"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { Modal } from "bootstrap";
export default {
    data: () => ({
        products: {},
        modal: null,
        editmode: false,
        form: new Form({
            id: "",
            name: "",
            size: "",
            color: "",
            price: "",
            description: "",
            user_id:""
        }),
    }),
    created() {
        this.$Progress.start();
        this.loadProducts();

        this.$Progress.finish();
    },
    methods: {
        editModal(product) {
            this.editmode = true;
            this.form.clear();
            this.modal = new Modal(document.getElementById("userModal"), {
                keyboard: false,
            });
            this.modal.show();
            this.form.fill(product);
        },
        openModal() {
            this.editmode = false;
            this.form.reset();
            this.form.clear();
            this.modal = new Modal(document.getElementById("userModal"), {
                keyboard: false,
            });
            this.modal.show();
        },
        closeModal() {
            this.modal.hide();
        },
        loadProducts(page = 1) {
            if (this.$gate.isAdmin) {
                axios
                    .get(`api/products?page=${page}`)
                    .then((data) => {
                        this.products = data.data;
                        //console.log(data.data);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        //Create products
        createProduct() {
            this.$Progress.start();
            this.form
                .post("api/products")
                .then((data) => {
                    this.loadProducts();
                    toast.fire({
                        icon: "success",
                        title: "Product created successfully.",
                    });
                    this.form.reset();
                    //console.log(data.stauusText);
                    this.closeModal();
                    this.$Progress.finish();
                })
                .catch((err) => {
                    if (err.response && err.response.status === 422) {
                        this.$Progress.fail();
                    }
                });
        },
        updateProducts() {
            let id = this.form.id;
            this.form
                .put(`api/products/${id}`)
                .then((data) => {
                    this.loadProducts();
                    toast.fire({
                        icon: "success",
                        title: data.data.message,
                    });
                    this.form.reset();
                    //console.log(data.stauusText);
                    this.closeModal();
                    this.$Progress.finish();
                })
                .catch((err) => {
                    if (err.response && err.response.status === 422) {
                        this.$Progress.fail();
                    }
                });
        },

        deleteUser(id) {
            this.$Progress.start();
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                //Send request to server
                if (result.isConfirmed) {
                    this.form
                        .delete(`api/products/${id}`)
                        .then((data) => {
                            swal.fire("Deleted!", data.data.message, "success");
                            this.loadProducts();
                            this.$Progress.finish();
                        })
                        .catch((err) => {
                            if (err.response && err.response.status === 422) {
                                swal(
                                    "Failed",
                                    "There was something wronge.",
                                    "warning"
                                );
                                this.$Progress.fail();
                            }
                        });
                }
            });
        },
    },

    mounted() {
        this.user_id = this.$gate.user.id

    },
};
</script>
