<template>
    <section class="content">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                <div>
                    <img width="100" :src="imageLoader" />
                    <p class="text-center mt-0">Please wait...</p>
                </div>
            </div>
            <div v-else class="row">
                <div class="col-md-12" v-if="!invalidObject">
                    <div class="card shadow mx-5 my-5">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="m-0 font-weight-bold text-primary">Update Role</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="mb-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" v-model="role.name"
                                                placeholder="Enter role name">
                                            <span>
                                                <small class="text-danger"> {{ errorMessages.name &&
                                                    errorMessages.name[0]
                                                    }}</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" v-model="role.status" :class="{
                                                'is-invalid':
                                                    errorMessages.status && !role.status,
                                                'is-valid': role.status,
                                            }" @input="clearErrorMessages('status')">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <span>
                                            <small class="text-danger">
                                                {{ errorMessages.status && errorMessages.status[0] }}
                                            </small>
                                        </span>
                                    </div>
                                </div>

                                <div cols="12">
                                    <button class="btn btn-success btn-sm " @click="updateRole">Save</button>
                                    <button class="btn btn-danger btn-sm ml-3" @click="cancel">Cancel</button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span>
                                            <small class="text-danger">
                                                {{ authenticateError }}
                                            </small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" v-else-if="invalidObject">
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



export default {
    name: 'BrandsCreate',
    components: {
    },
    data() {
        return {
            isLoading: false,
            errorMessages: '',
            authenticateError: '',
            isLoading: true,
            role: {},
            invalidObject: false,
            imageLoader: "/images/Spinning arrows.gif",


        }
    },
    methods: {
        async updateRole() {
            try {
                this.isLoading = true;
                const response = await axios.put(`roles/${this.role.id}`, this.role);
                if (response.data.status == 'success') {
                    this.$router.push('/admin/roles');
                    this.isLoading = false;
                }
            }
            catch (error) {
                if (error.response.status == 403) {
                    this.errorMessages = '',
                        this.authenticateError = error.response.data.message;
                    this.$toast.warning(
                        error.response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                } else {
                    this.authenticateError = '';
                    this.errorMessages = error.response.data.errors;
                    this.$toast.error(
                        error.response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.$swal({
                        title: "Warning",
                        html: error.response.data.message,
                        icon: "warning",
                        button: "OK",
                    });
                }
            }
        },
        cancel() {
            this.$router.push('/admin/roles');
        },

        async getRole(role_id) {
            try {
                this.isLoading = true;
                await axios.get(`roles/${role_id}`).then(response => {
                    this.role = response.data.role;
                    this.isLoading = false;
                }).catch(error => {
                    this.isLoading = false;
                    this.invalidObject = true;
                    this.$swal({
                        title: "Warning",
                        html: error.response.data.message,
                        icon: "warning",
                        button: "OK",
                    })
                })
            }
            catch (error) {
                this.isLoading = false;
                this.$swal({
                    title: "Warning",
                    html: "Something went wrong",
                    icon: "warning",
                    button: "OK",
                });
            }
        },

        clearErrorMessages(key) {
            this.errorMessages[key] = null;
        }
    },
    mounted() {
        this.getRole(this.$route.params.id);
    }
};
</script>

<style type="text/css">
.fas,
.text-info {
    cursor: pointer;
}
</style>
