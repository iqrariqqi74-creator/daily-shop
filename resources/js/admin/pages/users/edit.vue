<template>
    <section class="content">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                <div>
                    <img width="100" :src="imageLoader" />
                    <p class="text-center mt-0">Please wait...</p>
                </div>
            </div>
            <div v-else>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow my-5 mx-5 mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="m-0 font-weight-bold text-primary">Create User</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-1">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" v-model="user.name"
                                                    @input="clearErrorMessages(user.name)" placeholder="Name"
                                                    :class="{ 'is-invalid': errorMessages.name, 'is-valid': user.name && !errorMessages.name }" />
                                                <small class="text-danger">
                                                    {{ errorMessages.name && errorMessages.name[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number</label>
                                                <input type="number" class="form-control" v-model="user.phone_number"
                                                    @input="clearErrorMessages(user.phone_number)"
                                                    placeholder="Phone Number"
                                                    :class="{ 'is-invalid': errorMessages.phone_number, 'is-valid': user.phone_number && !errorMessages.phone_number }" />
                                                <small class="text-danger">
                                                    {{ errorMessages.phone_number && errorMessages.phone_number[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="user.status"
                                                    :class="{ 'is-invalid': errorMessages.status, 'is-valid': user.status && !errorMessages.status }"
                                                    @change="clearErrorMessages(user.status)">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>

                                                <small class="text-danger">
                                                    {{ errorMessages.status && errorMessages.status[0] }}
                                                </small>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" v-model="user.email"
                                                    placeholder="Email"
                                                    :class="{ 'is-invalid': errorMessages.email, 'is-valid': user.email && !errorMessages.email }"
                                                    @input="clearErrorMessages(user.email)" />
                                                <small class="text-danger">
                                                    {{ errorMessages.email && errorMessages.email[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="password">password</label>
                                                <input type="password" class="form-control" v-model="user.password"
                                                    @input="clearErrorMessages(user.password)" placeholder="password"
                                                    :class="{ 'is-invalid': errorMessages.password, 'is-valid': user.password && !errorMessages.password }" />
                                                <small class="text-danger">
                                                    {{ errorMessages.password && errorMessages.password[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm password</label>
                                                <input type="password" class="form-control"
                                                    v-model="user.password_confirmation" placeholder="Confirm password"
                                                    :class="{ 'is-invalid': errorMessages.password_confirmation, 'is-valid': user.password_confirmation && !errorMessages.password_confirmation }" />
                                                <small class="text-danger">
                                                    {{ errorMessages.password_confirmation &&
                                                        errorMessages.password_confirmation[0] }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 p-0">
                                            <table v-show="!isLoading" class="table table-bordered border-0"
                                                id="dataTable">
                                                <tbody>
                                                    <tr>
                                                        <td class="border-0">
                                                            <ul class="p-0">
                                                                <li class="font-bold list-unstyled">
                                                                    <div class="bg-success text-white form-control">
                                                                        <label>Assign Roles</label>
                                                                    </div>
                                                                    <ul class="p-0">
                                                                        <li v-for="(role, index) in roles" :key="index"
                                                                            class="list-unstyled">
                                                                            <div class="form-control border-0">
                                                                                <label>
                                                                                    <input type="checkbox"
                                                                                        :value="role.id"
                                                                                        v-model="selected_roles.role_ids" />
                                                                                    {{ role.name }}
                                                                                </label>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div cols="12">
                                        <button class="btn btn-success" @click="updateUser">Save</button>
                                        <button class="btn btn-danger ml-3" @click="cancel">Cancel</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-danger">{{ authenticateError }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

import * as notify from "../../../utils/notify.js";
import axios from "axios";



export default {
    name: 'UserCreate',
    components: {
    },
    data() {
        return {
            errorMessages: '',
            authenticateError: '',
            isLoading: true,
            user: {},
            roles: [],
            invalidObject: false,
            selected_roles: {
                role_ids: []
            },
            imageLoader: "/images/Spinning arrows.gif"
        }
    },
    methods: {
        async updateUser() {
            try {
                this.user.role_ids = this.selected_roles.role_ids;
                const response = await axios.put(`users/${this.user.id}`, this.user);
                if (response.data.status == 'success') {
                    this.$toast.success(
                        response.data.message,
                        {
                            position: 'top-right',
                            autoClose: 3000,
                        }
                    )
                    this.$router.push('/admin/users');
                }
            }
            catch (error) {
                if (error.response.status == 403) {
                    this.errorMessages = '',
                        this.authenticateError = error.response.data.message;
                    this.$toast.warning(
                        error.response.data.message,
                        {
                            position: 'top-right',
                            autoClose: 3000,
                        }
                    );
                } else {
                    this.authenticateError = '';
                    this.errorMessages = error.response.data.errors;
                }
            }
        },
        cancel() {
            this.$router.push('/admin/users');
        },

        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `role`,
                        },
                    })
                    .then((response) => {
                        this.companies = response.data.companies;
                        this.roles = response.data.roles;
                        this.isLoading = false;
                    });
            } catch (error) {
                this.isLoading = true;
                this.$swal({
                    title: "Error!",
                    html: error.response.data.message,
                    icon: "error",
                    confirmButtonText: "Ok",
                });
                this.isLoading = false;
            }
        },

        clearErrorMessages(key) {
            this.errorMessages[key] = null
        },


        async getUser(user_id) {
            try {
                await axios.get(`users/${user_id}`).then(response => {
                    this.user = response.data.user
                    let role_ids = [];
                    response.data.user.roles.forEach(function (role) {
                        role_ids.push(role.id);
                    });
                    this.selected_roles.role_ids = role_ids;
                    this.isLoading = false;
                }).catch(error => {
                    this.invalidObject = true;
                    this.isLoading = false;
                    this.$swal({
                        title: "Warning!",
                        html: error.response.data.message,
                        icon: "warning",
                        button: "OK",
                    });
                })
            }
            catch (error) {
                this.$swal({
                    title: "Warning",
                    html: error.response.data.message,
                    icon: "warning",
                    button: "OK",
                });
            }
        },

    },
    mounted() {
        this.getUser(this.$route.params.id);
        this.getOptions();
    }
};
</script>

<style type="text/css">
.fas,
.text-info {
    cursor: pointer;
}
</style>
