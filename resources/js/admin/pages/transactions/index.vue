<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mx-5 my-5 mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
                                </div>

                                <div class="col-md-4 text-center mb-3">
                                    <div class="btn-group status-tabs" role="group">
                                        <button class="btn" :class="getTabClass('')" @click="setStatusFilter('')">
                                            All ({{ totalTransactions }})
                                        </button>



                                        <button class="btn" :class="getTabClass('completed')"
                                            @click="setStatusFilter('completed')">
                                            Completed ({{ completedCount }})
                                        </button>

                                        <button class="btn" :class="getTabClass('cancelled')"
                                            @click="setStatusFilter('cancelled')">
                                            Cancelled ({{ cancelledCount }})
                                        </button>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <form class="">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2" v-model="searchTransaction"
                                                @input="handelSearch">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>


                                <div class="col-md-2 text-right"><span class="text-info" @click="goToCreatePage"><i
                                            class="fas fa-plus"></i> Add New</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>From Date:</label>
                                    <vue-date-picker v-model="fromDate" :format="'dd-MM-yyyy :hh:mm a'"
                                        placeholder="Select start date" />
                                </div>
                                <div class="col-md-3">
                                    <label>To Date:</label>
                                    <vue-date-picker v-model="toDate" :format="'dd-MM-yyyy :hh:mm a'"
                                        placeholder="Select end date" />
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button class="btn btn-primary" @click="filterByDate">Filter</button>
                                    <button class="btn btn-secondary ml-2" @click="clearDateFilter">Clear</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                                    <div>
                                        <img width="100" :src="loaderImage" />
                                        <p class="text-center mt-0">Please wait...</p>
                                    </div>
                                </div>
                                <table v-show="!isLoading" class="table table-striped" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('id', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('id', 'desc')"></i>
                                            </th>
                                            <th>
                                                Customer
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('customer_id', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('customer_id', 'desc')"></i>
                                            </th>
                                            <th>
                                                Vendor
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('vendor_id', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('vendor_id', 'desc')"></i>
                                            </th>
                                            <th>
                                                Transaction ID
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_number', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_number', 'desc')"></i>
                                            </th>
                                            <th>
                                                Date Time
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_date_time', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_date_time', 'desc')"></i>
                                            </th>
                                            <th>
                                                Amount
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('amount', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('amount', 'desc')"></i>
                                            </th>
                                            <th> Payment Method
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('payment_method', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('payment_method', 'desc')"></i>
                                            </th>
                                            <th> Transaction Type
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_type', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('transaction_type', 'desc')"></i>
                                            </th>
                                            <th> Service Type
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('service_type', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('service_type', 'desc')"></i>
                                            </th>

                                            <th>
                                                Status
                                                <i class="fas fa-arrow-down sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('status', 'asc')"></i>
                                                <i class="fas fa-arrow-up sm-d-none ml-1" style="font-size: 8.5px"
                                                    @click="sortBy('status', 'desc')"></i>
                                            </th>
                                            <th>
                                                Update Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="transactions.length == 0">
                                            <td colspan="12">
                                                <h4 class="text-center">No data found.</h4>
                                            </td>
                                        </tr>
                                        <tr v-for="(transaction, index) in transactions" :key="index">

                                            <td>
                                                {{ transaction.id }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img :src="getImage(transaction.customer.image)" width="50"
                                                        height="50" class="rounded-circle mr-2" alt="Customer Image" />
                                                    <div>
                                                        <small class="d-block font-weight-bold">{{
                                                            transaction.customer.name
                                                        }}</small>
                                                        <small class="d-block text-muted">{{ transaction.customer.email
                                                        }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img :src="getImage(transaction.vendor.image)" width="50"
                                                        height="50" class="rounded-circle mr-2" alt="Vendor Image" />
                                                    <div>
                                                        <small class="d-block font-weight-bold">{{
                                                            transaction.vendor.name
                                                        }}</small>
                                                        <small class="d-block text-muted">{{ transaction.vendor.email
                                                        }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ transaction.transaction_number }}</td>

                                            <td>{{ transaction.transaction_date_time }}</td>
                                            <td>{{ transaction.amount }} KD</td>
                                            <td>

                                                <span class="badge badge-warning"
                                                    v-if="transaction.payment_method == 'credit_card'">
                                                    Credit Card
                                                </span>
                                                <span class="badge badge-info"
                                                    v-else-if="transaction.payment_method == 'debit_card'">Debit
                                                    Card</span>
                                                <span class="badge badge-success"
                                                    v-else-if="transaction.payment_method == 'cash'">Cash</span>

                                            </td>
                                            <td>

                                                <span class="badge badge-warning"
                                                    v-if="transaction.transaction_type == 'vendor_payout'">Vendor
                                                    Payout</span>
                                                <span class="badge badge-success"
                                                    v-else-if="transaction.transaction_type == 'customer_payment'">Customer
                                                    Payment</span>
                                            </td>
                                            <td>{{ transaction.service_type }}</td>

                                            <td>
                                                <span class="badge badge-success"
                                                    v-if="transaction.status == 'completed'">completed</span>
                                                <span class="badge badge-danger"
                                                    v-else-if="transaction.status == 'cancelled'">cancelled</span>
                                            </td>

                                            <td>
                                                <select class="form-control" v-model="transaction.status"
                                                    @change="confirmStatusUpdate(transaction)">
                                                    <option value="completed">Completed</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-show="!isLoading">
                                <pagination :totalPages="totalPages" :perPage="50" :currentPage="currentPage"
                                    @pageChanged="onPageChange" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

import * as notify from "../../../utils/notify.js";
import axios from "axios";
import Pagination from "../../pages/Pagination.vue"
import { VueDatePicker } from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { format } from 'date-fns';

export default {
    name: 'orderIndex',
    components: {
        Pagination,
        VueDatePicker
    },
    data() {
        return {
            errorMessage: '',
            debug: '',
            transactions: [],
            totalPages: 0,
            currentPage: 1,
            isLoading: true,
            params: {
                page: 1,
            },
            searchTransaction: '',
            loaderImage: "/images/Spinning arrows.gif",
            statusFilter: '', // status filter for active/inactive transactions

            fromDate: null,  // Filter start date
            toDate: null     // Filter end date

        }
    },
    computed: {
        totalTransactions() {
            return this.transactions.length;
        },
        pendingCount() {
            return this.transactions.filter(c => c.status === 'pending').length;
        },
        completedCount() {
            return this.transactions.filter(c => c.status === 'completed').length;
        },
        cancelledCount() {
            return this.transactions.filter(c => c.status === 'cancelled').length;
        },
        processingCount() {
            return this.transactions.filter(c => c.status === 'processing').length;
        }
    },
    methods: {
        getImage(path) {
            if (path.startsWith('http')) {
                return path;
            }
            return 'http://127.0.0.1:8000/' + path;
        },
        async getTransactions() {
            try {
                this.isLoading = true
                await axios.get("transactions", {
                    params: this.params
                }).then(response => {
                    this.transactions = response.data.transactions;
                    this.totalPages = response.data.total_pages;
                    this.isLoading = false;
                })
            }
            catch (error) {
                this.transactions = []
                notify.authError(error);
                this.isLoading = false;

            }
        },
        filterByDate() {
            // send dates in backend-friendly format (yyyy-MM-dd)
            this.params.from_date = this.fromDate ? this.formatDateForAPI(this.fromDate) : null;
            this.params.to_date = this.toDate ? this.formatDateForAPI(this.toDate) : null;
            this.getTransactions(); // reload transactions with date filter
        },
        clearDateFilter() {
            this.fromDate = null;
            this.toDate = null;
            delete this.params.from_date;
            delete this.params.to_date;
            this.getTransactions();
        },
        formatDateForAPI(date) {
            if (!date) return null;
            return format(new Date(date), 'yyyy-MM-dd'); // backend expects this format
        },
        async deleteOrder(order, index) {
            let response = await this.$swal({
                title: "Are you sure ?",
                text: 'You want to delete this order?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !",
                cancelButtonText: "No, cancel !",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((result) => {
                if (result.value) {
                    try {
                        this.isLoading = true;
                        axios.delete(`transactions/${order.id}`).then(response => {
                            this.$swal({
                                title: 'Success!',
                                text: 'order deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                            this.transactions.splice(index, 1);
                            this.isLoading = false;
                        }).catch(error => {
                            this.isLoading = false;
                            this.$swal({
                                title: 'Warning!',
                                html: error.response.data.message,
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            });
                        });
                    }
                    catch (error) {
                        this.isLoading = false;
                        this.$swal({
                            title: 'Warning!',
                            html: error.response.data.message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        },
        onPageChange(page) {
            this.params.page = page;
            this.currentPage = page;
            this.getTransactions();
        },

        async confirmStatusUpdate(transaction) {
            const oldStatus = transaction.status; // backup in case user cancels
            const result = await this.$swal({
                title: 'Are you sure?',
                text: `Do you want to update status to "${transaction.status}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                // send request to backend to update status
                try {
                    await axios.post(`transactions/${transaction.id}/status`, { status: transaction.status });
                    this.$swal('Updated!', 'Status has been updated.', 'success');
                } catch (err) {
                    transaction.status = oldStatus; // revert on error
                    this.$swal('Error!', 'Failed to update status.', 'error');
                }
            } else {
                transaction.status = oldStatus; // revert if cancelled
            }
        },

        goToCreatePage() {
            this.$router.push('/admin/transactions/create');
        },
        editPage(order) {
            this.$router.push(`/admin/transactions/${order.id}/edit`);
        },
        viewOrder(order) {
            this.$router.push(`/admin/transactions/${order.id}/details`);
        },

        async handelSearch() {
            this.params.search = this.searchTransaction;
            this.getTransactions();
        },

        setStatusFilter(status) {
            this.statusFilter = status;
            this.params.status = status; // API ke liye status filter set karna
            this.getTransactions();
        },
        getTabClass(tab) {
            if (this.statusFilter === tab) {
                switch (tab) {
                    case 'completed': return 'active-tab success'; // green
                    case 'cancelled': return 'active-tab danger';  // red
                    default: return 'active-tab secondary';        // all → gray
                }
            } else {
                return 'btn-outline-secondary'; // unselected
            }
        },

        sortBy(column, order) {
            this.params.sort_by = column;
            this.params.order = order;
            this.getTransactions();
        },


        async getOptions() {
            try {
                this.isLoading = true;
                await axios
                    .get("common_options", {
                        params: {
                            options: `package,company`,
                        },
                    })
                    .then((response) => {
                        this.packages = response.data.packages;
                        this.companies = response.data.companies;
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

        formatDate(date) {
            if (!date) return '';
            return format(new Date(date), 'dd-MM-yyyy'); // 30-11-2025 format
        }
    },
    mounted() {
        this.getTransactions();

    }
};
</script>

<style type="text/css">
.fas,
.text-info {
    cursor: pointer;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    color: gray;
}

.status-tabs .btn {
    border: none;
    border-bottom: 2px solid transparent;
    font-weight: 500;
    transition: all 0.3s;
    margin-right: 5px;
}

.status-tabs .active-tab.success {
    border-bottom: 3px solid #28a745;
    /* green underline */
    color: #28a745;
    /* green text */
    font-weight: 600;
}

.status-tabs .active-tab.danger {
    border-bottom: 3px solid #dc3545;
    /* red underline */
    color: #dc3545;
    /* red text */
    font-weight: 600;
}

.status-tabs .active-tab.warning {
    border-bottom: 3px solid #ffc107;
    /* yellow underline */
    color: #ffc107;
    /* yellow text */
    font-weight: 600;
}

.status-tabs .active-tab.info {
    border-bottom: 3px solid #17a2b8;
    /* blue underline */
    color: #17a2b8;
    /* blue text */
    font-weight: 600;
}

.status-tabs .active-tab.secondary {
    border-bottom: 3px solid #6c757d;
    /* gray underline */
    color: #6c757d;
    /* gray text */
    font-weight: 600;
}

/* hover effect */
.status-tabs .btn:hover {
    background-color: rgba(0, 0, 0, 0.05);
}
</style>
