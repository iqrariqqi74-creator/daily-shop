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
                                <h6 class="m-0 font-weight-bold text-primary">Create Order</h6>
                            </div>

                            <div class="card-body">
                                <!-- Form Row 1 -->
                                <div class="row mb-3">
                                    <!-- Customer -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer</label>
                                            <select class="form-control" v-model="order.customer_id" :class="{
                                                'is-invalid': errorMessages.customer_id,
                                                'is-valid': order.customer_id && !errorMessages.customer_id
                                            }">
                                                <option value="">Select Customer</option>
                                                <option v-for="c in customers" :key="c.id" :value="c.id">{{ c.name }}
                                                </option>
                                            </select>
                                            <small class="text-danger">{{ errorMessages.customer_id?.[0] }}</small>
                                        </div>
                                    </div>
                                    <!-- Vendor -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="vendor_id">Vendors</label>
                                            <select class="form-control" v-model="order.vendor_id" :class="{
                                                'is-invalid': errorMessages.vendor_id,
                                                'is-valid': order.vendor_id && !errorMessages.vendor_id
                                            }">
                                                <option value="">Select Vendor</option>
                                                <option v-for="v in vendors" :key="v.id" :value="v.id">{{ v.name }}
                                                </option>
                                            </select>
                                            <small class="text-danger">{{ errorMessages.vendor_id?.[0] }}</small>
                                        </div>
                                    </div>

                                    <!-- Service Type -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="service_type">Service Type</label>
                                            <input type="text" class="form-control" v-model="order.service_type" :class="{
                                                'is-invalid': errorMessages.service_type,
                                                'is-valid': order.service_type && !errorMessages.service_type
                                            }" placeholder="Service Type" />
                                            <small class="text-danger">{{ errorMessages.service_type?.[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Row 2: Dates and Amount -->
                                <div class="row mb-3">
                                    <!-- Start Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="order_start_date">Start Date</label>
                                            <vue-date-picker v-model="orderStartDate"
                                                @update:modelValue="handleDateValueChange($event, 'order_start_date')" />
                                            <small class="text-danger">{{ errorMessages.order_start_date?.[0] }}</small>
                                        </div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="order_end_date">End Date</label>
                                            <vue-date-picker v-model="orderEndDate"
                                                @update:modelValue="handleDateValueChange($event, 'order_end_date')" />
                                            <small class="text-danger">{{ errorMessages.order_end_date?.[0] }}</small>
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="number" class="form-control" v-model="order.amount" :class="{
                                                'is-invalid': errorMessages.amount,
                                                'is-valid': order.amount && !errorMessages.amount
                                            }" placeholder="Amount" />
                                            <small class="text-danger">{{ errorMessages.amount?.[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Row 3: Payment, Promo, Status -->
                                <div class="row mb-3">
                                    <!-- Payment Method -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_method">Payment Method</label>
                                            <select class="form-control" v-model="order.payment_method" :class="{
                                                'is-invalid': errorMessages.payment_method,
                                                'is-valid': order.payment_method && !errorMessages.payment_method
                                            }">
                                                <option value="">Select Payment</option>
                                                <option value="credit_card">Credit Card</option>
                                                <option value="debit_card">Debit Card</option>
                                                <option value="cash">Cash</option>
                                            </select>
                                            <small class="text-danger">{{ errorMessages.payment_method?.[0] }}</small>
                                        </div>
                                    </div>

                                    <!-- Promo Code -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="promo_code">Promo Code</label>
                                            <input type="text" class="form-control" v-model="order.promo_code" :class="{
                                                'is-invalid': errorMessages.promo_code,
                                                'is-valid': order.promo_code && !errorMessages.promo_code
                                            }" placeholder="Promo Code" />
                                            <small class="text-danger">{{ errorMessages.promo_code?.[0] }}</small>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" v-model="order.status" :class="{
                                                'is-invalid': errorMessages.status,
                                                'is-valid': order.status && !errorMessages.status
                                            }">
                                                <option value="">Select Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                            <small class="text-danger">{{ errorMessages.status?.[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="mt-3">
                                    <button class="btn btn-success" @click="createOrder" :disabled="isLoading">
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
    </section>
</template>

<script>
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { format } from "date-fns";

export default {
    name: 'OrderCreate',
    components: { VueDatePicker },
    data() {
        return {
            isLoading: false,
            loaderImage: "/images/Spinning arrows.gif",
            errorMessages: {},
            authenticateError: '',
            customers: [],
            vendors: [],
            order: {
                customer_id: "",
                order_name: "",
                service_type: "",
                order_start_date: "",
                order_end_date: "",
                amount: "",
                order_number: "",
                payment_method: "",
                promo_code: "",
                status: "pending"
            },
            orderStartDate: null,
            orderEndDate: null
        };
    },
    methods: {
        clearErrorMessages(field) {
            if (this.errorMessages[field]) this.errorMessages[field] = '';
            if (this.authenticateError) this.authenticateError = '';
        },
        handleDateValueChange(date, field) {
            if (date) this.order[field] = format(new Date(date), "yyyy-MM-dd HH:mm:ss");
        },
        async createOrder() {
            this.isLoading = true;
            try {
                const response = await axios.post("/orders", this.order);
                if (response.data.status === 'success') {
                    this.$toast.success(response.data.message);
                    this.$router.push('/admin/orders');
                }
            } catch (error) {
                this.isLoading = false;
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || '';
            }
        },
        cancel() {
            this.$router.push('/admin/orders');
        },
        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `customer,vendor`,
                        },
                    })
                    .then((response) => {
                        this.customers = response.data.customers;
                        this.vendors = response.data.vendors;
                        this.isLoading = false;
                    });
            } catch (error) {
                this.isLoading = true;
                this.$swal({
                    title: "Error!",
                    html: error.response.data.message,
                    icon: "error",
                    confirmButtonText: "Ok",
                });
                this.isLoading = false;
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
