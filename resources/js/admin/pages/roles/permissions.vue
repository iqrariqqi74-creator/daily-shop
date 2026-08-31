<template>
    <section class="content">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                <div>
                    <img width="100" :src="loaderImage" />
                    <p class="text-center mt-0">Please wait...</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" v-if="!invalidObject">
                    <div class="card shadow mb-4 mx-5 my-5">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Assign Permissions to "{{ role.name }}"
                                    </h6>
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

                                <table v-show="!isLoading" class="table table-bordered" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Assign Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <ul class="p-0">
                                                    <li v-for="(module, index) in modules" :key="index"
                                                        class="font-bold list-unstyled">
                                                        <SingleCheckbox :module="module"
                                                            :selected_permission="selected_permission" />
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div cols="12" class="mt-3">
                                    <button class="btn btn-success" @click="assignPermissions">Save</button>
                                    <button class="btn btn-danger ml-3" @click="cancel">Cancel</button>
                                </div>
                            </div>

                            <div v-show="!isLoading">
                                <pagination :totalPages="totalPages" :perPage="50" :currentPage="currentPage"
                                    @pageChanged="onPageChange" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-10 mx-5 my-5" v-else-if="invalidObject">
                    <div class="alert alert-danger" role="alert">
                        You are trying to edit an invalid role. <br />
                    </div>
                    <button class="btn btn-danger" @click="cancel">Go Back</button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import * as notify from "../../../utils/notify.js";
import axios from "axios";

import Pagination from "../Pagination.vue";
import SingleCheckbox from "./SingleCheckbox.vue";

export default {
    name: "GivePermission",
    components: {
        Pagination,
        SingleCheckbox,
    },
    data() {
        return {
            loaderImage: "/images/Spinning arrows.gif",
            errorMessage: "",
            debug: "",
            modules: [],
            permission_name: [],
            selected_permission: {
                role_id: "",
                permission_ids: [],
            },
            role: {},
            totalPages: 0,
            currentPage: 1,
            isLoading: false,
            params: {
                page: 1,
            },
            invalidObject: false,
        };
    },
    methods: {
        async getPermissions() {
            try {
                this.isLoading = true;
                const response = await axios.get("permissions", {
                    params: this.params,
                });
                this.modules = response.data.modules;
                this.totalPages = response.data.total_pages;
            } catch (error) {
                this.modules = [];
            } finally {
                this.isLoading = false;
            }
        },

        async assignPermissions() {
            this.isLoading = true;
            this.errorMessage = "";
            this.selected_permission.role_id = this.role.role_id;

            try {
                const response = await axios.post("roles_permissions", this.selected_permission);


                // Safe check
                if (response && response.data && response.data.status === "success") {
                    this.$toast.success(response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.$router.push("/admin/roles");
                } else {
                    throw new Error(response?.data?.message || "Unexpected response structure.");
                }
            } catch (error) {
                console.error("assignPermissions error:", error);
                this.$swal({
                    title: "Error",
                    text: error?.response?.data?.message || error.message || "An error occurred while assigning permissions.",
                    icon: "error",
                });
            } finally {
                this.isLoading = false;
            }
        },

        cancel() {
            this.$router.push("/admin/roles");
        },

        async getRole(role_id) {
            try {
                const response = await axios.get(`roles/${role_id}`);
                this.role.name = response.data.role.name;
                this.role.role_id = role_id;
                this.selected_permission.permission_ids = response.data.role.permissions.map(
                    (p) => p.permission_id
                );
            } catch (error) {
                this.invalidObject = true;
            }
        },

        onPageChange(page) {
            this.params.page = page;
            this.currentPage = page;
            this.getPermissions();
        },
    },
    mounted() {
        this.getPermissions();
        this.getRole(this.$route.params.id);
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
