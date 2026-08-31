<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4 mx-5 my-5">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="m-0 font-weight-bold text-primary">Roles</h6>
                                </div>
                                <div class="col-md-4 text-right"><span class="text-info" @click="goToCreatePage"><i
                                            class="fas fa-plus"></i> Add New</span></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
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
                                            <th> Assign Permissions </th>
                                            <th>
                                                Status
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'desc')"></i>
                                            </th>
                                            <th width="10">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(role, index) in roles" :key="index">
                                            <td>{{ role.name }}</td>
                                            <td>
                                                <span class="text-primary btn" @click="permissionPage(role)">Assign
                                                    Permissions</span>
                                            </td>
                                            <td>
                                                <span v-if="role.status === 1" class="badge badge-success">Active</span>
                                                <span v-else class="badge badge-danger">Inactive</span>
                                            </td>
                                            <td>
                                                <span class="d-flex justify-content-between">
                                                    <i class="fas fa-edit text-primary" @click="editPage(role)"></i>
                                                    <i class="fas fa-trash text-danger mx-2"
                                                        @click="deleteRole(role, index)"></i>
                                                </span>
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

import * as notify from "../../../utils/notify";
import axios from "axios";

import Pagination from '../../pages/Pagination.vue'

export default {
    name: 'RolesIndex',
    components: {
        Pagination
    },
    data() {
        return {
            errorMessage: '',
            debug: '',
            roles: [],
            totalPages: 0,
            currentPage: 1,
            isLoading: true,
            params: {
                page: 1,
            },
            loaderImage: "/images/Spinning arrows.gif",

        }
    },
    methods: {
        async getRoles() {
            try {
                this.isLoading = true

                await axios.get("roles", {
                    params: this.params
                }).then(response => {
                    this.roles = response.data.roles;
                    this.totalPages = response.data.total_pages;
                    this.isLoading = false;
                })
            }
            catch (error) {
                this.roles = []
                this.isLoading = false
                this.$swal({
                    title: 'Warning!',
                    html: error.response.data.message,
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            }
        },
        async deleteRole(role, index) {
            let response = await this.$swal({
                title: "Are you sure ?",
                text: 'You want to delete this role?',
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
                        axios.delete(`roles/${role.id}`).then(response => {
                            this.roles.splice(index, 1);
                            this.$swal({
                                title: 'Success!',
                                html: response.data.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
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
                            title: 'Error!',
                            html: error.response.data.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        },
        onPageChange(page) {
            this.params.page = page;
            this.currentPage = page;
            this.getRoles();
        },

        goToCreatePage() {
            this.$router.push('/admin/roles/create');
        },
        editPage(role) {
            this.$router.push(`/admin/roles/${role.id}/edit`);
        },
        permissionPage(role) {
            this.$router.push(`/admin/roles/${role.id}/permissions`);
        },
        sortBy(column, order) {
            this.params.sort_by = column;
            this.params.order = order;
            this.getRoles();
        },
    },
    mounted() {
        this.getRoles();
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
