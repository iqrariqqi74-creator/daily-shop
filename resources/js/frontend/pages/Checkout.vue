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
         CHECKOUT
    ===================================================== -->
    <section id="checkout">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="checkout-area">
                        <form id="frmPlaceOrder" @submit.prevent="placeOrder">
                            <div class="row">
                                <!-- =================================================
                                     LEFT SIDE
                                ================================================= -->
                                <div class="col-md-8">
                                    <div class="checkout-left">
                                        <div class="panel-group" id="accordion">
                                            <!-- =================================================
                                                 GUEST LOGIN
                                            ================================================= -->
                                            <div v-if="!getUserToken">
                                                <a
                                                    href="#"
                                                    data-toggle="modal"
                                                    data-target="#login-modal"
                                                >
                                                    Login
                                                </a>

                                                <br />
                                                <br />

                                                OR

                                                <br />
                                                <br />
                                            </div>

                                            <!-- =================================================
                                                 SHIPPING ADDRESS
                                            ================================================= -->
                                            <div
                                                class="panel panel-default aa-checkout-billaddress"
                                            >
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a
                                                            data-toggle="collapse"
                                                            data-parent="#accordion"
                                                        >
                                                            User Details Address
                                                        </a>
                                                    </h4>
                                                </div>

                                                <div
                                                    id="collapseFour"
                                                    class="panel-collapse collapse in"
                                                >
                                                    <div class="panel-body">
                                                        <!-- =================================================
                                                             NAME / EMAIL / PHONE
                                                        ================================================= -->
                                                        <div class="row">
                                                            <!-- NAME -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        placeholder="Name*"
                                                                        v-model="
                                                                            checkout.name
                                                                        "
                                                                        name="name"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>

                                                            <!-- EMAIL -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="email"
                                                                        placeholder="Email Address*"
                                                                        v-model="
                                                                            checkout.email
                                                                        "
                                                                        name="email"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>

                                                            <!-- PHONE -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="tel"
                                                                        placeholder="Phone*"
                                                                        v-model="
                                                                            checkout.mobile
                                                                        "
                                                                        name="mobile"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- =================================================
                                                             ADDRESS
                                                        ================================================= -->
                                                        <div class="row">
                                                            <div
                                                                class="col-md-12"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <textarea
                                                                        cols="8"
                                                                        rows="3"
                                                                        name="address"
                                                                        required
                                                                        placeholder="Enter Address*"
                                                                        v-model="
                                                                            checkout.address
                                                                        "
                                                                    ></textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- =================================================
                                                             CITY / STATE / ZIP
                                                        ================================================= -->
                                                        <div class="row">
                                                            <!-- CITY -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        placeholder="City / Town*"
                                                                        v-model="
                                                                            checkout.city
                                                                        "
                                                                        name="city"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>

                                                            <!-- STATE -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        placeholder="State*"
                                                                        v-model="
                                                                            checkout.state
                                                                        "
                                                                        name="state"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>

                                                            <!-- ZIP -->
                                                            <div
                                                                class="col-md-4"
                                                            >
                                                                <div
                                                                    class="aa-checkout-single-bill"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        placeholder="Postcode / ZIP*"
                                                                        v-model="
                                                                            checkout.zip
                                                                        "
                                                                        name="zip"
                                                                        required
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- =================================================
                                     RIGHT SIDE
                                ================================================= -->
                                <div class="col-md-4">
                                    <div class="checkout-right">
                                        <!-- =================================================
                                             ORDER SUMMARY
                                        ================================================= -->
                                        <h4>Order Summary</h4>

                                        <div class="aa-order-summary-area">
                                            <table
                                                class="table table-responsive"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <!-- =================================================
                                                         CART PRODUCTS
                                                    ================================================= -->
                                                    <tr
                                                        v-for="item in cart"
                                                        :key="
                                                            item.attr_id ||
                                                            item.id ||
                                                            item.cart_id
                                                        "
                                                    >
                                                        <td>
                                                            <div
                                                                class="checkout-product"
                                                            >
                                                                <!-- PRODUCT IMAGE -->
                                                                <img
                                                                    :src="
                                                                        getProductImage(
                                                                            item.attribute_image ||
                                                                                item.image,
                                                                        )
                                                                    "
                                                                    :alt="
                                                                        item.name
                                                                    "
                                                                    class="checkout-product-image"
                                                                    @error="
                                                                        productImageError
                                                                    "
                                                                />

                                                                <!-- PRODUCT INFO -->
                                                                <div
                                                                    class="checkout-product-info"
                                                                >
                                                                    <div
                                                                        class="checkout-product-name"
                                                                    >
                                                                        {{
                                                                            item.name
                                                                        }}
                                                                    </div>

                                                                    <strong>
                                                                        x
                                                                        {{
                                                                            item.qty
                                                                        }}
                                                                    </strong>

                                                                    <!-- COLOR -->
                                                                    <div
                                                                        v-if="
                                                                            item.color
                                                                        "
                                                                        class="checkout-product-option"
                                                                    >
                                                                        COLOR:
                                                                        {{
                                                                            item.color
                                                                        }}
                                                                    </div>

                                                                    <!-- SIZE -->
                                                                    <div
                                                                        v-if="
                                                                            item.size
                                                                        "
                                                                        class="checkout-product-option"
                                                                    >
                                                                        SIZE:
                                                                        {{
                                                                            item.size
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            Rs
                                                            {{
                                                                (
                                                                    Number(
                                                                        item.price ||
                                                                            0,
                                                                    ) *
                                                                    Number(
                                                                        item.qty ||
                                                                            0,
                                                                    )
                                                                ).toFixed(2)
                                                            }}
                                                        </td>
                                                    </tr>

                                                    <!-- EMPTY CART -->
                                                    <tr
                                                        v-if="cart.length === 0"
                                                    >
                                                        <td colspan="2">
                                                            Cart empty
                                                        </td>
                                                    </tr>
                                                </tbody>

                                                <tfoot>
                                                    <!-- =================================================
                                                         SUBTOTAL
                                                    ================================================= -->
                                                    <tr>
                                                        <th>Subtotal</th>

                                                        <td>
                                                            Rs
                                                            {{
                                                                subtotalPrice.toFixed(
                                                                    2,
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>

                                                    <!-- =================================================
                                                         COUPON APPLIED
                                                    ================================================= -->
                                                    <tr
                                                        v-if="couponApplied"
                                                        class="show_coupon_box"
                                                    >
                                                        <th>
                                                            Coupon Code

                                                            <a
                                                                href="javascript:void(0)"
                                                                @click="
                                                                    removeCoupon
                                                                "
                                                                class="remove_coupon_code_link"
                                                            >
                                                                Remove
                                                            </a>
                                                        </th>

                                                        <td>
                                                            {{ couponCode }}
                                                        </td>
                                                    </tr>

                                                    <!-- =================================================
                                                         DISCOUNT
                                                    ================================================= -->
                                                    <tr v-if="couponApplied">
                                                        <th>Discount</th>

                                                        <td>
                                                            - Rs
                                                            {{
                                                                Number(
                                                                    couponDiscount ||
                                                                        0,
                                                                ).toFixed(2)
                                                            }}
                                                        </td>
                                                    </tr>

                                                    <!-- =================================================
                                                         TOTAL
                                                    ================================================= -->
                                                    <tr>
                                                        <th>Total</th>

                                                        <td id="total_price">
                                                            INR
                                                            {{
                                                                totalPrice.toFixed(
                                                                    2,
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <!-- =================================================
                                             COUPON CODE
                                        ================================================= -->
                                        <h4>Coupon Code</h4>

                                        <div
                                            class="aa-payment-method coupon_code"
                                        >
                                            <input
                                                type="text"
                                                placeholder="Coupon Code"
                                                class="aa-coupon-code apply_coupon_code_box"
                                                name="coupon_code"
                                                id="coupon_code"
                                                v-model="couponCode"
                                                :disabled="couponApplied"
                                            />

                                            <!-- APPLY -->
                                            <input
                                                v-if="!couponApplied"
                                                type="button"
                                                value="Apply Coupon"
                                                class="aa-browse-btn apply_coupon_code_box"
                                                @click="applyCouponCode"
                                            />

                                            <!-- REMOVE -->
                                            <input
                                                v-else
                                                type="button"
                                                value="Remove Coupon"
                                                class="aa-browse-btn apply_coupon_code_box"
                                                @click="removeCoupon"
                                            />

                                            <div id="coupon_code_msg">
                                                {{ couponMessage }}
                                            </div>
                                        </div>

                                        <br />

                                        <!-- =================================================
                                             PAYMENT METHOD
                                        ================================================= -->
                                        <h4>Payment Method</h4>

                                        <div class="aa-payment-method">
                                            <!-- ONLY COD -->
                                            <label for="cod">
                                                <input
                                                    type="radio"
                                                    id="cod"
                                                    name="payment_type"
                                                    value="COD"
                                                    v-model="payment_type"
                                                    checked
                                                />

                                                Cash on Delivery
                                            </label>

                                            <br />

                                            <!-- PLACE ORDER -->
                                            <!-- PLACE ORDER -->
                                            <button
                                                type="submit"
                                                class="aa-browse-btn"
                                                id="btnPlaceOrder"
                                                :disabled="placingOrder"
                                            >
                                                <span v-if="placingOrder">
                                                    <i
                                                        class="fa fa-spinner fa-spin"
                                                    ></i>
                                                    Processing...
                                                </span>

                                                <span v-else>
                                                    Place Order
                                                </span>
                                            </button>
                                            <!-- <input
                                                type="submit"
                                                value="Place Order"
                                                class="aa-browse-btn"
                                                id="btnPlaceOrder"
                                                :disabled="placingOrder"
                                            /> -->
                                        </div>

                                        <!-- =================================================
                                             ORDER MESSAGE
                                        ================================================= -->
                                        <div id="order_place_msg">
                                            {{ orderMessage }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    name: "Checkout",

    // =====================================================
    // DATA
    // =====================================================

    data() {
        return {
            // =================================================
            // CART
            // =================================================

            cart: [],

            // =================================================
            // CUSTOMER
            // =================================================

            checkout: {
                name: "",
                email: "",
                mobile: "",
                address: "",
                city: "",
                state: "",
                zip: "",
            },

            // =================================================
            // COUPON
            // =================================================

            couponCode: "",

            couponApplied: false,

            couponMessage: "",

            couponDiscount: 0,

            // =================================================
            // PAYMENT
            // =================================================

            payment_type: "COD",

            // =================================================
            // ORDER
            // =================================================

            placingOrder: false,

            orderMessage: "",
        };
    },

    // =====================================================
    // COMPUTED
    // =====================================================

    computed: {
        ...mapGetters(["getUserToken", "getGuestId", "getFrontendUser"]),

        // =================================================
        // SUBTOTAL
        // =================================================

        subtotalPrice() {
            let total = 0;

            this.cart.forEach((item) => {
                total += Number(item.price || 0) * Number(item.qty || 0);
            });

            return total;
        },

        // =================================================
        // TOTAL AFTER COUPON
        // =================================================

        totalPrice() {
            let total = this.subtotalPrice;

            total = total - Number(this.couponDiscount || 0);

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

            // =================================================
            // LOGIN USER
            // =================================================

            if (this.getUserToken) {
                headers.Authorization = `Bearer ${this.getUserToken}`;
            }

            // =================================================
            // GUEST USER
            // =================================================
            else if (this.getGuestId) {
                headers["X-Guest-ID"] = this.getGuestId;
            }

            return headers;
        },

        // =================================================
        // GET CART
        // =================================================

        async getCart() {
            try {
                const response = await axios.get("/api/cart", {
                    headers: this.getHeaders(),
                });

                console.log("CHECKOUT CART RESPONSE:", response.data);

                if (response.data?.status) {
                    const result = response.data.result || {};

                    this.cart = Array.isArray(result.list) ? result.list : [];
                }
            } catch (error) {
                console.error(
                    "CHECKOUT CART ERROR:",
                    error.response?.data || error,
                );

                this.cart = [];
            }
        },

        // =================================================
        // PRODUCT IMAGE
        // ATTRIBUTE IMAGE FIRST
        // OTHERWISE PRODUCT IMAGE
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

        // =================================================
        // IMAGE ERROR
        // =================================================

        productImageError(event) {
            event.target.src = "/assets/img/default-product.jpg";
        },

        // =================================================
        // CHECKOUT IMAGE ERROR
        // =================================================

        checkoutImageError(event) {
            event.target.src = "/assets/img/default-product.jpg";
        },

        // =================================================
        // LOGIN
        // =================================================
        goToLogin() {
            this.$router.push("/login");
        },

        // =================================================
        // APPLY COUPON
        // =================================================

        async applyCouponCode() {
            if (!this.couponCode.trim()) {
                this.couponMessage = "Please enter coupon code.";

                this.couponApplied = false;

                this.couponDiscount = 0;

                return;
            }

            try {
                this.couponMessage = "Applying coupon...";

                const response = await axios.post(
                    "/api/apply_coupon_code",
                    {
                        coupon_code: this.couponCode.trim(),
                    },
                    {
                        headers: this.getHeaders(),
                    },
                );

                // =================================================
                // SUCCESS
                // =================================================

                if (
                    response.data?.status === "success" ||
                    response.data?.status === true
                ) {
                    this.couponApplied = true;

                    this.couponDiscount = Number(
                        response.data?.coupon_code_value || 0,
                    );

                    this.couponMessage =
                        response.data?.message ||
                        "Coupon applied successfully.";

                    if (this.$toast) {
                        this.$toast.success(
                            response.data?.message ||
                                "Coupon applied successfully.",
                            {
                                position: "top-right",
                                autoClose: 3000,
                            },
                        );
                    }

                    return;
                }

                // =================================================
                // INVALID COUPON
                // =================================================

                this.couponApplied = false;

                this.couponDiscount = 0;

                this.couponMessage =
                    response.data?.message || "Invalid coupon code.";
            } catch (error) {
                this.$toast.error(
                    error.response?.data?.message || "Unable to apply coupon.",
                    {
                        position: "top-right",
                        autoClose: 3000,
                    },
                );
                this.couponApplied = false;
                this.couponDiscount = 0;
                this.couponMessage =
                    error.response?.data?.message || "Unable to apply coupon.";
            }
        },

        // =================================================
        // REMOVE COUPON
        // =================================================

        async removeCoupon() {
            try {
                const response = await axios.post(
                    "/api/remove_coupon_code",
                    {
                        coupon_code: this.couponCode,
                    },
                    {
                        headers: this.getHeaders(),
                    },
                );

                if (
                    response.data?.status === "success" ||
                    response.data?.status === true
                ) {
                    this.couponCode = "";

                    this.couponApplied = false;

                    this.couponDiscount = 0;

                    this.couponMessage =
                        response.data?.message || "Coupon code removed.";

                    if (this.$toast) {
                        this.$toast.success("Coupon code removed.", {
                            position: "top-right",
                            autoClose: 3000,
                        });
                    }
                }
            } catch (error) {
                this.$toast.error(
                    error.response?.data?.message || "Unable to remove coupon.",
                    {
                        position: "top-right",
                        autoClose: 3000,
                    },
                );

                this.couponCode = "";
                this.couponApplied = false;

                this.couponDiscount = 0;

                this.couponMessage = "Coupon code removed.";
            }
        },

        // =================================================
        // PLACE ORDER
        // =================================================

        async placeOrder() {
            this.orderMessage = "";

            // =================================================
            // CART EMPTY
            // =================================================

            if (this.cart.length === 0) {
                this.orderMessage = "Cart is empty.";

                return;
            }

            // =================================================
            // START LOADING
            // =================================================

            this.placingOrder = true;

            try {
                // =================================================
                // ORDER DATA
                // =================================================

                const data = {
                    name: this.checkout.name,

                    email: this.checkout.email,

                    mobile: this.checkout.mobile,

                    address: this.checkout.address,

                    city: this.checkout.city,

                    state: this.checkout.state,

                    zip: this.checkout.zip,

                    // Coupon only if applied
                    coupon_code: this.couponApplied ? this.couponCode : "",

                    // ONLY COD
                    payment_type: "COD",
                };

                // =================================================
                // API
                // =================================================

                const response = await axios.post("/api/place_order", data, {
                    headers: this.getHeaders(),
                });

                console.log("PLACE ORDER RESPONSE:", response.data);

                // =================================================
                // SUCCESS
                // =================================================

                if (
                    response.data?.status === true ||
                    response.data?.status === "success" ||
                    response.data?.success === true
                ) {
                    this.orderMessage =
                        response.data.message || "Order placed successfully.";

                    if (this.$toast) {
                        this.$toast.success("Order placed successfully.", {
                            position: "top-right",
                            autoClose: 3000,
                        });
                    }

                    // =================================================
                    // CART UPDATED
                    // =================================================

                    window.dispatchEvent(new Event("cart-updated"));

                    // =================================================
                    // COD
                    // =================================================

                    setTimeout(() => {
                        this.$router.push("/");
                    }, 1500);
                } else {
                    this.orderMessage =
                        response.data?.message || "Unable to place order.";
                }
            } catch (error) {
                this.$toast.error(
                    error.response?.data?.message || "Something went wrong.",
                    {
                        position: "top-right",
                        autoClose: 3000,
                    },
                );

                this.orderMessage =
                    error.response?.data?.message || "Something went wrong.";
            } finally {
                this.placingOrder = false;
            }
        },

        // =================================================
        // FILL LOGGED USER DATA
        // =================================================

        fillLoggedUserData() {
            // =================================================
            // USER NOT LOGGED IN
            // =================================================

            if (!this.getUserToken) {
                return;
            }

            const user = this.getFrontendUser;

            console.log("FRONTEND USER FROM VUEX:", user);

            if (!user) {
                console.log("FRONTEND USER NOT FOUND IN VUEX");

                return;
            }

            // =================================================
            // NAME
            // =================================================

            this.checkout.name =
                user.name || user.full_name || user.first_name || "";

            // =================================================
            // EMAIL
            // =================================================

            this.checkout.email = user.email || "";

            // =================================================
            // MOBILE
            // =================================================

            this.checkout.mobile =
                user.mobile || user.phone || user.phone_number || "";

            // =================================================
            // ADDRESS
            // =================================================

            this.checkout.address = user.address || user.street_address || "";

            // =================================================
            // CITY
            // =================================================

            this.checkout.city = user.city || "";

            // =================================================
            // STATE
            // =================================================

            this.checkout.state = user.state || "";

            // =================================================
            // ZIP
            // =================================================

            this.checkout.zip =
                user.zip ||
                user.zip_code ||
                user.postcode ||
                user.pincode ||
                "";
        },
    },

    // =====================================================
    // MOUNTED
    // =====================================================

    async mounted() {
        await this.getCart();

        this.fillLoggedUserData();
    },
};
</script>

<style scoped>
/* =====================================================
   CHECKOUT PRODUCT
===================================================== */

.checkout-product {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
}

/* =====================================================
   PRODUCT IMAGE
===================================================== */

.checkout-product-image {
    width: 65px !important;
    height: 65px !important;

    min-width: 65px !important;
    max-width: 65px !important;

    min-height: 65px !important;
    max-height: 65px !important;

    object-fit: contain !important;

    display: block !important;

    background: #fff;

    border: 1px solid #eee;

    border-radius: 4px;

    padding: 3px;

    box-sizing: border-box;
}

/* =====================================================
   PRODUCT INFORMATION
===================================================== */

.checkout-product-info {
    flex: 1;

    min-width: 0;

    line-height: 1.5;
}

.checkout-product-name {
    font-weight: 600;

    font-size: 14px;

    margin-bottom: 3px;

    white-space: normal;

    word-break: break-word;
}

.checkout-product-option {
    font-size: 12px;

    color: #777;

    margin-top: 2px;
}

/* =====================================================
   ORDER SUMMARY TABLE
===================================================== */

.aa-order-summary-area table {
    width: 100%;
}

.aa-order-summary-area td {
    vertical-align: middle !important;
}

/* Product column */

.aa-order-summary-area td:first-child {
    width: 75%;
}

/* Price column */

.aa-order-summary-area td:last-child {
    width: 25%;

    white-space: nowrap;

    text-align: right;
}

/* =====================================================
   COUPON MESSAGE
===================================================== */

#coupon_code_msg {
    margin-top: 10px;

    font-size: 13px;
}

/* =====================================================
   REMOVE COUPON
===================================================== */

.remove_coupon_code_link {
    margin-left: 10px;

    color: #ff0000;

    cursor: pointer;

    font-size: 12px;
}

/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767px) {
    .checkout-product-image {
        width: 55px !important;
        height: 55px !important;

        min-width: 55px !important;
        max-width: 55px !important;

        min-height: 55px !important;
        max-height: 55px !important;
    }

    .checkout-product-name {
        font-size: 13px;
    }

    .checkout-product {
        gap: 8px;
    }
}
</style>
