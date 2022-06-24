<template>
    <div class="container mt-5">
        <h1 class="h2">Porfile</h1>
        <div class="align-self-center">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">User Profile</li>
                    </ul>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form
                        @submit.prevent="updateInfo()"
                        @keydown="form.onKeydown($event)"
                    >
                        <div>
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
                            <!--<div class="mb-3">
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
                            </div>-->
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
                        <div class="footer m-2">
                            <router-link
                                class="btn btn-danger float-right"
                                aria-current="page"
                                to="/dashboard"
                            >

                                Back
                            </router-link>

                            <button type="submit" class="btn btn-success">
                                Save
                            </button>
                        </div>
                    </form>

                    <!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: new Form({
                id: "",
                name: "",
                password: "",
                email: "",
                type: "",
                photo: "",
            }),
        };
    },
    created() {
 this.$Progress.start();
        axios.get("api/profile").then(({ data }) => this.form.fill(data));
 this.$Progress.finish();
    },
    methods: {

        updateInfo() {
            this.$Progress.start();
            this.form
                .put("api/profile")
                .then((data) => {
                    toast.fire({
                        icon: "success",
                        title: data.data.message,
                    });
                    console.log(data);
                })
                .catch((err) => { this.$Progress.finish();});
        },
    },
};
</script>
