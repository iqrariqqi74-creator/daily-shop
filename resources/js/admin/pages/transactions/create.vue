<template>
    <section class="content">
        <div class="container-fluid">

            <!-- Loader -->
            <div v-if="isLoading" class="text-center">
                <img width="100" :src="loaderImage" />
                <p>Please wait...</p>
            </div>

            <div v-else>
                <div class="card shadow my-5 mx-5">

                    <div class="card-header">
                        <h5>Create Transaction</h5>
                    </div>

                    <div class="card-body">

                        <!-- 🔥 ALERTS -->
                        <div v-if="authenticateError" class="alert alert-danger">
                            {{ authenticateError }}
                        </div>

                        <div v-if="successMessage" class="alert alert-success">
                            {{ successMessage }}
                        </div>

                        <!-- ROW 1 -->
                        <div class="row mb-3">

                            <!-- Customer -->
                            <div class="col-md-4">
                                <label>Customer</label>
                                <select class="form-control" v-model="transaction.customer_id"
                                    @change="clearError('customer_id')" :class="inputClass('customer_id')">

                                    <option value="">Select Customer</option>
                                    <option v-for="c in customers" :key="c.id" :value="c.id">
                                        {{ c.name }}
                                    </option>
                                </select>
                                <small class="text-danger">{{ errorMessages.customer_id?.[0] }}</small>
                            </div>

                            <!-- Vendor -->
                            <div class="col-md-4">
                                <label>Vendor</label>
                                <select class="form-control" v-model="transaction.vendor_id"
                                    @change="clearError('vendor_id')" :class="inputClass('vendor_id')">

                                    <option value="">Select Vendor</option>
                                    <option v-for="v in vendors" :key="v.id" :value="v.id">
                                        {{ v.name }}
                                    </option>
                                </select>
                                <small class="text-danger">{{ errorMessages.vendor_id?.[0] }}</small>
                            </div>

                            <!-- Service -->
                            <div class="col-md-4">
                                <label>Service Type</label>
                                <input type="text" class="form-control" v-model="transaction.service_type"
                                    @input="clearError('service_type')" :class="inputClass('service_type')" />
                                <small class="text-danger">{{ errorMessages.service_type?.[0] }}</small>
                            </div>

                        </div>

                        <!-- ROW 2 -->
                        <div class="row mb-3">

                            <!-- Date -->
                            <div class="col-md-4">
                                <label>Transaction Date Time</label>
                                <vue-date-picker v-model="datePickerValue" :enable-time-picker="true"
                                    @update:modelValue="handleDateValueChange" />

                                <small class="text-danger">
                                    {{ errorMessages.transaction_date_time?.[0] }}
                                </small>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-4">
                                <label>Amount</label>
                                <input type="number" class="form-control" v-model="transaction.amount"
                                    @input="clearError('amount')" :class="inputClass('amount')" />
                                <small class="text-danger">{{ errorMessages.amount?.[0] }}</small>
                            </div>

                            <!-- Type -->
                            <div class="col-md-4">
                                <label>Transaction Type</label>
                                <select class="form-control" v-model="transaction.transaction_type"
                                    @change="clearError('transaction_type')" :class="inputClass('transaction_type')">

                                    <option value="">Select</option>
                                    <option value="customer_payment">Customer Payment</option>
                                    <option value="vendor_payout">Vendor Payout</option>
                                </select>
                                <small class="text-danger">{{ errorMessages.transaction_type?.[0] }}</small>
                            </div>

                        </div>

                        <!-- ROW 3 -->
                        <div class="row mb-3">

                            <!-- Payment -->
                            <div class="col-md-4">
                                <label>Payment Method</label>
                                <select class="form-control" v-model="transaction.payment_method"
                                    @change="clearError('payment_method')" :class="inputClass('payment_method')">

                                    <option value="">Select</option>
                                    <option value="cash">Cash</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="debit_card">Debit Card</option>
                                </select>
                                <small class="text-danger">{{ errorMessages.payment_method?.[0] }}</small>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <label>Status</label>
                                <select class="form-control" v-model="transaction.status" @change="clearError('status')"
                                    :class="inputClass('status')">

                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <small class="text-danger">{{ errorMessages.status?.[0] }}</small>
                            </div>

                        </div>

                        <!-- BUTTONS -->
                        <div class="mt-3">
                            <button class="btn btn-success" @click="createTransaction">
                                Save
                            </button>

                            <button class="btn btn-danger ml-2" @click="cancel">
                                Cancel
                            </button>
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

export default {
    components: { VueDatePicker },

    data() {
        return {
            isLoading: false,
            loaderImage: "/images/Spinning arrows.gif",

            customers: [],
            vendors: [],

            errorMessages: {},
            authenticateError: "",
            successMessage: "",

            datePickerValue: null,

            transaction: {
                customer_id: "",
                vendor_id: "",
                service_type: "",
                transaction_type: "",
                amount: "",
                payment_method: "",
                transaction_date_time: "",
                status: "completed",
            }
        };
    },

    methods: {

        inputClass(field) {
            return {
                'is-invalid': this.errorMessages[field],
                'is-valid': this.transaction[field] && !this.errorMessages[field]
            };
        },

        clearError(field) {
            if (this.errorMessages[field]) {
                delete this.errorMessages[field];
            }
        },

        handleDateValueChange(date) {
            if (date) {
                this.transaction.transaction_date_time =
                    format(new Date(date), "yyyy-MM-dd HH:mm:ss");
                this.clearError('transaction_date_time');
            }
        },

        async createTransaction() {
            this.isLoading = true;
            this.errorMessages = {};
            this.authenticateError = "";
            this.successMessage = "";

            try {
                const res = await axios.post("/transactions", this.transaction);

                if (res.data.status === "success") {
                    this.successMessage = "Transaction created successfully";

                    this.$toast.success("Transaction Created");

                    setTimeout(() => {
                        this.$router.push("/admin/transactions");
                    }, 1000);
                }

            } catch (error) {
                this.errorMessages = error.response?.data?.errors || {};
                this.authenticateError = error.response?.data?.message || "Something went wrong";
            }

            this.isLoading = false;
        },

        cancel() {
            this.$router.push("/admin/transactions");
        },

        async getOptions() {
            try {
                const res = await axios.get("common_options", {
                    params: { options: "customer,vendor" }
                });

                this.customers = res.data.customers;
                this.vendors = res.data.vendors;

            } catch (error) {
                console.log(error);
            }
        }
    },

    mounted() {
        this.getOptions();
    }
};
</script>

<style scoped>
.card {
    border-radius: 10px;
}

/* ✅ RED ERROR */
.is-invalid {
    border: 1px solid #dc3545 !important;
}

/* ✅ GREEN SUCCESS */
.is-valid {
    border: 1px solid #28a745 !important;
}
</style>
