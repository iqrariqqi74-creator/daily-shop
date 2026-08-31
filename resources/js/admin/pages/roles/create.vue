<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow my-5 mx-5 ">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="m-0 font-weight-bold text-primary">Create Role</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" v-model="role.name" :class="{
                                                'is-invalid':
                                                    errorMessages.name && !role.name,
                                                'is-valid': role.name,
                                            }" placeholder="Enter role name" @input="clearErrorMessages('name')">
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
                                            }" @input="clearErrorMessages(role.status)">
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div cols="12">
                                    <button class="btn btn-success" @click="createRoles">Save</button>
                                    <button class="btn btn-danger ml-3" @click="cancel">Cancel</button>
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
            </div>
        </div>
    </section>

</template>

<script>

import * as notify from "../../../utils/notify.js";
import axios from "axios";



export default {
    name: 'RolesCreate',
    components: {
    },
    data() {
        return {
            errorMessages: '',
            authenticateError: '',
            debug: '',
            role: [],
            isLoading: true,
            role: {
                name: '',
                status: 1,
            },
            loaderImage: "/images/Spinning arrows.gif",

        }
    },
    methods: {
        clearErrorMessages(key) {
            this.errorMessages[key] = '';
        },

        async createRoles() {
            try {
                const response = await axios.post("roles", this.role);
                if (response.data.status == 'success') {
                    this.$toast.success(
                        response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.role = {
                        name: '',
                        status: 1,
                    };
                    this.$router.push('/admin/roles');
                }
            }
            catch (error) {
                this.errorMessages = error.response.data.errors;
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
                    this.$toast.error(
                        error.response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.errorMessages = error.response.data.errors;
                    this.$toast.error(
                        this.errorMessages[0], {
                        position: "top-right",
                        autoClose: 5000,
                    });
                }
            }
        },
        cancel() {
            this.$router.push('/admin/roles');
        },

    },
};
</script>

<style type="text/css">
.fas,
.text-info {
    cursor: pointer;
}
</style>
