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
                                        <h6 class="m-0 font-weight-bold text-primary">Edit Customer</h6>
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
                                                <div v-else-if="customer.image && !customer.imagePreview" class="mt-2">
                                                    <img :src="'/' + customer.image" alt="Current" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="updateCustomer" :disabled="isLoading">
                                            <span v-if="isLoading">Updating...</span>
                                            <span v-else>Update</span>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'CustomerEdit',
    components: {},
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: '',
            customer: {
                name: "",
                email: "",
                address: "",
                status: 1,
                phone_number: "",
                image: null,
                imagePreview: null
            },
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = '';
            if (this.authenticateError) this.authenticateError = '';
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.customer.image = file;
                this.customer.imagePreview = URL.createObjectURL(file);
            }
        },
        async fetchCustomer() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`customers/${id}`);
                this.customer = res.data.customer;
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(error.response?.data?.message || "Failed to fetch customer data");
            }
        },
        async updateCustomer() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const formData = new FormData();
                for (const key in this.customer) {
                    if (this.customer[key] !== null) {
                        formData.append(key, this.customer[key]);
                    }
                }

                const res = await axios.post(`customers/${id}?_method=PUT`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (res.data.status === 'success') {
                    this.$toast.success(res.data.message);
                    this.$router.push('/admin/customers');
                }
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || '';
            }
        },
        cancel() {
            this.$router.push('/admin/customers');
        },
        async updateStatus(customer, status) {
            try {
                await axios.post(`customers/${customer.id}/status`, {
                    status: status
                });

                customer.status = status; // UI update

                this.$toast.success("Status updated successfully");
            } catch (error) {
                this.$toast.error("Something went wrong");
            }
        }
    },
    mounted() {
        this.fetchCustomer();
    }
}
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
