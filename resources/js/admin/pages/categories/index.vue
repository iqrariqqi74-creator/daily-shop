<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mx-5 my-5 mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                                </div>

                                <div class="col-md-3 text-center mb-3">
                                    <div class="btn-group status-tabs" role="group">
                                        <button class="btn" :class="getTabClass('')" @click="setStatusFilter('')">
                                            All ({{ totalCategories }})
                                        </button>

                                        <button class="btn" :class="getTabClass('active')"
                                            @click="setStatusFilter('active')">
                                            Active ({{ activeCount }})
                                        </button>

                                        <button class="btn" :class="getTabClass('inactive')"
                                            @click="setStatusFilter('inactive')">
                                            Inactive ({{ inactiveCount }})
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <form class="">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2" v-model="searchCategories"
                                                @input="handleCategoriesSearch">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-2 text-right">
                                    <span class="text-info" @click="goToCreatePage">
                                        <i class="fas fa-plus">
                                        </i> Add New</span>
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
                                                Image
                                            </th>
                                            <th>
                                                Name
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('name', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('name', 'desc')"></i>
                                            </th>
                                            <th>
                                                Sub Category
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('categories.parent_id', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('categories.parent_id', 'desc')"></i>

                                            </th>

                                            <th> Status
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 14.5px"
                                                    @click="sortBy('status', 'desc')"></i>
                                            </th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="categories.length == 0">
                                            <td colspan="10">
                                                <h4 class="text-center">No data found.</h4>
                                            </td>
                                        </tr>
                                        <tr v-for="(category, index) in categories" :key="category.id">

                                            <td>
                                                <span v-if="category.image">
                                                    <img :src="getImage(category.image)" width="50" height="50"
                                                        class="rounded-circle" alt="category Image" />
                                                </span>
                                                <span v-else class="badge badge-secondary">
                                                    No Image
                                                </span>
                                            </td>
                                            <td>
                                                {{ category.name }}
                                                <small class="d-block text-muted">{{ category.name }}</small>
                                            </td>

                                            <td>
                                                <template v-if="category.children && category.children.length">
                                                    <span v-for="child in category.children" :key="child.id"
                                                        class="badge badge-info mr-1 mb-1">
                                                        {{ child.name }}
                                                    </span>
                                                </template>

                                                <span v-else class="badge badge-secondary">
                                                    No Sub Category
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge badge-success"
                                                    v-if="category.status == 'active'">Active</span>
                                                <span class="badge badge-danger" v-else>Inactive</span>
                                            </td>
                                            <!-- ACTION -->
                                            <td>
                                                <i class="fas fa-eye text-info mx-2"
                                                    @click="viewCategory(category)"></i>
                                                <i class="fas fa-edit text-primary" @click="editPage(category)"></i>
                                                <i class="fas fa-trash text-danger mx-2"
                                                    @click="deleteCategory(category, index)"></i>
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
import { format } from 'date-fns';

export default {
    name: 'categoryIndex',
    components: {
        Pagination
    },
    data() {
        return {
            errorMessage: '',
            debug: '',
            categories: [],
            totalPages: 0,
            currentPage: 1,
            isLoading: true,
            params: {
                page: 1,
            },
            searchCategories: '',
            loaderImage: "/images/Spinning arrows.gif",
            statusFilter: '', // status filter for active/inactive categories

        }
    },
    computed: {
        totalCategories() {
            return this.categories.length;
        },
        activeCount() {
            return this.categories.filter(c => c.status === "active").length;
        },
        inactiveCount() {
            return this.categories.filter(c => c.status === "inactive").length;
        }
    },
    methods: {
        getImage(path) {
            if (path.startsWith('http')) {
                return path;
            }
            return 'http://127.0.0.1:8000/' + path;
        },
        async getCategories() {
            try {
                this.isLoading = true
                await axios.get("categories", {
                    params: this.params
                }).then(response => {
                    this.categories = response.data.categories;
                    this.totalPages = response.data.total_pages;
                    this.isLoading = false;
                })
            }
            catch (error) {
                this.categories = []
                notify.authError(error);
                this.isLoading = false;

            }
        },
        async deleteCategory(category, index) {
            let response = await this.$swal({
                title: "Are you sure ?",
                text: 'You want to delete this category?',
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
                        this.isLoading = true;
                        axios.delete(`categories/${category.id}`).then(response => {
                            this.$swal({
                                title: 'Success!',
                                text: 'category deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            this.categories.splice(index, 1);
                            this.isLoading = false;
                        }).catch(error => {
                            this.isLoading = false;
                            this.$swal({
                                title: 'Warning!',
                                html: error.response.data.message,
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                    catch (error) {
                        this.isLoading = false;
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
            this.getCategories();
        },

        goToCreatePage() {
            this.$router.push('/admin/categories/create');
        },
        editPage(category) {
            this.$router.push(`/admin/categories/${category.id}/edit`);
        },

        viewCategory(category) {
            this.$router.push(`/admin/categories/${category.id}/details`);
        },

        async handleCategoriesSearch() {
            this.params.search = this.searchCategories;
            this.params.page = 1; // Reset to first page on new search
            await this.getCategories();
        },

        setStatusFilter(status) {
            this.statusFilter = status;
            this.params.status = status;
            this.getCategories();
        },

        getTabClass(tab) {
            // tab = '', 1, or 0
            if (this.statusFilter === tab) {
                if (tab === 'active') return 'active-tab success';       // Active → green
                if (tab === 'inactive') return 'active-tab danger';        // Inactive → red
                if (tab === '') return 'active-tab secondary';    // All → gray
            } else {
                return 'btn-outline-secondary';                   // unselected
            }
        },

        sortBy(column, order) {
            this.params.sort_by = column;
            this.params.order = order;
            this.getCategories();
        },


        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `package,company`,
                        },
                    })
                    .then((response) => {
                        this.packages = response.data.packages;
                        this.companies = response.data.companies;
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

        formatDate(date) {
            if (!date) return '';
            return format(new Date(date), 'dd-MM-yyyy'); // 30-11-2025 format
        }
    },
    mounted() {
        this.getCategories();

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

.status-tabs .btn {
    border: none;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    transition: all 0.2s;
}

.status-tabs .active-tab.success {
    border-bottom: 3px solid #28a745;
    /* green underline */
    color: #28a745;
    /* green text */
    font-weight: 600;
}

.status-tabs .active-tab.danger {
    border-bottom: 3px solid #dc3545;
    /* red underline */
    color: #dc3545;
    /* red text */
    font-weight: 600;
}

.status-tabs .active-tab.secondary {
    border-bottom: 3px solid #6c757d;
    /* gray underline */
    color: #6c757d;
    /* gray text */
    font-weight: 600;
}
</style>
