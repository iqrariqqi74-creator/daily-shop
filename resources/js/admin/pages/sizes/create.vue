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
                                        <h6 class="m-0 font-weight-bold text-primary">Create Size</h6>
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
                                                <input type="text" class="form-control" v-model="size.name"
                                                    @input="clearErrorMessages('name')" placeholder="Name" :class="{
                                                        'is-invalid': errorMessages.name,
                                                        'is-valid': size.name && !errorMessages.name
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.name?.[0] }}</small>
                                            </div>
                                        </div>
                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="size.status"
                                                    @change="clearErrorMessages('status')" :class="{
                                                        'is-invalid': errorMessages.status,
                                                        'is-valid': size.status && !errorMessages.status
                                                    }">
                                                    <option value="">Select Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                                <small class="text-danger">{{ errorMessages.status?.[0] }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row 2: description & Image -->
                                    <div class="row">
                                        <!-- Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Brand Image</label>
                                                <input type="file" class="form-control" @change="handleImageUpload"
                                                    accept="image/*" :class="{
                                                        'is-invalid': errorMessages.image,
                                                        'is-valid': size.image && !errorMessages.image
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.image?.[0] }}</small>

                                                <!-- Preview -->
                                                <div v-if="size.imagePreview" class="mt-2">
                                                    <img :src="size.imagePreview" alt="Preview" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- description -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <ckeditor :editor="editor" v-model="size.description">
                                                </ckeditor>
                                                <small class="text-danger">{{ errorMessages.description?.[0]
                                                    }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="createSize" :disabled="isLoading">
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
    name: 'sizeCreate',
    components: {
        Ckeditor,
    },
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: '',
            categories: [],
            size: {
                name: "",
                description: "",
                status: 'active',
                image: null,
                imagePreview: null,
                editor: ClassicEditor,
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
                this.size[field] = format(new Date(date), "yyyy-MM-dd HH:mm:ss");
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.size.image = file;
                this.size.imagePreview = URL.createObjectURL(file);
            }
        },
        async createSize() {
            this.isLoading = true;
            try {
                const formData = new FormData();
                for (const key in this.size) {
                    if (this.size[key] !== null) {
                        formData.append(key, this.size[key]);
                    }
                }

                const response = await axios.post("sizes", formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data.status === 'success') {
                    this.$toast.success(response.data.message, {
                        position: 'top-right',
                        autoClose: 5000
                    });
                    this.$router.push('/admin/sizes');
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
            this.$router.push('/admin/sizes');
        },


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
