<template>
    <div>
        <!-- =====================================================
             CART HEADER BANNER
        ===================================================== -->
        <section id="aa-catg-head-banner">
            <div class="aa-catg-head-banner-area">
                <div class="container">
                    <h2>Shopping Cart</h2>
                </div>
            </div>
        </section>

        <!-- =====================================================
             CART VIEW
        ===================================================== -->
        <section id="cart-view">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="cart-view-area">
                            <div class="cart-view-table">
                                <!-- LOADING -->
                                <div v-if="loading" class="cart-loading">
                                    <i class="fa fa-spinner fa-spin"></i>
                                    Loading cart...
                                </div>

                                <!-- =================================================
                                     CART HAS PRODUCTS
                                ================================================= -->
                                <div
                                    v-else-if="cartItems.length > 0"
                                    class="table-responsive"
                                >
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <!-- =================================================
                                                 CART ITEM
                                            ================================================= -->
                                            <tr
                                                v-for="item in cartItems"
                                                :key="
                                                    item.attr_id ||
                                                    item.id ||
                                                    item.cart_id
                                                "
                                                :id="
                                                    'cart_box' +
                                                    (item.attr_id ||
                                                        item.id ||
                                                        item.cart_id)
                                                "
                                            >
                                                <!-- REMOVE -->
                                                <td>
                                                    <a
                                                        class="remove"
                                                        href="javascript:void(0)"
                                                        @click.prevent="
                                                            deleteCartProduct(
                                                                item,
                                                            )
                                                        "
                                                    >
                                                        <span
                                                            class="fa fa-close"
                                                        ></span>
                                                    </a>
                                                </td>

                                                <!-- PRODUCT IMAGE -->
                                                <td>
                                                    <router-link
                                                        :to="
                                                            '/product/' +
                                                            item.slug
                                                        "
                                                    >
                                                        <img
                                                            :src="
                                                                getProductImage(
                                                                    item.attribute_image,
                                                                )
                                                            "
                                                            :alt="item.name"
                                                            @error="
                                                                productImageError
                                                            "
                                                        />
                                                    </router-link>
                                                </td>

                                                <!-- PRODUCT -->
                                                <td>
                                                    <router-link
                                                        class="aa-cart-title"
                                                        :to="
                                                            '/product/' +
                                                            item.slug
                                                        "
                                                    >
                                                        {{ item.name }}
                                                    </router-link>

                                                    <!-- SIZE -->
                                                    <br v-if="item.size" />

                                                    <span v-if="item.size">
                                                        SIZE:
                                                        {{ item.size }}
                                                    </span>

                                                    <!-- COLOR -->
                                                    <br v-if="item.color" />

                                                    <span v-if="item.color">
                                                        COLOR:
                                                        {{ item.color }}
                                                    </span>
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
                                                    <input
                                                        class="aa-cart-quantity"
                                                        type="number"
                                                        min="1"
                                                        :value="
                                                            item.qty ||
                                                            item.quantity ||
                                                            1
                                                        "
                                                        @change="
                                                            updateQty(
                                                                item,
                                                                $event.target
                                                                    .value,
                                                            )
                                                        "
                                                    />
                                                </td>

                                                <!-- TOTAL -->
                                                <td>
                                                    Rs
                                                    {{
                                                        Number(
                                                            getItemTotal(item),
                                                        ).toFixed(2)
                                                    }}
                                                </td>
                                            </tr>

                                            <!-- =================================================
                                                 CHECKOUT
                                            ================================================= -->
                                            <tr>
                                                <td
                                                    colspan="6"
                                                    class="aa-cart-view-bottom"
                                                >
                                                    <router-link
                                                        class="aa-cartbox-checkout aa-primary-btn"
                                                        to="/checkout"
                                                    >
                                                        Checkout
                                                    </router-link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- =================================================
                                     EMPTY CART
                                ================================================= -->
                                <div v-else class="cart-empty">
                                    <span class="fa fa-shopping-cart"></span>

                                    <h3>Cart empty</h3>

                                    <p>
                                        Your shopping cart is currently empty.
                                    </p>

                                    <router-link to="/" class="aa-primary-btn">
                                        Continue Shopping
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             LOADING OVERLAY
        ===================================================== -->
        <div v-if="deleting" class="cart-action-loading">
            Removing product...
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    name: "Cart",

    data() {
        return {
            // ==========================================
            // CART
            // ==========================================
            cartItems: [],

            // ==========================================
            // LOADING
            // ==========================================
            loading: false,

            deleting: false,
        };
    },

    computed: {
        ...mapGetters(["getUserToken", "getGuestId", "getFrontendUser"]),
    },

    methods: {
        // =====================================================
        // PRODUCT IMAGE
        // =====================================================
        getProductImage(image) {
            if (!image) {
                return "/assets/img/default-product.jpg";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // =====================================================
        // IMAGE ERROR
        // =====================================================
        productImageError(event) {
            event.target.src = "/assets/img/default-product.jpg";
        },

        // =====================================================
        // GET QUANTITY
        // =====================================================
        getQuantity(item) {
            return Number(item.qty || item.quantity || 1);
        },

        // =====================================================
        // ITEM TOTAL
        // =====================================================
        getItemTotal(item) {
            const price = Number(item.price || 0);

            const quantity = this.getQuantity(item);

            return price * quantity;
        },

        // =====================================================
        // HEADERS
        // =====================================================
        getCartHeaders() {
            const headers = {
                Accept: "application/json",
            };

            const userToken = this.getUserToken;
            const guestId = this.getGuestId;

            // LOGGED USER
            if (userToken) {
                headers.Authorization = `Bearer ${userToken}`;
            }

            // GUEST USER
            if (!userToken && guestId) {
                headers["X-Guest-ID"] = guestId;
            }

            return headers;
        },

        // =====================================================
        // GET CART
        // =====================================================
        async getCart() {
            this.loading = true;

            try {
                const headers = this.getCartHeaders();

                const response = await axios.get("/api/cart", {
                    headers: headers,
                });

                const data = response.data || {};

                /*
                 * Flexible response handling
                 */

                if (Array.isArray(data.list)) {
                    this.cartItems = data.list;
                } else if (Array.isArray(data.cart)) {
                    this.cartItems = data.cart;
                } else if (Array.isArray(data.result)) {
                    this.cartItems = data.result;
                } else if (Array.isArray(data.result?.list)) {
                    this.cartItems = data.result.list;
                } else if (Array.isArray(data.result?.cart)) {
                    this.cartItems = data.result.cart;
                } else {
                    this.cartItems = [];
                }
            } catch (error) {
                console.error(
                    "GET CART ERROR:",
                    error.response?.data || error.message,
                );

                this.cartItems = [];

                if (this.$toast) {
                    this.$toast.error(
                        error.response?.data?.message || "Unable to load cart.",
                    );
                }
            } finally {
                this.loading = false;
            }
        },
        // =====================================================
        // DELETE CART PRODUCT
        // =====================================================
        async deleteCartProduct(item) {
            if (!confirm("Are you sure you want to remove this product?")) {
                return;
            }

            this.deleting = true;

            try {
                // ==========================================
                // EXACT ATTRIBUTE IDS
                // ==========================================
                const data = {
                    product_id: Number(item.pid || item.product_id),

                    size_id: Number(item.size_id),

                    color_id: Number(item.color_id),

                    // IMPORTANT:
                    // 0 means REMOVE in your Laravel add_to_cart()
                    quantity: 0,
                };

                const response = await axios.post("/api/add-to-cart", data, {
                    headers: this.getCartHeaders(),
                });

                if (
                    response.data?.status === true &&
                    response.data?.message === "removed"
                ) {
                    if (this.$toast) {
                        this.$toast.success(
                            response.data?.message === "removed"
                                ? "Product removed from cart."
                                : response.data?.message,
                        );
                    }

                    await this.getCart();
                    window.dispatchEvent(new Event("cart-updated"));
                } else if (response.data?.status === true) {
                    // In case backend returns another success message
                    if (this.$toast) {
                        this.$toast.success(
                            response.data?.message ||
                                "Product removed from cart.",
                        );
                    }

                    await this.getCart();
                } else {
                    if (this.$toast) {
                        this.$toast.error(
                            response.data?.data ||
                                response.data?.message ||
                                "Unable to remove product.",
                        );
                    }
                }
            } catch (error) {
                console.error(
                    "DELETE CART ERROR:",
                    error.response?.data || error.message,
                );

                if (this.$toast) {
                    this.$toast.error(
                        error.response?.data?.data ||
                            error.response?.data?.message ||
                            "Something went wrong while removing product.",
                    );
                }
            } finally {
                this.deleting = false;
            }
        },
        // =====================================================
        // UPDATE QUANTITY
        // =====================================================
        async updateQty(item, quantity) {
            // User ne input mein jo FINAL quantity likhi hai
            const newQuantity = Number(quantity);

            // Current cart quantity
            const currentQuantity = this.getQuantity(item);

            // Invalid quantity
            if (!Number.isInteger(newQuantity) || newQuantity < 1) {
                this.$toast?.error("Quantity must be at least 1.");

                // Input ko current quantity par wapas lao
                await this.getCart();

                return;
            }

            // Agar quantity same hai to API call ki zaroorat nahi
            if (newQuantity === currentQuantity) {
                return;
            }

            try {
                // ==========================================
                // GET SIZE / COLOR ID
                // ==========================================

                const sizeId =
                    item.size_id ??
                    item.sizeId ??
                    item.product_attribute?.size_id ??
                    null;

                const colorId =
                    item.color_id ??
                    item.colorId ??
                    item.product_attribute?.color_id ??
                    null;

                const productId = item.pid ?? item.product_id ?? item.id;

                // ==========================================
                // VALIDATION
                // ==========================================

                if (!productId) {
                    this.$toast?.error("Product ID not found.");
                    return;
                }

                if (sizeId === null || sizeId === undefined) {
                    this.$toast?.error("Size ID not found.");
                    return;
                }

                if (colorId === null || colorId === undefined) {
                    this.$toast?.error("Color ID not found.");
                    return;
                }

                // ==========================================
                // IMPORTANT
                // ==========================================
                // Backend quantity ko EXISTING quantity mein
                // ADD karta hai.
                //
                // Isliye humein difference bhejna hai.
                //
                // Example:
                // Current = 12
                // New     = 11
                // Delta   = 11 - 12 = -1
                //
                // Backend:
                // 12 + (-1) = 11
                //
                // Example:
                // Current = 11
                // New     = 15
                // Delta   = 15 - 11 = 4
                //
                // Backend:
                // 11 + 4 = 15
                // ==========================================

                const quantityDifference = newQuantity - currentQuantity;

                // ==========================================
                // REQUEST DATA
                // ==========================================

                const data = {
                    product_id: Number(productId),
                    size_id: Number(sizeId),
                    color_id: Number(colorId),

                    // IMPORTANT:
                    // FINAL quantity nahi,
                    // difference/delta bhejna hai.
                    quantity: quantityDifference,
                };

                console.log("CURRENT QUANTITY:", currentQuantity);
                console.log("NEW QUANTITY:", newQuantity);
                console.log("QUANTITY DIFFERENCE:", quantityDifference);
                console.log("UPDATE CART DATA:", data);

                // ==========================================
                // API
                // ==========================================

                const response = await axios.post("/api/add-to-cart", data, {
                    headers: this.getCartHeaders(),
                });

                console.log("UPDATE CART RESPONSE:", response.data);

                // ==========================================
                // SUCCESS
                // ==========================================

                if (
                    response.data?.status === true ||
                    response.data?.success === true
                ) {
                    this.$toast?.success("Cart quantity updated.");

                    // Server se fresh cart lao
                    await this.getCart();

                    // Header/cart count waghera update
                    window.dispatchEvent(new Event("cart-updated"));
                } else {
                    this.$toast?.error(
                        response.data?.message || "Unable to update cart.",
                    );

                    await this.getCart();
                }
            } catch (error) {
                console.error(
                    "UPDATE CART ERROR:",
                    error.response?.data || error.message,
                );

                this.$toast?.error(
                    error.response?.data?.message || "Unable to update cart.",
                );

                // Server wali correct quantity wapas load karo
                await this.getCart();
            }
        },
    },

    // =====================================================
    // MOUNTED
    // =====================================================
    async mounted() {
        await this.getCart();
        console.log(this.getFrontendUser, "this.isUserToken");
    },
};
</script>

<style scoped>
/* =====================================================
   CART HEADER
===================================================== */

#aa-catg-head-banner {
    background: #f5f5f5;
    padding: 35px 0;
}

