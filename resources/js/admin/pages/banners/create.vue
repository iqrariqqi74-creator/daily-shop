<template>
    <section class="content">
        <div class="container-fluid">
            <!-- Loader -->
            <div
                class="d-flex align-items-center justify-content-center"
                v-if="isLoading"
            >
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
                                        <h6
                                            class="m-0 font-weight-bold text-primary"
                                        >
                                            Create Banner
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-1">
                                    <!-- Form Row 1 -->
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="banner.name"
                                                    @input="
                                                        clearErrorMessages(
                                                            'name',
                                                        )
                                                    "
                                                    placeholder="Name"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.name,
                                                        'is-valid':
                                                            banner.name &&
                                                            !errorMessages.name,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.name?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                        <!-- Name -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="text">Text</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="banner.text"
                                                    @input="
                                                        clearErrorMessages(
                                                            'text',
                                                        )
                                                    "
                                                    placeholder="Text"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.text,
                                                        'is-valid':
                                                            banner.text &&
                                                            !errorMessages.text,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.text?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                        <!-- Status -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status"
                                                    >Status</label
                                                >
                                                <select
                                                    class="form-control"
                                                    v-model="banner.status"
                                                    @change="
                                                        clearErrorMessages(
                                                            'status',
                                                        )
                                                    "
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.status,
                                                        'is-valid':
                                                            banner.status &&
                                                            !errorMessages.status,
                                                    }"
                                                >
                                                    <option value="">
                                                        Select Status
                                                    </option>
                                                    <option value="active">
                                                        Active
                                                    </option>
                                                    <option value="inactive">
                                                        Inactive
                                                    </option>
                                                </select>
                                                <small class="text-danger">{{
                                                    errorMessages.status?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row 2: description & Image -->
                                    <div class="row">
                                        <!-- Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image"
                                                    >banner Image</label
                                                >
                                                <input
                                                    type="file"
                                                    class="form-control"
                                                    @change="handleImageUpload"
                                                    accept="image/*"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.image,
                                                        'is-valid':
                                                            banner.image &&
                                                            !errorMessages.image,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.image?.[0]
                                                }}</small>

                                                <!-- Preview -->
                                                <div
                                                    v-if="banner.imagePreview"
                                                    class="mt-2"
                                                >
                                                    <img
                                                        :src="
                                                            banner.imagePreview
                                                        "
                                                        alt="Preview"
                                                        width="120"
                                                        class="rounded shadow-sm"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button
                                            class="btn btn-success"
                                            @click="createBanner"
                                            :disabled="isLoading"
                                        >
                                            <span v-if="isLoading"
                                                >Saving...</span
                                            >
                                            <span v-else>Save</span>
                                        </button>
                                        <button
                                            class="btn btn-danger ml-3"
                                            @click="cancel"
                                        >
                                            Cancel
                                        </button>
                                    </div>

                                    <!-- Authentication/Error -->
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <small class="text-danger">{{
                                                authenticateError
                                            }}</small>
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

export default {
    name: "bannerCreate",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            categories: [],
            banner: {
                name: "",
                status: "active",
                image: null,
                imagePreview: null,
            },
            isLoading: false,
            loaderImage: "/images/Spinning arrows.gif",
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = "";
            if (this.authenticateError) this.authenticateError = "";
        },
        handleDateValueChange(date, field) {
            if (date) {
                this.banner[field] = format(
                    new Date(date),
                    "yyyy-MM-dd HH:mm:ss",
                );
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.banner.image = file;
                this.banner.imagePreview = URL.createObjectURL(file);
            }
        },
        async createBanner() {
            this.isLoading = true;
            try {
                const formData = new FormData();
                for (const key in this.banner) {
                    if (this.banner[key] !== null) {
                        formData.append(key, this.banner[key]);
                    }
                }

                const response = await axios.post("banners", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                if (response.data.status === "success") {
                    this.$toast.success(response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.$router.push("/admin/banners");
                }
            } catch (error) {
                this.isLoading = false;
                if (error.response.status === 403) {
                    this.authenticateError = error.response.data.message;
                    this.errorMessages = {};
                    this.$toast.warning(this.authenticateError, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                } else {
                    this.authenticateError = "";
                    this.errorMessages = error.response.data.errors || {};
                    this.$toast.error(
                        error.response.data.message || "Something went wrong",
                        {
                            position: "bottom-right",
                            autoClose: 5000,
                        },
                    );
                }
            }
        },
        cancel() {
            this.$router.push("/admin/banners");
        },

        async getOptions() {
            try {
                const response = await axios.get("common_options", {
                    params: { options: "category" },
                });
                this.banners = response.data.banners || [];
            } catch (error) {
                this.$swal({
                    title: "Error!",
                    html:
                        error.response?.data?.message || "Something went wrong",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
    },
    mounted() {
        this.getOptions();
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
