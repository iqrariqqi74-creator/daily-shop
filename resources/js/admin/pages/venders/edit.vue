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
                                        <h6 class="m-0 font-weight-bold text-primary">Edit Vendor</h6>
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
                                                <input type="text" class="form-control" v-model="vendor.name"
                                                    @input="clearErrorMessages('name')" placeholder="Name" :class="{
                                                        'is-invalid': errorMessages.name,
                                                        'is-valid': vendor.name && !errorMessages.name
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.name?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" v-model="vendor.email"
                                                    @input="clearErrorMessages('email')" placeholder="Email" :class="{
                                                        'is-invalid': errorMessages.email,
                                                        'is-valid': vendor.email && !errorMessages.email
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.email?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control" v-model="vendor.status"
                                                    @change="clearErrorMessages('status')" :class="{
                                                        'is-invalid': errorMessages.status,
                                                        'is-valid': vendor.status && !errorMessages.status
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
                                                <input type="number" class="form-control" v-model="vendor.phone_number"
                                                    @input="clearErrorMessages('phone_number')"
                                                    placeholder="Phone Number" :class="{
                                                        'is-invalid': errorMessages.phone_number,
                                                        'is-valid': vendor.phone_number && !errorMessages.phone_number
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.phone_number?.[0] }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row 2: location & Image -->
                                    <div class="row">
                                        <!-- location -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="location">Location</label>
                                                <input type="text" class="form-control" v-model="vendor.location"
                                                    @input="clearErrorMessages('location')" placeholder="Location"
                                                    :class="{
                                                        'is-invalid': errorMessages.location,
                                                        'is-valid': vendor.location && !errorMessages.location
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.location?.[0] }}</small>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image">Vendor Image</label>
                                                <input type="file" class="form-control" @change="handleImageUpload"
                                                    accept="image/*" :class="{
                                                        'is-invalid': errorMessages.image,
                                                        'is-valid': vendor.image && !errorMessages.image
                                                    }" />
                                                <small class="text-danger">{{ errorMessages.image?.[0] }}</small>

                                                <!-- Preview -->
                                                <div v-if="vendor.imagePreview" class="mt-2">
                                                    <img :src="vendor.imagePreview" alt="Preview" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                                <div v-else-if="vendor.image && !vendor.imagePreview" class="mt-2">
                                                    <img :src="'/' + vendor.image" alt="Current" width="120"
                                                        class="rounded shadow-sm" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button class="btn btn-success" @click="updateVendor" :disabled="isLoading">
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

export default {
    name: "vendorEdit",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            vendor: {
                name: "",
                email: "",
                location: "",
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
            if (this.errorMessages[field]) this.errorMessages[field] = "";
            if (this.authenticateError) this.authenticateError = "";
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.vendor.image = file;
                this.vendor.imagePreview = URL.createObjectURL(file);
            }
        },
        async getVendor(user_id) {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`vendors/${id}`);
                const data = res.data.vendor; // <-- vendor key

                // Merge data into reactive vendor object
                this.vendor.name = data.name || "";
                this.vendor.email = data.email || "";
                this.vendor.location = data.location || ""; // map location -> location
                this.vendor.status = data.status !== undefined ? data.status : 1;
                this.vendor.phone_number = data.phone_number || "";
                this.vendor.image = data.image || null;
                this.vendor.imagePreview = data.image ? "/" + data.image : null;

                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(error.response?.data?.message || "Failed to fetch vendor data");
            }
        },
        async updateVendor() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const formData = new FormData();

                // Map location -> location for API
                formData.append("name", this.vendor.name);
                formData.append("email", this.vendor.email);
                formData.append("location", this.vendor.location);
                formData.append("status", this.vendor.status);
                formData.append("phone_number", this.vendor.phone_number);

                // Only append image if new file selected
                if (this.vendor.image instanceof File) {
                    formData.append("image", this.vendor.image);
                }

                const res = await axios.post(`vendors/${id}?_method=PUT`, formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                });

                if (res.data.status === "success") {
                    this.$toast.success(res.data.message);
                    this.$router.push("/admin/vendors");
                }
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || "";
            }
        },
        cancel() {
            this.$router.push("/admin/vendors");
        }
    },
    mounted() {
        this.getVendor();
    }
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
