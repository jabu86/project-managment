<template>
    <div class="container mt-5">
        <div v-if="$gate.isAdmin()">
            <h1 class="h2">Users</h1>
            <!-- Button trigger modal -->
            <button
                type="button"
                class="btn btn-primary float-right mb-2"
                @click="openModal"
            >
                Add New<i class="fas fa-user-plus fa-fw dark"></i>
            </button>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type</th>
                            <th scope="col">Date</th>
                            <th scope="col">Modify</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user">
                            <td>{{ user.id }}</td>
                            <td>{{ $filters.upText(user.name) }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ $filters.upText(user.type) }}</td>
                            <td>{{ $filters.dateFormat(user.created_at) }}</td>
                            <td>
                                <a href="#" @click="editModal(user)">
                                    <i class="fa fa-edit blue"></i> </a
                                >/
                                <a href="#" @click="deleteUser(user.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <Pagination
                            :data="users"
                            @pagination-change-page="loadUsers"
                        />
                    </tfoot>
                </table>
            </div>
        </div>
        <div v-if="!$gate.isAdmin()"><NotFound></NotFound></div>
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
                            Create New User
                        </h5>
                        <h5
                            class="modal-title"
                            id="newUserModal"
                            v-show="editmode"
                        >
                            Edit User
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <form
                        @submit.prevent="editmode ? updateUser() : createUser()"
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
                                <label for="email" class="form-label"
                                    >Email Address</label
                                >
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    class="form-control"
                                />
                                <HasError :form="form" field="email" />
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label"
                                    >Role</label
                                >
                                <select
                                    id="type"
                                    v-model="form.type"
                                    name="type"
                                    class="form-control"
                                >
                                    <option value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="author">Authour</option>
                                    <option value="user">User</option>
                                </select>
                                <HasError :form="form" field="type" />
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    name="password"
                                    class="form-control"
                                />
                                <HasError :form="form" field="password" />
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
        users: {},
        modal: null,
        editmode: false,
        form: new Form({
            id: "",
            name: "",
            password: "",
            email: "",
            type: "",
            photo: "",
        }),
    }),
    created() {
        this.$Progress.start();

        this.loadUsers();

        this.$Progress.finish();
    },
    methods: {
        editModal(user) {
            this.editmode = true;
            this.form.clear();
            this.modal = new Modal(document.getElementById("userModal"), {
                keyboard: false,
            });
            this.modal.show();
            this.form.fill(user);
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
        loadUsers(page = 1) {
            if (this.$gate.isAdmin) {
                axios
                    .get(`api/user?page=${page}`)
                    .then((data) => {
                        this.users = data.data;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
        createUser() {
            this.$Progress.start();
            this.form
                .post("api/user")
                .then((data) => {
                    this.loadUsers();
                    toast.fire({
                        icon: "success",
                        title: "User created successfully",
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
        updateUser() {
            let id = this.form.id;
            this.form
                .put(`api/user/${id}`)
                .then((data) => {
                    this.loadUsers();
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
                        .delete(`api/user/${id}`)
                        .then((data) => {
                            swal.fire("Deleted!", data.data.message, "success");

                            this.loadUsers();
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
        console.log("This is a component profile");
    },
};
</script>
