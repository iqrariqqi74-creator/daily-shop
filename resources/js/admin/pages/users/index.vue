<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mx-5 my-5 mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-1">
                                    <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                                </div>

                                <div class="col-md-5">
                                    <form class="">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2" v-model="searchUser"
                                                @input="handleUserSearch">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-2">

                                </div>
                                <div class="col-md-4 text-right"><span class="text-info" @click="goToCreatePage"><i
                                            class="fas fa-plus"></i> Add New</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                                    <div>
                                        <img width="100" :src="loaderImage" />
                                        <p class="text-center mt-0">Please wait...</p>
                                    </div>
                                </div>
                                <table v-show="!isLoading" class="table table-striped" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>
                                                Name
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('name', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('name', 'desc')"></i>
                                            </th>
                                            <th>
                                                Phone Number
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('phone_number', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('phone_number', 'desc')"></i>
                                            </th>
                                            <th>
                                                Email
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('email', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('email', 'desc')"></i>
                                            </th>
                                            <th>
                                                Status
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'desc')"></i>
                                            </th>
                                            <th>Role</th>

                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="users.length == 0">
                                            <td colspan="10">
                                                <h4 class="text-center">No data found.</h4>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(user, index) in users" :key="user.id">
                                            <td>
                                                {{ user.name }}
                                            </td>
                                            <td>
                                                {{ user.phone_number }}
                                            </td>
                                            <td>
                                                {{ user.email }}
                                            </td>
                                            <td>
                                                <span class="badge badge-success ml-3"
                                                    v-if="user.status == 1">Active</span>
                                                <span class="badge badge-danger ml-3"
                                                    v-if="user.status == 0">Inactive</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info ml-3" v-for="(role, index) in user.roles"
                                                    :key="index"> {{ role.name }}
                                                </span>
                                            </td>

                                            <td>
                                                <i class="fas fa-edit text-primary" @click="editPage(user)"></i>
                                                <i class="fas fa-trash text-danger mx-2"
                                                    @click="deleteUser(user, index)"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div v-show="!isLoading">
                                <pagination :totalPages="totalPages" :perPage="50" :currentPage="currentPage"
                                    @pageChanged="onPageChange" />
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
import Pagination from "../../pages/Pagination.vue"

export default {
    name: 'UsersIndex',
    components: {
        Pagination
    },
    data() {
        return {
            errorMessage: '',
            debug: '',
            users: [],
            companies: [],
            totalPages: 0,
            currentPage: 1,
            isLoading: true,
            params: {
                page: 1,
            },
            searchUser: '',
            loaderImage: "/images/Spinning arrows.gif",
            companyFilter: ''

        }
    },
    methods: {
        async getUsers() {
            try {
                this.isLoading = true
                await axios.get("users", {
                    params: this.params
                }).then(response => {
                    this.users = response.data.users;
                    this.totalPages = response.data.total_pages;
                    this.isLoading = false;
                })
            }
            catch (error) {
                this.users = []
                notify.authError(error);
                this.isLoading = false

            }
        },
        async deleteUser(user, index) {
            let response = await this.$swal({
                title: "Are you sure ?",
                text: 'You want to delete this user?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !",
                cancelButtonText: "No, cancel !",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    try {
                        axios.delete(`users/${user.id}`).then(response => {
                            this.$swal({
                                title: 'Success!',
                                text: 'User deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            this.products.splice(index, 1);
                        }).catch(error => {
                            this.$swal({
                                title: 'Warning!',
                                html: error.response.data.message,
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                    catch (error) {
                        this.$swal({
                            title: 'Warning!',
                            html: error.response.data.message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        },
        onPageChange(page) {
            this.params.page = page;
            this.currentPage = page;
            this.getUsers();
        },

        goToCreatePage() {
            this.$router.push('/admin/users/create');
        },
        editPage(user) {
            this.$router.push(`/admin/users/${user.id}/edit`);
        },

        async handleUserSearch() {
            this.params.search = this.searchUser;
            this.getUsers();
        },

        async filterCompanies() {
            this.params.filter = this.companyFilter;
            this.getUsers();
        },
        sortBy(column, order) {
            this.params.sort_by = column;
            this.params.order = order;
            this.getUsers();
        },
        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `company,role`,
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
    },
    mounted() {
        this.getUsers();
        // this.getOptions();

    }
};
</script>

<style type="text/css">
.fas,
.text-info {
    cursor: pointer;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    color: gray;
}
</style>
