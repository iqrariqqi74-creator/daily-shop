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
                                            Edit coupon
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="mb-1">
                                    <div class="row">
                                        <!-- =========================
                                         TITLE
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="title">
                                                    Title
                                                </label>

                                                <input
                                                    id="title"
                                                    type="text"
                                                    class="form-control"
                                                    v-model="coupon.title"
                                                    @input="
                                                        clearErrorMessages(
                                                            'title',
                                                        )
                                                    "
                                                    placeholder="Enter coupon title"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.title,

                                                        'is-valid':
                                                            coupon.title &&
                                                            !errorMessages.title,
                                                    }"
                                                />

                                                <small
                                                    v-if="errorMessages.title"
                                                    class="text-danger"
                                                >
                                                    {{ errorMessages.title[0] }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- =========================
                                         CODE
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="code">
                                                    Coupon Code
                                                </label>

                                                <input
                                                    id="code"
                                                    type="text"
                                                    class="form-control text-uppercase"
                                                    v-model="coupon.code"
                                                    @input="
                                                        clearErrorMessages(
                                                            'code',
                                                        )
                                                    "
                                                    placeholder="e.g. SAVE10"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.code,

                                                        'is-valid':
                                                            coupon.code &&
                                                            !errorMessages.code,
                                                    }"
                                                />

                                                <small
                                                    v-if="errorMessages.code"
                                                    class="text-danger"
                                                >
                                                    {{ errorMessages.code[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <!-- =========================
                                         Value
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="code">
                                                    Coupon Value
                                                </label>
                                                <input
                                                    id="value"
                                                    type="text"
                                                    class="form-control text-uppercase"
                                                    v-model="coupon.value"
                                                    @input="
                                                        clearErrorMessages(
                                                            'value',
                                                        )
                                                    "
                                                    placeholder="e.g. Value"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.value,

                                                        'is-valid':
                                                            coupon.value &&
                                                            !errorMessages.value,
                                                    }"
                                                />
                                                <small
                                                    v-if="errorMessages.value"
                                                    class="text-danger"
                                                >
                                                    {{ errorMessages.value[0] }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- =========================
                                         TYPE
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="type"> Type </label>

                                                <select
                                                    id="type"
                                                    name="type"
                                                    v-model="coupon.type"
                                                    @change="
                                                        clearErrorMessages(
                                                            'type',
                                                        )
                                                    "
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.type,

                                                        'is-valid':
                                                            coupon.type &&
                                                            !errorMessages.type,
                                                    }"
                                                >
                                                    <option value="">
                                                        Select Type
                                                    </option>

                                                    <option value="value">
                                                        Value
                                                    </option>

                                                    <option value="percentage">
                                                        Percentage
                                                    </option>
                                                </select>

                                                <small
                                                    v-if="errorMessages.type"
                                                    class="text-danger"
                                                >
                                                    {{ errorMessages.type[0] }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- =========================
                                         MINIMUM ORDER
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label
                                                    for="minimum_order_amount"
                                                >
                                                    Minimum Order Amount
                                                </label>

                                                <input
                                                    id="minimum_order_amount"
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control"
                                                    v-model="
                                                        coupon.minimum_order_amount
                                                    "
                                                    @input="
                                                        clearErrorMessages(
                                                            'minimum_order_amount',
                                                        )
                                                    "
                                                    placeholder="Minimum Order Amount"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.minimum_order_amount,

                                                        'is-valid':
                                                            coupon.minimum_order_amount !==
                                                                '' &&
                                                            !errorMessages.minimum_order_amount,
                                                    }"
                                                />

                                                <small
                                                    v-if="
                                                        errorMessages.minimum_order_amount
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{
                                                        errorMessages
                                                            .minimum_order_amount[0]
                                                    }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- =========================
                                         ONE TIME
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="is_one_time">
                                                    Is One Time
                                                </label>

                                                <select
                                                    id="is_one_time"
                                                    name="is_one_time"
                                                    v-model="coupon.is_one_time"
                                                    @change="
                                                        clearErrorMessages(
                                                            'is_one_time',
                                                        )
                                                    "
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.is_one_time,

                                                        'is-valid':
                                                            coupon.is_one_time !==
                                                                '' &&
                                                            coupon.is_one_time !==
                                                                null &&
                                                            !errorMessages.is_one_time,
                                                    }"
                                                >
                                                    <option value="">
                                                        Select Option
                                                    </option>

                                                    <option value="1">
                                                        Yes
                                                    </option>

                                                    <option value="0">
                                                        No
                                                    </option>
                                                </select>

                                                <small
                                                    v-if="
                                                        errorMessages.is_one_time
                                                    "
                                                    class="text-danger"
                                                >
                                                    {{
                                                        errorMessages
                                                            .is_one_time[0]
                                                    }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- =========================
                                         STATUS
                                    ========================== -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">
                                                    Status
                                                </label>

                                                <select
                                                    id="status"
                                                    name="status"
                                                    v-model="coupon.status"
                                                    @change="
                                                        clearErrorMessages(
                                                            'status',
                                                        )
                                                    "
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            errorMessages.status,

                                                        'is-valid':
                                                            coupon.status &&
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

                                                <small
                                                    v-if="errorMessages.status"
                                                    class="text-danger"
                                                >
                                                    {{
                                                        errorMessages.status[0]
                                                    }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Buttons -->
                                    <div class="mt-3">
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            @click="updateCoupon"
                                            :disabled="isLoading"
                                        >
                                            <span v-if="isLoading">
                                                Saving...
                                            </span>

                                            <span v-else>
                                                <i class="fas fa-save mr-1"></i>
                                                Save
                                            </span>
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-danger ml-3"
                                            @click="cancel"
                                            :disabled="isLoading"
                                        >
                                            <i class="fas fa-times mr-1"></i>
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
    title: "couponEdit",
    components: {},
    data() {
        return {
            errorMessages: {},
            authenticateError: "",
            coupon: {
                title: "",
                value: "",
                type: "",
                value: "",
                code: "",
                is_one_time: "",
                minimum_order_amount: "",
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

        async fetchCoupon() {
            this.isLoading = true;
            try {
                const id = this.$route.params.id;
                const res = await axios.get(`coupons/${id}`);
                this.coupon = res.data.coupon;
                this.isLoading = false;
            } catch (error) {
                this.isLoading = false;
                this.$toast.error(
                    error.response?.data?.message ||
                        "Failed to fetchCoupon coupon data",
                );
            }
        },
        async updateCoupon() {
            this.isLoading = true;

            try {
                const id = this.$route.params.id;

                const formData = new FormData();

                formData.append("title", this.coupon.title);
                formData.append("code", this.coupon.code);
                formData.append("value", this.coupon.value);
                formData.append("status", this.coupon.status);
                formData.append("value", this.coupon.value);
                formData.append("type", this.coupon.type);
                formData.append(
                    "minimum_order_amount",
                    this.coupon.minimum_order_amount,
                );
                formData.append("is_one_time", this.coupon.is_one_time);

                formData.append("_method", "PUT");

                const res = await axios.post(`/coupons/${id}`, formData);

                if (res.data.status === "success") {
                    this.$toast.success(res.data.message);

                    setTimeout(() => {
                        this.$router.push("/admin/coupons");
                    }, 1000);
                }
            } catch (error) {
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || "";
                this.$toast.error("Something went wrong");
            } finally {
                this.isLoading = false;
            }
        },
        cancel() {
            this.$router.push("/admin/coupons");
        },
        async updateStatus(coupon, status) {
            try {
                await axios.post(`coupons/${coupon.id}/status`, {
                    status: status,
                });

                coupon.status = status; // UI update

                this.$toast.success("Status updated successfully");
            } catch (error) {
                this.$toast.error("Something went wrong");
            }
        },
    },
    mounted() {
        this.fetchCoupon();
    },
};
</script>

<style scoped>
.fas,
.text-info {
    cursor: pointer;
}
</style>
