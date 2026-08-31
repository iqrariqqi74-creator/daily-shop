<template>
    <!-- =====================================================
         CATG HEADER BANNER SECTION
    ===================================================== -->
    <section id="aa-catg-head-banner">
        <div class="aa-catg-head-banner-area">
            <div class="container"></div>
        </div>
    </section>

    <!-- =====================================================
         ORDER DETAIL
    ===================================================== -->
    <section id="cart-view">
        <div class="container">
            <div class="row">
                <!-- =================================================
                     LOADING
                ================================================= -->
                <div
                    v-if="loading"
                    class="col-md-12 text-center"
                    style="padding: 30px"
                >
                    Loading order details...
                </div>

                <!-- =================================================
                     ERROR
                ================================================= -->
                <div v-else-if="errorMessage" class="col-md-12">
                    <div class="alert alert-danger">
                        {{ errorMessage }}
                    </div>
                </div>

                <!-- =================================================
                     ORDER DATA
                ================================================= -->
                <template v-else-if="orderDetails.length > 0">
                    <!-- =================================================
                         ADDRESS
                    ================================================= -->
                    <div class="col-md-6">
                        <div class="order_detail">
                            <h3>Details Address</h3>

                            <strong>
                                {{ order.name }}
                            </strong>

                            <span> ({{ order.mobile }}) </span>

                            <br />

                            {{ order.address }}

                            <br />

                            {{ order.city }}

                            <br />

                            {{ order.state }}

                            <br />

                            {{ order.pin_code || order.pincode || order.zip }}
                        </div>
                    </div>

                    <!-- =================================================
                         ORDER INFORMATION
                    ================================================= -->
                    <div class="col-md-6">
                        <div class="order_detail">
                            <h3>Order Details</h3>

                            Order Status:
                            <strong>
                                {{ order.orders_status || "Pending" }}
                            </strong>

                            <br />

                            Payment Status:
                            <strong>
                                {{ order.payment_status || "Pending" }}
                            </strong>

                            <br />

                            Payment Type:
                            <strong>
                                {{ order.payment_type || "-" }}
                            </strong>

                            <br />

                            <span v-if="order.payment_id">
                                Payment ID:
                                <strong>
                                    {{ order.payment_id }}
                                </strong>
                            </span>
                        </div>

                        <b>Track Details</b>

                        <br />

                        {{ order.track_details || "-" }}
                    </div>

                    <!-- =================================================
                         PRODUCTS
                    ================================================= -->
                    <div class="col-md-12">
                        <div class="cart-view-area">
                            <div class="cart-view-table">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Image</th>
                                                <th>Size</th>
                                                <th>Color</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <!-- =================================================
                                                 PRODUCTS
                                            ================================================= -->
                                            <tr
                                                v-for="item in orderDetails"
                                                :key="
                                                    item.id || item.product_id
                                                "
                                            >
                                                <!-- PRODUCT -->
                                                <td>
                                                    {{ item.pname || "-" }}
                                                </td>

                                                <!-- IMAGE -->
                                                <td>
                                                    <img
                                                        v-if="
                                                            item.attribute_image
                                                        "
                                                        :src="
                                                            getProductImage(
                                                                item.attribute_image,
                                                            )
                                                        "
                                                        :alt="item.pname"
                                                        class="order-product-image"
                                                    />

                                                    <span v-else> - </span>
                                                </td>

                                                <!-- SIZE -->
                                                <td>
                                                    {{ item.size || "-" }}
                                                </td>

                                                <!-- COLOR -->
                                                <td>
                                                    {{ item.color || "-" }}
                                                </td>

                                                <!-- PRICE -->
                                                <td>
                                                    Rs
                                                    {{
                                                        Number(
                                                            item.price || 0,
                                                        ).toFixed(2)
                                                    }}
                                                </td>

                                                <!-- QUANTITY -->
                                                <td>
                                                    {{ item.quantity || 0 }}
                                                </td>

                                                <!-- TOTAL -->
                                                <td>
                                                    Rs
                                                    {{
                                                        (
                                                            Number(
                                                                item.price || 0,
                                                            ) *
                                                            Number(
                                                                item.quantity ||
                                                                    0,
                                                            )
                                                        ).toFixed(2)
                                                    }}
                                                </td>
                                            </tr>

                                            <!-- =================================================
                                                 TOTAL
                                            ================================================= -->
                                            <tr>
                                                <td colspan="5">&nbsp;</td>

                                                <td>
                                                    <b>Total</b>
                                                </td>

                                                <td>
                                                    <b>
                                                        Rs
                                                        {{
                                                            totalAmount.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                    </b>
                                                </td>
                                            </tr>

                                            <!-- =================================================
                                                 COUPON
                                            ================================================= -->
                                            <tr
                                                v-if="
                                                    Number(
                                                        order.coupon_value || 0,
                                                    ) > 0
                                                "
                                            >
                                                <td colspan="5">&nbsp;</td>

                                                <td>
                                                    <b>
                                                        Coupon

                                                        <span
                                                            class="coupon_apply_txt"
                                                        >
                                                            (
                                                            {{
                                                                order.coupon_code
                                                            }}
                                                            )
                                                        </span>
                                                    </b>
                                                </td>

                                                <td>
                                                    Rs
                                                    {{
                                                        Number(
                                                            order.coupon_value,
                                                        ).toFixed(2)
                                                    }}
                                                </td>
                                            </tr>

                                            <!-- =================================================
     TAX
================================================= -->
                                            <tr>
                                                <td colspan="5">&nbsp;</td>

                                                <td>
                                                    <b>Tax Amount</b>
                                                </td>

                                                <td>
                                                    Rs
                                                    {{
                                                        Number(
                                                            order.tax_amount ||
                                                                0,
                                                        ).toFixed(2)
                                                    }}
                                                </td>
                                            </tr>

                                            <!-- =================================================
                                                 FINAL TOTAL
                                            ================================================= -->
                                            <tr
                                                v-if="
                                                    Number(
                                                        order.coupon_value || 0,
                                                    ) > 0
                                                "
                                            >
                                                <td colspan="5">&nbsp;</td>

                                                <td>
                                                    <b>Final Total</b>
                                                </td>

                                                <td>
                                                    <b>
                                                        Rs
                                                        {{
                                                            finalTotal.toFixed(
                                                                2,
                                                            )
                                                        }}
                                                    </b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- =================================================
                     NO ORDER
                ================================================= -->
                <div v-else class="col-md-12 text-center" style="padding: 30px">
                    <h4>Order not found.</h4>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    name: "OrderDetail",

    // =====================================================
    // DATA
    // =====================================================

    data() {
        return {
            orderDetails: [],

            loading: false,

            errorMessage: "",
        };
    },

    // =====================================================
    // COMPUTED
    // =====================================================

    computed: {
        ...mapGetters(["getUserToken", "getGuestId"]),

        // =================================================
        // MAIN ORDER
        // =================================================

        order() {
            return this.orderDetails[0] || {};
        },

        // =================================================
        // TOTAL
        // =================================================

        totalAmount() {
            return this.orderDetails.reduce((total, item) => {
                const price = Number(item.price || 0);

                const quantity = Number(item.quantity || 0);

                return total + price * quantity;
            }, 0);
        },

        // =================================================
        // FINAL TOTAL
        // =================================================

        finalTotal() {
            const coupon = Number(this.order.coupon_value || 0);
            const tax = Number(this.order.tax_amount || 0);

            const total = this.totalAmount - coupon + tax;

            return total < 0 ? 0 : total;
        },
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
            }

            return headers;
        },

        // =================================================
        // PRODUCT IMAGE
        // =================================================

        getProductImage(image) {
            if (!image) {
                return "/assets/img/default-product.jpg";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        async getOrderDetail() {
            this.loading = true;

            this.errorMessage = "";

            try {
                // =================================================
                // GET ORDER ID
                // =================================================

                const orderId = this.$route.params.id;

                // =================================================
                // TOKEN CHECK
                // =================================================

                if (!this.getUserToken) {
                    this.errorMessage = "Please login to view order details.";
                    this.$toast.error(this.errorMessage);
                    return;
                }

                // =================================================
                // API
                // =================================================

                const response = await axios.get(
                    `/api/order_detail/${orderId}`,
                    {
                        headers: this.getHeaders(),
                    },
                );

                // =================================================
                // SUCCESS
                // =================================================

                if (response.data?.status) {
                    // IMPORTANT:
                    // API response key is order_details
                    const result = response.data.order_details || [];

                    if (Array.isArray(result) && result.length > 0) {
                        this.orderDetails = result;
                    } else {
                        this.orderDetails = [];

                        this.errorMessage = "Order not found.";

                        this.$toast.error(this.errorMessage);
                    }
                } else {
                    this.orderDetails = [];

                    this.errorMessage =
                        response.data?.message ||
                        "Unable to load order details.";

                    this.$toast.error(this.errorMessage);
                }
            } catch (error) {
                this.orderDetails = [];

                const message =
                    error.response?.data?.message ||
                    "Something went wrong while loading order details.";

                this.errorMessage = message;

                this.$toast.error(message);
            } finally {
                this.loading = false;
            }
        },
    },

    // =====================================================
    // MOUNTED
    // =====================================================

    async mounted() {
        await this.getOrderDetail();
    },
};
</script>

<style scoped>
/* =====================================================
   ORDER DETAIL
===================================================== */

.order_detail {
    padding: 20px 0;
}

.order_detail h3 {
    margin-bottom: 15px;
}

/* =====================================================
   PRODUCT IMAGE
===================================================== */

.order-product-image {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

/* =====================================================
   TABLE
===================================================== */

.table th,
.table td {
    vertical-align: middle !important;
}

/* =====================================================
   COUPON
===================================================== */

.coupon_apply_txt {
    margin-left: 5px;
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

    .order-product-image {
        width: 60px;
        height: 60px;
    }
}
</style>
