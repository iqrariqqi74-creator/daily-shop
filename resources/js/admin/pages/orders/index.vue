<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mx-5 my-5 mb-4">
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-1">
                                    <h6
                                        class="m-0 font-weight-bold text-primary"
                                    >
                                        Orders
                                    </h6>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                </div>
                                <div class="col-md-6 text-center mb-3">
                                    <form class="">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control bg-light small"
                                                placeholder="Search for..."
                                                aria-label="Search"
                                                aria-describedby="basic-addon2"
                                                v-model="searchOrders"
                                                @input="handelSearch"
                                            />
                                            <div class="input-group-append">
                                                <button
                                                    class="btn btn-info"
                                                    type="button"
                                                >
                                                    <i
                                                        class="fas fa-search fa-sm"
                                                    ></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2 text-right">
                                    <span
                                        class="text-info"
                                        @click="goToCreatePage"
                                        ><i class="fas fa-plus"></i> Add
                                        New</span
                                    >
                                </div>
                                <div class="col-12 my-4">
                                    <h6
                                        class="m-0 font-weight-bold text-primary"
                                    >
                                        Orders Status
                                    </h6>
                                    <div
                                        class="btn-group status-tabs"
                                        role="group"
                                    >
                                        <button
                                            class="btn"
                                            :class="getTabClass('')"
                                            @click="setStatusFilter('')"
                                        >
                                            All ({{ totalOrders }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getTabClass(1)"
                                            @click="setStatusFilter(1)"
                                        >
                                            Placed ({{ placedCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getTabClass(2)"
                                            @click="
                                                setStatusFilter(2)
                                            "
                                        >
                                            On The Way ({{ onTheWayCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getTabClass(4)"
                                            @click="
                                                setStatusFilter(4)
                                            "
                                        >
                                            Cancelled ({{ cancelledCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getTabClass(3)"
                                            @click="
                                                setStatusFilter(3)
                                            "
                                        >
                                            Delivered ({{ deliveredCount }})
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6
                                        class="m-0 font-weight-bold text-primary"
                                    >
                                        Payment Status
                                    </h6>
                                    <div
                                        class="btn-group status-tabs"
                                        role="group"
                                    >
                                        <button
                                            class="btn"
                                            :class="getPaymentTabClass('')"
                                            @click="setPaymentStatusFilter('')"
                                        >
                                            All ({{ totalOrders }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getPaymentTabClass(1)"
                                            @click="setPaymentStatusFilter(1)"
                                        >
                                            Pending ({{ pendingCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getPaymentTabClass(2)"
                                            @click="
                                                setPaymentStatusFilter(2)
                                            "
                                        >
                                            Completed ({{ completedCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getPaymentTabClass(3)"
                                            @click="
                                                setPaymentStatusFilter(3)
                                            "
                                        >
                                            Cancelled ({{ cancelledPaymentCount }})
                                        </button>

                                        <button
                                            class="btn"
                                            :class="getPaymentTabClass(4)"
                                            @click="
                                                setPaymentStatusFilter(4)
                                            "
                                        >
                                            Processing ({{ processedCount }})
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>From Date:</label>
                                    <vue-date-picker
                                        v-model="fromDate"
                                        :format="'dd-MM-yyyy'"
                                        placeholder="Select start date"
                                    />
                                </div>
                                <div class="col-md-3">
                                    <label>To Date:</label>
                                    <vue-date-picker
                                        v-model="toDate"
                                        :format="'dd-MM-yyyy'"
                                        placeholder="Select end date"
                                    />
                                </div>
                                <div class="col-md-3 align-self-end">
                                    <button
                                        class="btn btn-primary"
                                        @click="filterByDate"
                                    >
                                        Filter
                                    </button>
                                    <button
                                        class="btn btn-secondary ml-2"
                                        @click="clearDateFilter"
                                    >
                                        Clear
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div
                                    class="d-flex align-items-center justify-content-center"
                                    v-if="isLoading"
                                >
                                    <div>
                                        <img width="100" :src="loaderImage" />
                                        <p class="text-center mt-0">
                                            Please wait...
                                        </p>
                                    </div>
                                </div>
                                <table
                                    v-show="!isLoading"
                                    class="table table-striped"
                                    id="dataTable"
                                    width="100%"
                                    cellspacing="0"
                                >
                                    <thead>
                                        <tr>
                                            <th>
                                                ID
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="sortBy('id', 'asc')"
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy('id', 'desc')
                                                    "
                                                ></i>
                                            </th>
                                            <th>
                                                Customer
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy('name', 'asc')
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy('name', 'desc')
                                                    "
                                                ></i>
                                            </th>

                                            <th>
                                                Amount
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'total_amount',
                                                            'asc',
                                                        )
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'total_amount',
                                                            'desc',
                                                        )
                                                    "
                                                ></i>
                                            </th>

                                            <th>
                                                Payment Method
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'payment_type',
                                                            'asc',
                                                        )
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'payment_type',
                                                            'desc',
                                                        )
                                                    "
                                                ></i>
                                            </th>

                                            <th>
                                                Status
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy('order_status', 'asc')
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy('order_status', 'desc')
                                                    "
                                                ></i>
                                            </th>
                                            <th>Update Order Status</th>
                                            <th>
                                                Payment Status
                                                <i
                                                    class="fas fa-arrow-down sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'payment_status',
                                                            'asc',
                                                        )
                                                    "
                                                ></i>
                                                <i
                                                    class="fas fa-arrow-up sm-d-none ml-1"
                                                    style="font-size: 8.5px"
                                                    @click="
                                                        sortBy(
                                                            'payment_status',
                                                            'desc',
                                                        )
                                                    "
                                                ></i>
                                            </th>
                                            <th>Update Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="orders.length == 0">
                                            <td colspan="12">
                                                <h4 class="text-center">
                                                    No data found.
                                                </h4>
                                            </td>
                                        </tr>
                                        <tr
                                            v-for="(order, index) in orders"
                                            :key="index"
                                             :class="getPaymentRowClass(order.payment_status)"
                                             @click="goToOrderDetails(order.id)"
                                            style="cursor: pointer;"
                                        >
                                            <td>
                                                {{ order.id }}
                                            </td>
                                            <td>
                                                {{ order.name }}
                                            </td>

                                            <td>{{ order.total_amount }}</td>

                                            <td>
                                                <span
                                                    class="badge badge-warning"
                                                    v-if="
                                                        order.payment_type ==
                                                        'credit_card'
                                                    "
                                                >
                                                    Credit Card
                                                </span>
                                                <span
                                                    class="badge badge-info"
                                                    v-else-if="
                                                        order.payment_type ==
                                                        'debit_card'
                                                    "
                                                    >Debit Card</span
                                                >
                                                <span
                                                    class="badge badge-success"
                                                    v-else-if="
                                                        order.payment_type ==
                                                        'COD'
                                                    "
                                                    >Cash</span
                                                >
                                            </td>

                                            <td>
                                                <span
                                                    class="badge badge-warning"
                                                    v-if="
                                                        order.order_status ==
                                                        '1'
                                                    "
                                                    >Placed</span
                                                >
                                                <span
                                                    class="badge badge-primary"
                                                    v-else-if="
                                                        order.order_status ==
                                                        '2'
                                                    "
                                                    >On The Way</span
                                                >
                                                <span
                                                    class="badge badge-success"
                                                    v-else-if="
                                                        order.order_status ==
                                                        '3'
                                                    "
                                                    >Delivered</span
                                                >
                                                <span
                                                    class="badge badge-danger"
                                                    v-else-if="
                                                        order.order_status ==
                                                        '4'
                                                    "
                                                    >Canceled</span
                                                >
                                            </td>

                                            <td>
                                                <select
                                                    class="form-control"
                                                    v-model="order.order_status"
                                                    @click.stop
                                                    @change="
                                                        confirmStatusUpdate(
                                                            order,
                                                        )
                                                    "
                                                >
                                                    <option value="1">
                                                        Placed
                                                    </option>
                                                    <option value="2">
                                                        On The Way
                                                    </option>
                                                    <option value="3">
                                                        Delivered
                                                    </option>
                                                    <option value="4">
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge"
                                                    :class="getPaymentStatusBadge(order.payment_status)"
                                                >
                                                    {{ getPaymentStatusText(order.payment_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <select
                                                    class="form-control"
                                                    v-model="order.payment_status"
                                                    @click.stop
                                                    @change="
                                                        confirmPaymentStatusUpdate(
                                                            order,
                                                        )
                                                    "
                                                >
                                                    <option value="1">
                                                        Pending
                                                    </option>
                                                    <option value="2">
                                                        Completed
                                                    </option>
                                                    <option value="4">
                                                        Processing
                                                    </option>
                                                    <option value="3">
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-show="!isLoading">
                                <pagination
                                    :totalPages="totalPages"
                                    :perPage="50"
                                    :currentPage="currentPage"
                                    @pageChanged="onPageChange"
                                />
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
import Pagination from "../../pages/Pagination.vue";
import { format } from "date-fns";
import { VueDatePicker } from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    name: "orderIndex",
    components: {
        Pagination,
        VueDatePicker,
    },
    data() {
        return {
            errorMessage: "",
            debug: "",
            orders: [],
            companies: [],
            packages: [],
            totalPages: 0,
            currentPage: 1,
            isLoading: true,
            params: {
                page: 1,
            },
            searchOrders: "",
            loaderImage: "/images/Spinning arrows.gif",
            statusFilter: "", // status filter for active/inactive orders
            paymentStatusFilter: "", // payment status filter for active/inactive orders

            fromDate: null, // Filter start date
            toDate: null, // Filter end date
        };
    },
    computed: {
        totalOrders() {
            return this.orders.length;
        },
        placedCount() {
            return this.orders.filter((c) => c.order_status === 1)
                .length;
        },
        onTheWayCount() {
            return this.orders.filter((c) => c.order_status === 2)
                .length;
        },
        cancelledCount() {
            return this.orders.filter((c) => c.order_status === 4)
                .length;
        },
        deliveredCount() {
            return this.orders.filter((c) => c.order_status === 3)
                .length;
        },
        pendingCount() {
            return this.orders.filter(
                (c) => Number(c.payment_status) === 1
            ).length;
        },

        completedCount() {
            return this.orders.filter(
                (c) => Number(c.payment_status) === 2
            ).length;
        },

        processedCount() {
            return this.orders.filter(
                (c) => Number(c.payment_status) === 3
            ).length;
        },

        cancelledPaymentCount() {
            return this.orders.filter(
                (c) => Number(c.payment_status) === 4
            ).length;
        },
    },
    methods: {
        getImage(path) {
            if (path.startsWith("http")) {
                return path;
            }
            return "http://127.0.0.1:8000/" + path;
        },
        async getOrders() {
            try {
                this.isLoading = true;
                await axios
                    .get("orders", {
                        params: this.params,
                    })
                    .then((response) => {
                        this.orders = response.data.orders;
                        this.totalPages = response.data.total_pages;
                        this.isLoading = false;
                    });
            } catch (error) {
                this.orders = [];
                notify.authError(error);
                this.isLoading = false;
            }
        },
        filterByDate() {
            // send dates in backend-friendly format (yyyy-MM-dd)
            this.params.from_date = this.fromDate
                ? this.formatDateForAPI(this.fromDate)
                : null;
            this.params.to_date = this.toDate
                ? this.formatDateForAPI(this.toDate)
                : null;
            this.getOrders(); // reload orders with date filter
        },
        clearDateFilter() {
            this.fromDate = null;
            this.toDate = null;
            delete this.params.from_date;
            delete this.params.to_date;
            this.getOrders();
        },
        formatDateForAPI(date) {
            if (!date) return null;
            return format(new Date(date), "yyyy-MM-dd"); // backend expects this format
        },
        async deleteOrder(order, index) {
            let response = await this.$swal({
                title: "Are you sure ?",
                text: "You want to delete this order?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it !",
                cancelButtonText: "No, cancel !",
                closeOnConfirm: false,
                closeOnCancel: false,
            }).then((result) => {
                if (result.value) {
                    try {
                        this.isLoading = true;
                        axios
                            .delete(`orders/${order.id}`)
                            .then((response) => {
                                this.$swal({
                                    title: "Success!",
                                    text: "order deleted successfully.",
                                    icon: "success",
                                    confirmButtonText: "Ok",
                                });
                                this.orders.splice(index, 1);
                                this.isLoading = false;
                            })
                            .catch((error) => {
                                this.isLoading = false;
                                this.$swal({
                                    title: "Warning!",
                                    html: error.response.data.message,
                                    icon: "warning",
                                    confirmButtonText: "Ok",
                                });
                            });
                    } catch (error) {
                        this.isLoading = false;
                        this.$swal({
                            title: "Warning!",
                            html: error.response.data.message,
                            icon: "warning",
                            confirmButtonText: "Ok",
                        });
                    }
                }
            });
        },
        onPageChange(page) {
            this.params.page = page;
            this.currentPage = page;
            this.getOrders();
        },

        async confirmStatusUpdate(order) {
            const oldStatus = order.order_status; // backup in case user cancels
            const result = await this.$swal({
                title: "Are you sure?",
                text: `Do you want to update status to "${order.order_status}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel",
            });

            if (result.isConfirmed) {
                // send request to backend to update status
                try {
                    await axios.post(`orders/${order.id}/status`, {
                        status: order.order_status,
                    });
                    this.$swal(
                        "Updated!",
                        "Status has been updated.",
                        "success",
                    );
                } catch (err) {
                    order.order_status = oldStatus; // revert on error
                    this.$swal("Error!", "Failed to update status.", "error");
                }
            } else {
                order.order_status = oldStatus; // revert if cancelled
            }
        },
        async confirmPaymentStatusUpdate(order) {
            const oldStatus = order.payment_status; // backup in case user cancels
            const result = await this.$swal({
                title: "Are you sure?",
                text: `Do you want to update payment status to "${order.payment_status}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel",
            });

            if (result.isConfirmed) {
                // send request to backend to update status
                try {
                    await axios.post(`orders/${order.id}/payment-status`, {
                        status: order.payment_status,
                    });
                    this.$swal(
                        "Updated!",
                        "Payment status has been updated.",
                        "success",
                    );
                } catch (err) {
                    order.payment_status = oldStatus; // revert on error
                    this.$swal("Error!", "Failed to update payment status.", "error");
                }
            } else {
                order.payment_status = oldStatus; // revert if cancelled
            }
        },

        goToCreatePage() {
            this.$router.push("/admin/orders/create");
        },
        editPage(order) {
            this.$router.push(`/admin/orders/${order.id}/edit`);
        },
        viewOrder(order) {
            this.$router.push(`/admin/orders/${order.id}/details`);
        },

        async handelSearch() {
            this.params.search = this.searchOrders;
            this.getOrders();
        },

        setStatusFilter(status) {
            this.statusFilter = status;
            this.params.status = status; // API ke liye status filter set karna
            this.getOrders();
        },
        setPaymentStatusFilter(paymentStatus) {
            this.paymentStatusFilter = paymentStatus;
            this.params.payment_status = paymentStatus; // API ke liye payment status filter set karna
            this.getOrders();
        },
        getTabClass(tab) {
            if (this.statusFilter == tab) {
                switch (Number(tab)) {
                    case 1:
                        return "active-tab warning"; // Placed - yellow

                    case 2:
                        return "active-tab primary"; // On The Way - blue

                    case 3:
                        return "active-tab success"; // Delivered - green

                    case 4:
                        return "active-tab danger"; // Cancelled - red

                    default:
                        return "active-tab secondary"; // All - gray
                }
            }

            return "btn-outline-secondary";
        },

        getPaymentTabClass(tab) {
            if (this.paymentStatusFilter == tab) {
                switch (Number(tab)) {
                    case 1:
                        return "active-tab warning"; // Pending - yellow

                    case 2:
                        return "active-tab success"; // Completed - blue

                    case 3:
                        return "active-tab danger"; // Cancelled - green

                    case 4:
                        return "active-tab primary"; // Processing - red

                    default:
                        return "active-tab secondary"; // All - gray
                }
            }

            return "btn-outline-secondary";
        },

        sortBy(column, order) {
            this.params.sort_by = column;
            this.params.order = order;
            this.getOrders();
        },

        getPaymentStatusText(status) {
            switch (Number(status)) {
                case 1:
                    return "Pending";
                case 2:
                    return "Completed";
                case 3:
                    return "Cancelled";
                case 4:
                    return "Processing";
                default:
                    return "Unknown";
            }
        },

        getPaymentStatusBadge(status) {
            switch (Number(status)) {
                case 1:
                    return "badge-warning";   // Pending - Yellow

                case 2:
                    return "badge-success";   // Completed - Green

                case 3:
                    return "badge-danger";      // Cancelled - Blue

                case 4:
                    return "badge-secondary"; // Processing - Gray

                default:
                    return "badge-dark";
            }
        },
        getPaymentRowClass(status) {
            switch (Number(status)) {
                case 1:
                    return "payment-row-pending";

                case 2:
                    return "payment-row-completed";

                case 3:
                    return "payment-row-cancelled";

                case 4:
                    return "payment-row-processing";

                default:
                    return "";
            }
        },
         goToOrderDetails(orderId) {
                this.$router.push(`/admin/orders/details/${orderId}`);
            },
        formatDate(date) {
            if (!date) return "";
            return format(new Date(date), "dd-MM-yyyy"); // 30-11-2025 format
        },
    },
    mounted() {
        this.getOrders();
    },
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

.status-tabs .active-tab.primary {
    border-bottom: 3px solid #007bff;
    /* blue underline */
    color: #007bff;
    /* blue text */
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
.payment-row-pending td {
    background-color: #edb90d !important;
}

.payment-row-completed td {
    background-color: #28a745 !important;
}

.payment-row-cancelled td {
    background-color: #dc3545 !important;
}

.payment-row-processing td {
    background-color: #007bff !important;
}
</style>
