<template>
    <!-- =====================================================
         CATG HEADER BANNER SECTION
    ===================================================== -->
    <section id="aa-catg-head-banner">
        <div class="aa-catg-head-banner-area">
            <div class="container"></div>
        </div>
    </section>

    <!-- / catg header banner section -->

    <!-- =====================================================
         ORDER VIEW
    ===================================================== -->
    <section id="cart-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="cart-view-area">
                        <div class="cart-view-table">
                            <!-- =================================================
                                 LOADING
                            ================================================= -->
                            <div
                                v-if="loading"
                                class="text-center"
                                style="padding: 30px"
                            >
                                Loading orders...
                            </div>

                            <!-- =================================================
                                 ERROR
                            ================================================= -->
                            <div
                                v-else-if="errorMessage"
                                class="alert alert-danger"
                            >
                                {{ errorMessage }}
                            </div>

                            <!-- =================================================
                                 ORDERS TABLE
                            ================================================= -->
                            <div
                                v-else-if="orders.length > 0"
                                class="table-responsive"
                            >
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                            <th>Discount</th>
                                            <th>Tax</th>
                                            <th>Total Amt</th>
                                            <th>Payment ID</th>
                                            <th>Placed At</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="order in orders"
                                            :key="order.id"
                                        >
                                            <!-- ORDER ID -->
                                            <td class="order_id_btn">
                                                <a
                                                    href="javascript:void(0)"
                                                    @click="
                                                        goToOrderDetail(
                                                            order.id,
                                                        )
                                                    "
                                                >
                                                    {{ order.id }}
                                                </a>
                                            </td>

                                            <td>
                                                <span
                                                    class="badge badge-warning"
                                                    v-if="
                                                        order.order_status == 1
                                                    "
                                                    >Placed</span
                                                >
                                                <span
                                                    class="badge badge-primary"
                                                    v-else-if="
                                                        order.order_status == 2
                                                    "
                                                    >On The Way</span
                                                >
                                                <span
                                                    class="badge badge-success"
                                                    v-else-if="
                                                        order.order_status == 3
                                                    "
                                                    >Delivered</span
                                                >
                                                <span
                                                    class="badge badge-info"
                                                    v-else-if="
                                                        order.order_status == 4
                                                    "
                                                    >Canceled</span
                                                >
                                            </td>

                                            <!-- PAYMENT STATUS -->
                                            <td>
                                                <span
                                                    class="badge badge-warning"
                                                    v-if="
                                                        order.payment_status ==
                                                        '1'
                                                    "
                                                    >Pending</span
                                                >
                                                <span
                                                    class="badge badge-success"
                                                    v-else-if="
                                                        order.payment_status ==
                                                        '2'
                                                    "
                                                    >Completed</span
                                                >
                                                <span
                                                    class="badge badge-secondary"
                                                    v-else-if="
                                                        order.payment_status ==
                                                        '3'
                                                    "
                                                    >Processing</span
                                                >
                                                <span
                                                    class="badge badge-info"
                                                    v-else-if="
                                                        order.payment_status ==
                                                        '4'
                                                    "
                                                    >Canceled</span
                                                >
                                            </td>
                                            <td>
                                                Rs
                                                {{
                                                    Number(
                                                        order.coupon_value ||
                                                            order.discount ||
                                                            0,
                                                    ).toFixed(2)
                                                }}
                                            </td>
                                            <td>
                                                Rs
                                                {{
                                                    Number(
                                                        order.tax_amount ||
                                                            order.tax ||
                                                            0,
                                                    ).toFixed(2)
                                                }}
                                            </td>

                                            <!-- TOTAL -->
                                            <td>
                                                Rs
                                                {{
                                                    Number(
                                                        order.total_amount ||
                                                            order.total_amt ||
                                                            0,
                                                    ).toFixed(2)
                                                }}
                                            </td>

                                            <!-- PAYMENT ID -->
                                            <td>
                                                {{
                                                    order.payment_id ||
                                                    order.txn_id ||
                                                    "-"
                                                }}
                                            </td>

                                            <!-- PLACED AT -->
                                            <td>
                                                {{
                                                    order.added_on ||
                                                    order.created_at ||
                                                    "-"
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- =================================================
                                 EMPTY ORDERS
                            ================================================= -->
                            <div
                                v-else
                                class="text-center"
                                style="padding: 30px"
                            >
                                <h4>No orders found.</h4>
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
import { mapGetters } from "vuex";

export default {
    name: "Orders",

    // =====================================================
    // DATA
    // =====================================================

    data() {
        return {
            orders: [],

            loading: false,

            errorMessage: "",
        };
    },

    // =====================================================
    // COMPUTED
    // =====================================================

    computed: {
        ...mapGetters(["getUserToken", "getGuestId"]),
    },

    // =====================================================
    // METHODS
    // =====================================================

    methods: {
        // =================================================
        // HEADERS
        // =================================================

        getHeaders() {
            const headers = {
                Accept: "application/json",
                "Content-Type": "application/json",
            };

            if (this.getUserToken) {
                headers.Authorization = `Bearer ${this.getUserToken}`;
            } else {
            }

            return headers;
        },

        // =================================================
        // GET ORDERS
        // =================================================

        async getOrders() {
            this.loading = true;

            this.errorMessage = "";

            try {
                // =====================================================
                // API
                // =====================================================

                const response = await axios.get("/api/order", {
                    headers: this.getHeaders(),
                });

                // =====================================================
                // SUCCESS
                // =====================================================

                if (response.data?.status) {
                    const result =
                        response.data.result || response.data.orders || [];

                    if (Array.isArray(result)) {
                        this.orders = result;
                    } else if (Array.isArray(result.list)) {
                        this.orders = result.list;
                    } else {
                        this.orders = [];
                    }
                } else {
                    this.orders = [];
                }
            } catch (error) {
                this.orders = [];
                // TOAST
                this.$toast.error(message);

                this.errorMessage =
                    error.response?.data?.message ||
                    "Something went wrong while loading orders.";
            } finally {
                this.loading = false;
            }
        },

        // =================================================
        // ORDER STATUS
        // =================================================

        getOrderStatus(order) {
            if (
                order.orders_status !== undefined &&
                order.orders_status !== null
            ) {
                return order.orders_status;
            }

            if (
                order.order_status !== undefined &&
                order.order_status !== null
            ) {
                return order.order_status;
            }

            return "Pending";
        },

        // =================================================
        // ORDER DETAIL
        // =================================================

        goToOrderDetail(orderId) {
            this.$router.push(`/orders/${orderId}`);
        },
    },

    // =====================================================
    // MOUNTED
    // =====================================================

    async mounted() {
        // Token available hai
        if (this.getUserToken) {
            await this.getOrders();

            return;
        }

        // Token Vuex mein late load ho raha ho to thora wait
        setTimeout(async () => {
            if (this.getUserToken) {
                await this.getOrders();
            } else {
                this.errorMessage = "Please login to view your orders.";
            }
        }, 500);
    },
};
</script>

<style scoped>
/* =====================================================
   ORDER ID
===================================================== */

.order_id_btn a {
    cursor: pointer;
}

/* =====================================================
   TABLE
===================================================== */

.table th,
.table td {
    vertical-align: middle !important;
}

/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {
    .table {
        font-size: 13px;
    }

    .table th,
    .table td {
        white-space: nowrap;
    }
}
</style>
