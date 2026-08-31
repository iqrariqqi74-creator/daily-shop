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
                                            Edit Banner
                                        </h6>
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
                                        <!-- text -->
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
                                        <div class="col-md-3">
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
                                    <!-- Form Row 2: Address & Image -->
                                    <div class="row">
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
                                                <div
                                                    v-else-if="
                                                        banner.image &&
                                                        !banner.imagePreview
                                                    "
                                                    class="mt-2"
                                                >
                                                    <img
                                                        :src="
                                                            '/' + banner.image
                                                        "
                                                        alt="Current"
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
                                            @click="updateBanner"
                                            :disabled="isLoading"
                                        >
                                            <span v-if="isLoading"
                                                >Updating...</span
                                            >
                                            <span v-else>Update</span>
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

export default {
    name: "bannerEdit",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            banner: {
                name: "",
                status: "",
                image: null,
                imagePreview: null,
            },
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = "";
            if (this.authenticateError) this.authenticateError = "";
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.banner.image = file;
                this.banner.imagePreview = URL.createObjectURL(file);
            }
        },
        async fetchBanner() {
            this.isLoading = true;
            // alert('fetchBanner');
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`banners/${id}`);
                this.banner = res.data.banner;
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(
                    error.response?.data?.message ||
                        "Failed to fetch banner data",
                );
            }
        },
        async updateBanner() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;

                console.log("button");
                const formData = new FormData();

                formData.append("name", this.banner.name);
                formData.append("status", this.banner.status);
                formData.append("text", this.banner.text);

                if (this.banner.image instanceof File) {
                    formData.append("image", this.banner.image);
                }

                formData.append("_method", "PUT");

                const res = await axios.post(`/banners/${id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.data.status === "success") {
                    this.$toast.success(res.data.message);

                    setTimeout(() => {
                        this.$router.push("/admin/banners");
                    }, 1000);
                }
            } catch (error) {
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || "";
            } finally {
                this.isLoading = false;
            }
        },
        cancel() {
            this.$router.push("/admin/banners");
        },
        async updateStatus(banner, status) {
            try {
                await axios.post(`banners/${banner.id}/status`, {
                    status: status,
                });

                banner.status = status; // UI update

                this.$toast.success("Status updated successfully");
            } catch (error) {
                this.$toast.error("Something went wrong");
            }
        },
    },
    mounted() {
        this.fetchBanner();
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
