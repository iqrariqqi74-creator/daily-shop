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
                                            Create vendor
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
                                                    v-model="vendor.name"
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
                                                            vendor.name &&
                                                            !errorMessages.name,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.name?.[0]
                                                }}</small>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    v-model="vendor.email"
                                                    @input="
                                                        clearErrorMessages(
                                                            'email',
                                                        )
                                                    "
                                                    placeholder="Email"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.email,
                                                        'is-valid':
                                                            vendor.email &&
                                                            !errorMessages.email,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.email?.[0]
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
                                                    v-model="vendor.status"
                                                    @change="
                                                        clearErrorMessages(
                                                            'status',
                                                        )
                                                    "
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.status,
                                                        'is-valid':
                                                            vendor.status &&
                                                            !errorMessages.status,
                                                    }"
                                                >
                                                    <option value="">
                                                        Select Status
                                                    </option>
                                                    <option value="1">
                                                        Active
                                                    </option>
                                                    <option value="0">
                                                        Inactive
                                                    </option>
                                                </select>
                                                <small class="text-danger">{{
                                                    errorMessages.status?.[0]
                                                }}</small>
                                            </div>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number"
                                                    >Phone Number</label
                                                >
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    v-model="
                                                        vendor.phone_number
                                                    "
                                                    @input="
                                                        clearErrorMessages(
                                                            'phone_number',
                                                        )
                                                    "
                                                    placeholder="Phone Number"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.phone_number,
                                                        'is-valid':
                                                            vendor.phone_number &&
                                                            !errorMessages.phone_number,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages
                                                        .phone_number?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Row 2: location & Image -->
                                    <div class="row">
                                        <!-- location -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="location"
                                                    >location</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="vendor.location"
                                                    @input="
                                                        clearErrorMessages(
                                                            'location',
                                                        )
                                                    "
                                                    placeholder="location"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.location,
                                                        'is-valid':
                                                            vendor.location &&
                                                            !errorMessages.location,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.location?.[0]
                                                }}</small>
                                            </div>
                                        </div>

                                        <!-- Image Upload -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="image"
                                                    >vendor Image</label
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
                                                            vendor.image &&
                                                            !errorMessages.image,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.image?.[0]
                                                }}</small>

                                                <!-- Preview -->
                                                <div
                                                    v-if="vendor.imagePreview"
                                                    class="mt-2"
                                                >
                                                    <img
                                                        :src="
                                                            vendor.imagePreview
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
                                            @click="createVendor"
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
import { VueDatePicker } from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { format } from "date-fns";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
    name: "vendorCreate",
    components: {
        VueDatePicker,
    },
    data() {
        return {
            editor: ClassicEditor,
            errorMessages: {},
            authenticateError: "",
            packages: [],
            vendor: {
                name: "",
                email: "",
                location: "",
                status: 1,
                phone_number: "",
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
                this.vendor[field] = format(
                    new Date(date),
                    "yyyy-MM-dd HH:mm:ss",
                );
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.vendor.image = file;
                this.vendor.imagePreview = URL.createObjectURL(file);
            }
        },
        async createVendor() {
            this.isLoading = true;
            try {
                const formData = new FormData();
                for (const key in this.vendor) {
                    if (this.vendor[key] !== null) {
                        formData.append(key, this.vendor[key]);
                    }
                }

                const response = await axios.post("vendors", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                if (response.data.status === "success") {
                    this.$toast.success(response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.$router.push("/admin/vendors");
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
            this.$router.push("/admin/vendors");
        },
        async getOptions() {
            try {
                const response = await axios.get("common_options", {
                    params: { options: "package" },
                });
                this.packages = response.data.packages || [];
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
        // any additional setup if needed
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
