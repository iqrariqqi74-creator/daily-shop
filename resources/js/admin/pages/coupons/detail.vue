<template>
    <section class="content">
        <div class="container-fluid">
            <!-- Loading -->
            <div
                v-if="isLoading"
                class="d-flex justify-content-center align-items-center"
                style="min-height: 300px"
            >
                <div class="text-center">
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>
                    <p class="mt-2 mb-0">Please wait...</p>
                </div>
            </div>

            <!-- Error -->
            <div v-else-if="errorMessage" class="m-4">
                <div class="alert alert-danger">
                    {{ errorMessage }}
                </div>

                <button class="btn btn-secondary" @click="$router.back()">
                    Back
                </button>
            </div>

            <!-- Coupon Details -->
            <div v-else-if="coupon" class="card shadow m-4">
                <!-- Header -->
                <div
                    class="card-header d-flex justify-content-between align-items-center"
                >
                    <h4 class="mb-0">Coupon Details</h4>

                    <button
                        class="btn btn-secondary btn-sm"
                        @click="$router.back()"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back
                    </button>
                </div>

                <!-- Body -->
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <!-- ID -->
                                    <tr>
                                        <th width="220">ID</th>
                                        <td>
                                            {{ coupon.id }}
                                        </td>
                                    </tr>

                                    <!-- Title -->
                                    <tr>
                                        <th>Title</th>
                                        <td>
                                            {{ coupon.title || "N/A" }}
                                        </td>
                                    </tr>

                                    <!-- Code -->
                                    <tr>
                                        <th>Coupon Code</th>
                                        <td>
                                            <span
                                                v-if="coupon.code"
                                                class="badge badge-info"
                                            >
                                                {{ coupon.code }}
                                            </span>

                                            <span v-else> N/A </span>
                                        </td>
                                    </tr>

                                    <!-- Value -->
                                    <tr>
                                        <th>Value</th>
                                        <td>
                                            {{ coupon.value ?? "N/A" }}
                                        </td>
                                    </tr>

                                    <!-- Type -->
                                    <tr>
                                        <th>Type</th>
                                        <td>
                                            <span
                                                v-if="
                                                    coupon.type === 'percentage'
                                                "
                                                class="badge badge-primary"
                                            >
                                                Percentage
                                            </span>

                                            <span
                                                v-else-if="
                                                    coupon.type === 'value'
                                                "
                                                class="badge badge-info"
                                            >
                                                Value
                                            </span>

                                            <span v-else>
                                                {{ coupon.type || "N/A" }}
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Minimum Order -->
                                    <tr>
                                        <th>Minimum Order Amount</th>
                                        <td>
                                            {{
                                                coupon.minimum_order_amount ??
                                                "N/A"
                                            }}
                                        </td>
                                    </tr>

                                    <!-- One Time -->
                                    <tr>
                                        <th>Is One Time</th>
                                        <td>
                                            <span
                                                v-if="
                                                    Number(
                                                        coupon.is_one_time,
                                                    ) === 1
                                                "
                                                class="badge badge-success"
                                            >
                                                Yes
                                            </span>

                                            <span
                                                v-else
                                                class="badge badge-secondary"
                                            >
                                                No
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Status -->
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span
                                                v-if="
                                                    coupon.status === 'active'
                                                "
                                                class="badge badge-success"
                                            >
                                                Active
                                            </span>

                                            <span
                                                v-else
                                                class="badge badge-danger"
                                            >
                                                Inactive
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <!-- Created By -->
                                    <tr>
                                        <th width="220">Created By</th>
                                        <td>
                                            {{
                                                coupon.created_by?.name || "N/A"
                                            }}
                                        </td>
                                    </tr>

                                    <!-- Updated By -->
                                    <tr>
                                        <th>Updated By</th>
                                        <td>
                                            {{
                                                coupon.updated_by?.name || "N/A"
                                            }}
                                        </td>
                                    </tr>

                                    <!-- Deleted By -->
                                    <tr>
                                        <th>Deleted By</th>
                                        <td>
                                            {{
                                                coupon.deleted_by?.name || "N/A"
                                            }}
                                        </td>
                                    </tr>

                                    <!-- Created At -->
                                    <tr>
                                        <th>Created At</th>
                                        <td>
                                            {{ formatDate(coupon.created_at) }}
                                        </td>
                                    </tr>

                                    <!-- Updated At -->
                                    <tr>
                                        <th>Updated At</th>
                                        <td>
                                            {{ formatDate(coupon.updated_at) }}
                                        </td>
                                    </tr>

                                    <!-- Deleted At -->
                                    <tr>
                                        <th>Deleted At</th>
                                        <td>
                                            {{
                                                coupon.deleted_at
                                                    ? formatDate(
                                                          coupon.deleted_at,
                                                      )
                                                    : "N/A"
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Description if you add it later -->
                    <div v-if="coupon.description" class="row mt-3">
                        <div class="col-md-12">
                            <div class="card border">
                                <div class="card-header">
                                    <strong>Description</strong>
                                </div>

                                <div
                                    class="card-body"
                                    v-html="coupon.description"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-4">
                        <button
                            class="btn btn-secondary"
                            @click="$router.back()"
                        >
                            <i class="fas fa-arrow-left mr-1"></i>
                            Back
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Data -->
            <div v-else class="m-4">
                <div class="alert alert-warning">Coupon data not found.</div>

                <button class="btn btn-secondary" @click="$router.back()">
                    Back
                </button>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { format } from "date-fns";

export default {
    name: "CouponDetails",

    data() {
        return {
            coupon: null,
            isLoading: false,
            errorMessage: "",
        };
    },

    methods: {
        async getCoupon() {
            this.isLoading = true;
            this.errorMessage = "";

            try {
                const id = this.$route.params.id;

                // IMPORTANT:
                // Agar backend route /coupons/{id} hai
                // to yahan coupons/${id} use karo.
                const response = await axios.get(`coupons/${id}`);

                if (response.data.status === "success") {
                    this.coupon = response.data.coupon;
                } else {
                    this.errorMessage =
                        response.data.message || "Failed to fetch coupon.";
                }
            } catch (error) {
                console.error("Coupon Details Error:", error);

                this.errorMessage =
                    error.response?.data?.message ||
                    "Failed to fetch coupon details.";
            } finally {
                this.isLoading = false;
            }
        },

        formatDate(date) {
            if (!date) {
                return "N/A";
            }

            try {
                return format(new Date(date), "dd-MM-yyyy hh:mm a");
            } catch (error) {
                return "N/A";
            }
        },
    },

    mounted() {
        this.getCoupon();
    },
};
</script>

<style scoped>
.table th {
    background: #f8f9fa;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 13px;
    padding: 6px 10px;
}
</style>
