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
                                        <h6 class="m-0 font-weight-bold text-primary">Create Category</h6>
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
                                                <input type="text" class="form-control" v-model="category.name"
                                                    @input="clearErrorMessages('name')" placeholder="Name" :class="{
                                                        'is-invalid': errorMessages.name,
                                                        'is-valid': category.name && !errorMessages.name
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.name?.[0] }}</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="parent_id">Category Name</label>

                                                <v-select
                                                    v-model="category.parent_id"
                                                    :options="categories"
                                                    label="name"
                                                    :reduce="item => item.id"
                                                    placeholder="Main Category"
                                                    :clearable="true"
                                                    :searchable="true"
                                                >
                                                    <!-- Dropdown List -->
                                                    <template #option="{ name, image }">
                                                        <div class="d-flex align-items-center py-2">
                                                            <img
                                                                :src="IMAGE_URL + image"
                                                                width="35"
                                                                height="35"
                                                                class="rounded-circle me-3"
                                                                style="object-fit: cover;"
                                                            />
                                                            <span>{{ name }}</span>
                                                        </div>
                                                    </template>

                                                    <!-- Selected Item -->
                                                    <template #selected-option="{ name, image }">
                                                        <div class="d-flex align-items-center">
                                                            <img
                                                                :src="IMAGE_URL + image"
                                                                width="30"
                                                                height="30"
                                                                class="rounded-circle me-3"
                                                                style="object-fit: cover;"
                                                            />
                                                            <span>{{ name }}</span>
                                                        </div>
                                                    </template>

                                                    <!-- Main Category Option -->
                                                    <template #no-options>
                                                        No category found
                                                    </template>
                                                </v-select>

                                                <small class="text-danger">
                                                    {{ errorMessages.parent_id?.[0] }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="category.status"
                                                    @change="clearErrorMessages('status')" :class="{
                                                        'is-invalid': errorMessages.status,
                                                        'is-valid': category.status && !errorMessages.status
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
                                                <label for="image">Category Image</label>
                                                <input type="file" class="form-control" @change="handleImageUpload"
                                                    accept="image/*" :class="{
                                                        'is-invalid': errorMessages.image,
                                                        'is-valid': category.image && !errorMessages.image
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.image?.[0] }}</small>

                                                <!-- Preview -->
                                                <div v-if="category.imagePreview" class="mt-2">
                                                    <img :src="category.imagePreview" alt="Preview" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                        <!-- description -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <ckeditor :editor="editor" v-model="category.description">
                                                </ckeditor>
                                                <small class="text-danger">{{ errorMessages.description?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="createCategory" :disabled="isLoading">
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    name: 'categoryCreate',
    components: {
        Ckeditor,
      vSelect
    },
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: '',
            categories: [],
            category: {
                name: "",
                email: "",
                description: "",
                status: 'active',
                phone_number: "",
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
                this.category[field] = format(new Date(date), "yyyy-MM-dd HH:mm:ss");
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.category.image = file;
                this.category.imagePreview = URL.createObjectURL(file);
            }
        },
        async createCategory() {
            this.isLoading = true;
            try {
                const formData = new FormData();
                for (const key in this.category) {
                    if (this.category[key] !== null) {
                        formData.append(key, this.category[key]);
                    }
                }

                const response = await axios.post("categories", formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data.status === 'success') {
                    this.$toast.success(response.data.message, {
                        position: 'top-right',
                        autoClose: 5000
                    });
                    this.$router.push('/admin/categories');
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
            this.$router.push('/admin/categories');
        },

        async getOptions() {
            try {
                const response = await axios.get("common_options", {
                    params: { options: 'category' },
                });
                this.categories = response.data.categories || [];
            } catch (error) {
                this.$swal({
                    title: "Error!",
                    html: error.response?.data?.message || "Something went wrong",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
    },
    mounted() {
        this.getOptions();
    }
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
