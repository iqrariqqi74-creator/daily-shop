<template>
    <section class="content">
        <div class="container-fluid py-4">
            <!-- PAGE HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">
                        Order Details
                    </h4>

                    <small class="text-muted" v-if="order">
                        Order #{{ order.id }}
                    </small>
                </div>

                <button class="btn btn-secondary" @click="$router.back()">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back
                </button>
            </div>

            <!-- LOADING -->
            <div v-if="loading" class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only"> Loading... </span>
                    </div>

                    <p class="text-muted mt-3 mb-0">Loading order details...</p>
                </div>
            </div>

            <!-- DATA -->
            <div v-else-if="order">
                <!-- ==========================================
                     ORDER + CUSTOMER
                =========================================== -->

                <div class="row">
                    <!-- ORDER INFORMATION -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white">
                                <h6 class="font-weight-bold text-primary mb-0">
                                    <i class="fas fa-receipt mr-2"></i>
                                    Order Information
                                </h6>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- ORDER ID -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Order ID </small>

                                            <h6>#{{ order.id }}</h6>
                                        </div>
                                    </div>

                                    <!-- CUSTOMER NAME -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Customer Name </small>

                                            <h6>
                                                {{ order.name || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Email </small>

                                            <h6>
                                                {{ order.email || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- MOBILE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Mobile </small>

                                            <h6>
                                                {{ order.mobile || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- PAYMENT TYPE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Payment Type </small>

                                            <h6>
                                                {{
                                                    order.payment_type || "N/A"
                                                }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- PAYMENT STATUS -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Payment Status </small>

                                            <div>
                                                <span
                                                    class="badge"
                                                    :class="
                                                        getPaymentStatusBadge(
                                                            order.payment_status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        getPaymentStatusText(
                                                            order.payment_status,
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ORDER STATUS -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Order Status </small>

                                            <div>
                                                <span
                                                    class="badge"
                                                    :class="
                                                        getOrderStatusBadge(
                                                            order.order_status,
                                                        )
                                                    "
                                                >
                                                    {{
                                                        getOrderStatusText(
                                                            order.order_status,
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SUB TOTAL -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Sub Total </small>

                                            <h5 class="text-dark mb-0">
                                                {{ subTotal.toFixed(2) }}
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- COUPON CODE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Coupon Code </small>

                                            <h6>
                                                {{ order.coupon_code || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- DISCOUNT -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Discount </small>

                                            <h6
                                                class="font-weight-bold text-success"
                                            >
                                                {{
                                                    Number(
                                                        order.coupon_value || 0,
                                                    ).toFixed(2)
                                                }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- TAX -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Tax </small>

                                            <h6 v-if="taxInfo">
                                                {{ taxInfo.name || "Tax" }}

                                                <span class="tax-value">
                                                    {{ taxInfo.value || 0 }}
                                                </span>
                                            </h6>

                                            <h6 v-else>N/A</h6>
                                        </div>
                                    </div>

                                    <!-- TAX TOTAL -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Tax Amount </small>

                                            <h6
                                                class="font-weight-bold text-warning"
                                            >
                                                {{ taxAmount.toFixed(2) }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- TOTAL AMOUNT -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Total Amount </small>

                                            <h5 class="text-success mb-0">
                                                {{ order.total_amount }}
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- DATE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Order Date </small>

                                            <h6>
                                                {{ formatDate(order.added_on) }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- ADDRESS -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Address </small>

                                            <h6>
                                                {{ order.address || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- CITY -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> City </small>

                                            <h6>
                                                {{ order.city || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- STATE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> State </small>

                                            <h6>
                                                {{ order.state || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- PIN CODE -->
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-box">
                                            <small> Pin Code </small>

                                            <h6>
                                                {{ order.pin_code || "N/A" }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CUSTOMER CARD -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white">
                                <h6 class="font-weight-bold text-primary mb-0">
                                    <i class="fas fa-user mr-2"></i>
                                    Customer
                                </h6>
                            </div>

                            <div class="card-body text-center">
                                <div class="customer-icon">
                                    <i class="fas fa-user"></i>
                                </div>

                                <h5 class="font-weight-bold">
                                    {{ order.name || "N/A" }}
                                </h5>

                                <p class="text-muted mb-2">
                                    {{ order.email || "N/A" }}
                                </p>

                                <p class="text-muted">
                                    <i class="fas fa-phone mr-1"></i>
                                    {{ order.mobile || "N/A" }}
                                </p>

                                <hr />

                                <div class="text-left">
                                    <p class="mb-2">
                                        <strong>Address:</strong>
                                    </p>

                                    <p class="text-muted">
                                        {{ order.address || "N/A" }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==========================================
                     PRODUCTS
                =========================================== -->

                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white d-flex justify-content-between align-items-center"
                    >
                        <h6 class="font-weight-bold text-primary mb-0">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Order Products
                        </h6>

                        <span class="badge badge-primary">
                            {{ orderDetails.length }} Product(s)
                        </span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="60">#</th>

                                        <th>Product</th>

                                        <th>SKU</th>

                                        <th>Size</th>

                                        <th>Color</th>

                                        <th>Price</th>

                                        <th>Tax</th>

                                        <th>Quantity</th>

                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="(item, index) in orderDetails"
                                        :key="item.id"
                                    >
                                        <!-- NUMBER -->
                                        <td>
                                            {{ index + 1 }}
                                        </td>

                                        <!-- PRODUCT -->
                                        <td>
                                            <div
                                                class="d-flex align-items-center"
                                            >
                                                <!-- IMAGE -->
                                                <div class="product-image mr-3">
                                                    <img
                                                        v-if="
                                                            item
                                                                .product_attribute
                                                                ?.attribute_image
                                                        "
                                                        :src="
                                                            getImage(
                                                                item
                                                                    .product_attribute
                                                                    .attribute_image,
                                                            )
                                                        "
                                                        alt="Product"
                                                    />

                                                    <i
                                                        v-else
                                                        class="fas fa-image text-muted"
                                                    ></i>
                                                </div>

                                                <!-- NAME -->
                                                <div>
                                                    <h6 class="mb-1">
                                                        {{
                                                            item
                                                                .product_attribute
                                                                ?.product
                                                                ?.name || "N/A"
                                                        }}
                                                    </h6>

                                                    <small class="text-muted">
                                                        Product ID:
                                                        {{ item.product_id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- SKU -->
                                        <td>
                                            {{
                                                item.product_attribute?.sku ||
                                                "N/A"
                                            }}
                                        </td>

                                        <!-- SIZE -->
                                        <td>
                                            <span class="badge badge-light">
                                                {{
                                                    item.product_attribute?.size
                                                        ?.name || "N/A"
                                                }}
                                            </span>
                                        </td>

                                        <!-- COLOR -->
                                        <td>
                                            <span
                                                class="product-image mr-3"
                                                v-if="
                                                    item.product_attribute
                                                        ?.color
                                                "
                                            >
                                                <img
                                                    v-if="
                                                        item.product_attribute
                                                            ?.color?.image
                                                    "
                                                    :src="
                                                        getImage(
                                                            item
                                                                .product_attribute
                                                                .color.image,
                                                        )
                                                    "
                                                    :alt="
                                                        item.product_attribute
                                                            .color.name
                                                    "
                                                    class="color-image"
                                                />
                                            </span>
                                        </td>

                                        <!-- PRICE -->
                                        <td>
                                            {{ item.price }}
                                        </td>

                                        <!-- TAX -->
                                        <td>
                                            <div
                                                v-if="
                                                    item.product_attribute
                                                        ?.product?.tax
                                                "
                                                class="tax-box"
                                            >
                                                <span class="tax-name">
                                                    {{
                                                        item.product_attribute
                                                            .product.tax.name ||
                                                        "Tax"
                                                    }}
                                                </span>

                                                <span class="tax-value-small">
                                                    {{
                                                        item.product_attribute
                                                            .product.tax
                                                            .value || 0
                                                    }}
                                                </span>
                                            </div>

                                            <span v-else> N/A </span>
                                        </td>

                                        <!-- QUANTITY -->
                                        <td>
                                            <span class="qty-badge">
                                                {{ item.quantity }}
                                            </span>
                                        </td>

                                        <!-- TOTAL -->
                                        <td class="text-right font-weight-bold">
                                            {{
                                                (
                                                    Number(item.price || 0) *
                                                    Number(item.quantity || 0)
                                                ).toFixed(2)
                                            }}
                                        </td>
                                    </tr>
                                </tbody>

                                <!-- GRAND TOTAL -->
                                <tfoot>
                                    <!-- SUB TOTAL -->
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="text-right font-weight-bold"
                                        >
                                            Sub Total
                                        </td>

                                        <td class="text-right font-weight-bold">
                                            {{ subTotal.toFixed(2) }}
                                        </td>
                                    </tr>

                                    <!-- DISCOUNT -->
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="text-right font-weight-bold"
                                        >
                                            Discount
                                        </td>

                                        <td
                                            class="text-right font-weight-bold text-danger"
                                        >
                                            -
                                            {{
                                                Number(
                                                    order.coupon_value || 0,
                                                ).toFixed(2)
                                            }}
                                        </td>
                                    </tr>

                                    <!-- TAX -->
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="text-right font-weight-bold"
                                        >
                                            Tax

                                            <span
                                                v-if="taxInfo"
                                                class="text-muted ml-1"
                                            >
                                                ({{ taxInfo.name }})
                                            </span>
                                        </td>

                                        <td
                                            class="text-right font-weight-bold text-warning"
                                        >
                                            + {{ taxAmount.toFixed(2) }}
                                        </td>
                                    </tr>

                                    <!-- GRAND TOTAL -->
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="text-right font-weight-bold"
                                        >
                                            Grand Total
                                        </td>

                                        <td
                                            class="text-right font-weight-bold text-success"
                                        >
                                            {{
                                                Number(
                                                    order.total_amount ||
                                                        grandTotal,
                                                ).toFixed(2)
                                            }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NO DATA -->
            <div v-else class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>

                    <h5>Order Not Found</h5>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { format } from "date-fns";

export default {
    data() {
        return {
            orderDetails: [],
            loading: false,
        };
    },

    computed: {
        // ==========================================
        // MAIN ORDER
        // ==========================================

        order() {
            if (!this.orderDetails.length) {
                return null;
            }
            return this.orderDetails[0].order || null;
        },

        // ==========================================
        // SUB TOTAL
        // ==========================================

        subTotal() {
            return this.orderDetails.reduce((total, item) => {
                return (
                    total + Number(item.price || 0) * Number(item.quantity || 0)
                );
            }, 0);
        },

        // ==========================================
        // TAX INFO
        // ==========================================

        taxInfo() {
            for (const item of this.orderDetails) {
                const tax = item.product_attribute?.product?.tax;

                if (tax) {
                    return tax;
                }
            }

            return null;
        },

        // ==========================================
        // TAX AMOUNT
        // ==========================================

        taxAmount() {
            // Agar backend mein tax_amount save hai
            if (
                this.order &&
                this.order.tax_amount !== null &&
                this.order.tax_amount !== undefined
            ) {
                return Number(this.order.tax_amount || 0);
            }

            return 0;
        },

        // ==========================================
        // GRAND TOTAL
        // ==========================================

        grandTotal() {
            return this.orderDetails.reduce((total, item) => {
                return (
                    total + Number(item.price || 0) * Number(item.quantity || 0)
                );
            }, 0);
        },
    },

    methods: {
        // ==========================================
        // GET ORDER DETAILS
        // ==========================================

        async getOrderDetails() {
            this.loading = true;
            try {
                const id = this.$route.params.id;

                const response = await axios.get(`/order/details/${id}`);

                if (response.data.status === "success") {
                    this.orderDetails = response.data.orders_details || [];
                } else {
                    this.$toast.error(
                        response.data.message ||
                            "Failed to fetch order details.",
                    );
                }
            } catch (error) {
                this.$toast.error(
                    error.response?.data?.message ||
                        "Unable to fetch order details.",
                );
            } finally {
                this.loading = false;
            }
        },

        // ==========================================
        // IMAGE
        // ==========================================

        getImage(path) {
            if (!path) {
                return "";
            }

            if (path.startsWith("http://") || path.startsWith("https://")) {
                return path;
            }

            return `${window.location.origin}/${path}`;
        },

        // ==========================================
        // DATE
        // ==========================================

        formatDate(date) {
            if (!date) {
                return "N/A";
            }

            try {
                return format(new Date(date), "dd-MM-yyyy hh:mm a");
            } catch (error) {
                return date;
            }
        },

        // ==========================================
        // ORDER STATUS TEXT
        // ==========================================

        getOrderStatusText(status) {
            switch (Number(status)) {
                case 1:
                    return "Placed";

                case 2:
                    return "On The Way";

                case 3:
                    return "Delivered";

                case 4:
                    return "Cancelled";

                default:
                    return "Unknown";
            }
        },

        // ==========================================
        // ORDER STATUS BADGE
        // ==========================================

        getOrderStatusBadge(status) {
            switch (Number(status)) {
                case 1:
                    return "badge-warning";

                case 2:
                    return "badge-primary";

                case 3:
                    return "badge-success";

                case 4:
                    return "badge-danger";

                default:
                    return "badge-secondary";
            }
        },

        // ==========================================
        // PAYMENT STATUS TEXT
        // ==========================================

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

        // ==========================================
        // PAYMENT STATUS BADGE
        // ==========================================

        getPaymentStatusBadge(status) {
            switch (Number(status)) {
                case 1:
                    return "badge-warning";

                case 2:
                    return "badge-success";

                case 3:
                    return "badge-danger";

                case 4:
                    return "badge-secondary";

                default:
                    return "badge-dark";
            }
        },
    },

    mounted() {
        this.getOrderDetails();
    },
};
</script>

<style scoped>
.detail-box {
    background: #f8f9fa;
    border-radius: 7px;
    padding: 14px 16px;
    height: 100%;
    border: 1px solid #eeeeee;
}

.detail-box small {
    display: block;
    color: #858796;
    font-size: 12px;
    margin-bottom: 5px;
}

.detail-box h6 {
    margin: 0;
    font-weight: 600;
    color: #343a40;
    word-break: break-word;
}

/* TAX */

.tax-value {
    display: inline-block;
    margin-left: 7px;
    padding: 3px 8px;
    border-radius: 12px;
    background: #fff3cd;
    color: #856404;
    font-size: 12px;
    font-weight: 600;
}

.tax-box {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.tax-name {
    font-weight: 600;
    color: #343a40;
}

.tax-value-small {
    display: inline-block;
    width: fit-content;
    padding: 2px 7px;
    border-radius: 10px;
    background: #fff3cd;
    color: #856404;
    font-size: 11px;
    font-weight: 600;
}

/* CUSTOMER */

.customer-icon {
    width: 80px;
    height: 80px;
    margin: 5px auto 18px;
    border-radius: 50%;
    background: #e9f2ff;
    color: #007bff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

/* PRODUCT IMAGE */

.product-image {
    width: 65px;
    height: 65px;
    border-radius: 7px;
    overflow: hidden;
    background: #f5f5f5;
    border: 1px solid #eeeeee;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* QUANTITY */

.qty-badge {
    display: inline-block;
    min-width: 32px;
    padding: 5px 9px;
    text-align: center;
    background: #f1f3f5;
    border-radius: 5px;
    font-weight: 600;
}

/* COLOR */

.color-name {
    display: flex;
    align-items: center;
    gap: 6px;
}

.color-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    background: #999;
}

/* TABLE */

.table th {
    white-space: nowrap;
    font-size: 13px;
}

.table td {
    vertical-align: middle;
}

/* CARD */

.card {
    border-radius: 8px;
}
</style>