#aa-catg-head-banner h2 {
    margin: 0;
    font-size: 28px;
    padding: 13px;
}

/* =====================================================
   LOADING
===================================================== */

.cart-loading {
    text-align: center;
    padding: 60px 20px;
    font-size: 18px;
}

.cart-loading i {
    margin-right: 8px;
}

/* =====================================================
   CART EMPTY
===================================================== */

.cart-empty {
    text-align: center;
    padding: 70px 20px;
}

.cart-empty .fa-shopping-cart {
    font-size: 55px;
    margin-bottom: 20px;
}

.cart-empty h3 {
    margin-bottom: 10px;
}

.cart-empty p {
    margin-bottom: 25px;
}

/* =====================================================
   PRODUCT IMAGE
===================================================== */

.cart-view-table img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    background: #fff;
}

/* =====================================================
   REMOVE
===================================================== */

.cart-view-table .remove {
    font-size: 18px;
    color: #ff0000;
    cursor: pointer;
}

/* =====================================================
   QUANTITY
===================================================== */

.aa-cart-quantity {
    width: 70px;
    height: 35px;
    padding: 5px;
}

/* =====================================================
   CHECKOUT
===================================================== */

.aa-cartbox-checkout {
    display: inline-block;
    padding: 12px 25px;
    text-decoration: none;
}

/* =====================================================
   ACTION LOADING
===================================================== */

.cart-action-loading {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: #333;
    color: #fff;
    padding: 12px 20px;
    border-radius: 4px;
}
</style>
