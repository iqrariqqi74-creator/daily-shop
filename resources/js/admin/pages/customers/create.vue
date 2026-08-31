<template>
    <section class="content">
        <div class="container-fluid">
            <!-- Loader -->
            <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                <div>
                    <img width="100" :src="loaderImage" />
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
                                        <h6 class="m-0 font-weight-bold text-primary">Create Customer</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-1">
                                    <!-- Form Row 1 -->
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" v-model="customer.name"
                                                    @input="clearErrorMessages('name')" placeholder="Name" :class="{
                                                        'is-invalid': errorMessages.name,
                                                        'is-valid': customer.name && !errorMessages.name
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.name?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" v-model="customer.email"
                                                    @input="clearErrorMessages('email')" placeholder="Email" :class="{
                                                        'is-invalid': errorMessages.email,
                                                        'is-valid': customer.email && !errorMessages.email
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.email?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="customer.status"
                                                    @change="clearErrorMessages('status')" :class="{
                                                        'is-invalid': errorMessages.status,
                                                        'is-valid': customer.status && !errorMessages.status
                                                    }">
                                                    <option value="">Select Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <small class="text-danger">{{ errorMessages.status?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number</label>
                                                <input type="number" class="form-control"
                                                    v-model="customer.phone_number"
                                                    @input="clearErrorMessages('phone_number')"
                                                    placeholder="Phone Number" :class="{
                                                        'is-invalid': errorMessages.phone_number,
                                                        'is-valid': customer.phone_number && !errorMessages.phone_number
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.phone_number?.[0] }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row 2: Address & Image -->
                                    <div class="row">
                                        <!-- Address -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <ckeditor :editor="editor" v-model="customer.address" />
                                                <small class="text-danger">{{ errorMessages.address?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Customer Image</label>
                                                <input type="file" class="form-control" @change="handleImageUpload"
                                                    accept="image/*" :class="{
                                                        'is-invalid': errorMessages.image,
                                                        'is-valid': customer.image && !errorMessages.image
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.image?.[0] }}</small>

                                                <!-- Preview -->
                                                <div v-if="customer.imagePreview" class="mt-2">
                                                    <img :src="customer.imagePreview" alt="Preview" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="createCustomer" :disabled="isLoading">
                                            <span v-if="isLoading">Saving...</span>
                                            <span v-else>Save</span>
                                        </button>
                                        <button class="btn btn-danger ml-3" @click="cancel">Cancel</button>
                                    </div>

                                    <!-- Authentication/Error -->
                                    <div class="row mt-3">
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
import axios from "axios";
import { format } from "date-fns";
import { Ckeditor } from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
    name: 'customerCreate',
    components: {
        Ckeditor,
    },
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: '',
            packages: [],
            customer: {
                name: "",
                email: "",
                address: "",
                status: 1,
                phone_number: "",
                image: null,
                imagePreview: null
            },
            isLoading: false,
            loaderImage: "/images/Spinning arrows.gif",
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = '';
            if (this.authenticateError) this.authenticateError = '';
        },
        handleDateValueChange(date, field) {
            if (date) {
                this.customer[field] = format(new Date(date), "yyyy-MM-dd HH:mm:ss");
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.customer.image = file;
                this.customer.imagePreview = URL.createObjectURL(file);
            }
        },
        async createCustomer() {
            this.isLoading = true;
            try {
                const formData = new FormData();
                for (const key in this.customer) {
                    if (this.customer[key] !== null) {
                        formData.append(key, this.customer[key]);
                    }
                }

                const response = await axios.post("customers", formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data.status === 'success') {
                    this.$toast.success(response.data.message, {
                        position: 'top-right',
                        autoClose: 5000
                    });
                    this.$router.push('/admin/customers');
                }
            } catch (error) {
                this.isLoading = false;
                if (error.response.status === 403) {
                    this.authenticateError = error.response.data.message;
                    this.errorMessages = {};
                    this.$toast.warning(this.authenticateError, { position: 'top-right', autoClose: 5000 });
                } else {
                    this.authenticateError = '';
                    this.errorMessages = error.response.data.errors || {};
                    this.$toast.error(error.response.data.message || "Something went wrong", {
                        position: 'bottom-right',
                        autoClose: 5000
                    });
                }
            }
        },
        cancel() {
            this.$router.push('/admin/customers');
        },
        async getOptions() {
            try {
                const response = await axios.get("common_options", {
                    params: { options: 'package' },
                });
                this.packages = response.data.packages || [];
            } catch (error) {
                this.$swal({
                    title: "Error!",
                    html: error.response?.data?.message || "Something went wrong",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        }
    },
    mounted() {
    }
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
