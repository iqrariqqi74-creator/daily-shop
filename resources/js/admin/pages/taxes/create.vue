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
                                            Create Tax
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-1">
                                    <!-- Form Row 1 -->
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="value">Value</label>
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
                                        <div class="col-md-6">
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
                                            @click="createTax"
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
    name: "TaxCreate",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            categories: [],
            tax: {
                name: "",
                value: "",
                status: "active",
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

        async createTax() {
            this.isLoading = true;
            try {
                const formData = new FormData();

                formData.append("name", this.tax.name);
                formData.append("value", this.tax.value);
                formData.append("status", this.tax.status);

                const response = await axios.post("taxes", formData);

                if (response.data.status === "success") {
                    this.$toast.success(response.data.message, {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    this.$router.push("/admin/taxes");
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
            this.$router.push("/admin/taxes");
        },
    },
    mounted() {},
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
