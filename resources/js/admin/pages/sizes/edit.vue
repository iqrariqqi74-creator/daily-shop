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
                                        <h6 class="m-0 font-weight-bold text-primary">Edit Brand</h6>
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
                                                <input type="text" class="form-control" v-model="brand.name"
                                                    @input="clearErrorMessages('name')" placeholder="Name" :class="{
                                                        'is-invalid': errorMessages.name,
                                                        'is-valid': brand.name && !errorMessages.name
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.name?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="brand.status"
                                                    @change="clearErrorMessages('status')" :class="{
                                                        'is-invalid': errorMessages.status,
                                                        'is-valid': brand.status && !errorMessages.status
                                                    }">
                                                    <option value="">Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                                <small class="text-danger">{{ errorMessages.status?.[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Row 2: Address & Image -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Brand Image</label>
                                                <input type="file" class="form-control" @change="handleImageUpload"
                                                    accept="image/*" :class="{
                                                        'is-invalid': errorMessages.image,
                                                        'is-valid': brand.image && !errorMessages.image
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.image?.[0] }}</small>

                                                <!-- Preview -->
                                                <div v-if="brand.imagePreview" class="mt-2">
                                                    <img :src="brand.imagePreview" alt="Preview" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                                <div v-else-if="brand.image && !brand.imagePreview" class="mt-2">
                                                    <img :src="'/' + brand.image" alt="Current" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Address -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <ckeditor :editor="editor" v-model="brand.address" />
                                                <small class="text-danger">{{ errorMessages.address?.[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="updateBrand" :disabled="isLoading">
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
    name: 'brandEdit',
    components: {},
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: '',
            brand: {
                name: "",
                description: "",
                status: "",
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
                this.brand.image = file;
                this.brand.imagePreview = URL.createObjectURL(file);
            }
        },
        async fetchBrand() {
            this.isLoading = true;
            // alert('fetchBrand');
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`brands/${id}`);
                this.brand = res.data.brand;
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(error.response?.data?.message || "Failed to fetch brand data");
            }
        },
        async updateBrand() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const formData = new FormData();
                for (const key in this.brand) {
                    if (this.brand[key] !== null) {
                        formData.append(key, this.brand[key]);
                    }
                }

                const res = await axios.post(`brands/${id}?_method=PUT`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (res.data.status === 'success') {
                    this.$toast.success(res.data.message);
                    this.$router.push('/admin/brands');
                }
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || '';
            }
        },
        cancel() {
            this.$router.push('/admin/brands');
        },
        async updateStatus(brand, status) {
            try {
                await axios.post(`brands/${brand.id}/status`, {
                    status: status
                });

                brand.status = status; // UI update

                this.$toast.success("Status updated successfully");
            } catch (error) {
                this.$toast.error("Something went wrong");
            }
        },

    },
    mounted() {
        this.fetchBrand();
    }
}
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
