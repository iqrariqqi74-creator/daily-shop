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
                                            Edit Tax
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
                                                    v-model="tax.name"
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
                                                            tax.name &&
                                                            !errorMessages.name,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.name?.[0]
                                                }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Value</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="tax.value"
                                                    @input="
                                                        clearErrorMessages(
                                                            'value',
                                                        )
                                                    "
                                                    placeholder="value"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.value,
                                                        'is-valid':
                                                            tax.value &&
                                                            !errorMessages.value,
                                                    }"
                                                />
                                                <small class="text-danger">{{
                                                    errorMessages.value?.[0]
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
                                                    v-model="tax.status"
                                                    @change="
                                                        clearErrorMessages(
                                                            'status',
                                                        )
                                                    "
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.status,
                                                        'is-valid':
                                                            tax.status &&
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
                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button
                                            class="btn btn-success"
                                            @click="updateTax"
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
    name: "taxEdit",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            tax: {
                name: "",
                value: "",
                status: "",
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

        async fetchTax() {
            this.isLoading = true;
            // alert('fetchTax');
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`taxes/${id}`);
                this.tax = res.data.tax;
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(
                    error.response?.data?.message ||
                        "Failed to fetchTax tax data",
                );
            }
        },
        async updateTax() {
            this.isLoading = true;

            try {
                const id = this.$route.params.id;

                const formData = new FormData();

                formData.append("name", this.tax.name);
                formData.append("status", this.tax.status);
                formData.append("value", this.tax.value);

                formData.append("_method", "PUT");

                const res = await axios.post(`/taxes/${id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.data.status === "success") {
                    this.$toast.success(res.data.message);

                    setTimeout(() => {
                        this.$router.push("/admin/taxes");
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
            this.$router.push("/admin/taxes");
        },
        async updateStatus(tax, status) {
            try {
                await axios.post(`taxes/${tax.id}/status`, {
                    status: status,
                });

                tax.status = status; // UI update

                this.$toast.success("Status updated successfully");
            } catch (error) {
                this.$toast.error("Something went wrong");
            }
        },
    },
    mounted() {
        this.fetchTax();
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
