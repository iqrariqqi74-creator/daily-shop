```vue
<template>
    <header id="aa-header">
        <!-- =========================================================
             HEADER TOP
        ========================================================== -->
        <div class="aa-header-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-top-area">
                            <!-- =================================================
                                 LEFT
                            ================================================== -->
                            <div class="aa-header-top-left">
                                <div class="cellphone hidden-xs">
                                    <p>
                                        <span class="fa fa-phone"></span>
                                        00-62-658-658
                                    </p>
                                </div>
                            </div>

                            <!-- =================================================
                                 RIGHT
                            ================================================== -->
                            <div class="aa-header-top-right">
                                <ul class="aa-head-top-nav-right">
                                    <!-- CART -->
                                    <li>
                                        <router-link to="/cart">
                                            My Cart
                                        </router-link>
                                    </li>

                                    <!-- =================================================
                                         LOGGED IN
                                    ================================================== -->
                                    <template v-if="isLoggedIn">
                                        <li>
                                            <router-link to="/orders">
                                                My Order
                                            </router-link>
                                        </li>

                                        <li>
                                            <a href="#" @click.prevent="logout">
                                                Logout
                                            </a>
                                        </li>
                                    </template>

                                    <!-- =================================================
                                         NOT LOGGED IN
                                    ================================================== -->
                                    <template v-else>
                                        <li>
                                            <a
                                                href="#"
                                                data-toggle="modal"
                                                data-target="#login-modal"
                                                @click="openLogin"
                                            >
                                                Login
                                            </a>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================
             HEADER BOTTOM
        ========================================================== -->
        <div class="aa-header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-header-bottom-area">
                            <!-- =================================================
                                 LOGO
                            ================================================== -->
                            <div class="aa-logo">
                                <router-link to="/">
                                    <span class="fa fa-shopping-cart"></span>

                                    <p>
                                        daily<strong>Shop</strong>

                                        <span> Your Shopping Partner </span>
                                    </p>
                                </router-link>
                            </div>

                            <!-- =================================================
                                 CART
                            ================================================== -->
                            <div class="aa-cartbox">
                                <!-- CART LINK -->
                                <a class="aa-cart-link" href="#" @click.prevent>
                                    <span class="fa fa-shopping-basket"></span>

                                    <span class="aa-cart-title">
                                        SHOPPING CART
                                    </span>

                                    <!-- CART COUNT -->
                                    <span class="aa-cart-notify">
                                        {{ cartItems.length }}
                                    </span>
                                </a>

                                <!-- =================================================
                                     CART DROPDOWN
                                ================================================== -->
                                <div class="aa-cartbox-summary">
                                    <!-- =================================================
                                         CART HAS ITEMS
                                    ================================================== -->
                                    <ul v-if="cartItems.length > 0">
                                        <li
                                            v-for="item in cartItems"
                                            :key="item.cart_id || item.id"
                                        >
                                            <!-- PRODUCT IMAGE -->
                                            <router-link
                                                class="aa-cartbox-img"
                                                :to="productUrl(item)"
                                            >
                                                <img
                                                    v-if="item.attribute_image"
                                                    :src="
                                                        getImage(
                                                            item.attribute_image,
                                                        )
                                                    "
                                                    :alt="item.name"
                                                    @error="productImageError"
                                                />

                                                <img
                                                    v-else
                                                    :src="getImage(item.image)"
                                                    :alt="item.name"
                                                    @error="productImageError"
                                                />
                                            </router-link>

                                            <!-- PRODUCT INFO -->
                                            <div class="aa-cartbox-info">
                                                <!-- PRODUCT NAME -->
                                                <h4>
                                                    <router-link
                                                        :to="productUrl(item)"
                                                    >
                                                        {{ item.name }}
                                                    </router-link>
                                                </h4>

                                                <!-- QTY + PRICE -->
                                                <p>
                                                    {{ getQty(item) }}

                                                    × Rs

                                                    {{
                                                        Number(
                                                            item.price || 0,
                                                        ).toFixed(2)
                                                    }}
                                                </p>

                                                <!-- SIZE -->
                                                <small
                                                    v-if="getSize(item)"
                                                    class="cart-option"
                                                >
                                                    Size:
                                                    {{ getSize(item) }}
                                                </small>

                                                <!-- COLOR -->
                                                <small
                                                    v-if="getColor(item)"
                                                    class="cart-option"
                                                >
                                                    Color:
                                                    {{ getColor(item) }}
                                                </small>
                                            </div>
                                        </li>

                                        <!-- =================================================
                                             TOTAL
                                        ================================================== -->
                                        <li class="cart-total-row">
                                            <span
                                                class="aa-cartbox-total-title"
                                            >
                                                Total
                                            </span>

                                            <span
                                                class="aa-cartbox-total-price"
                                            >
                                                Rs
                                                {{ cartTotal.toFixed(2) }}
                                            </span>
                                        </li>
                                    </ul>

                                    <!-- =================================================
                                         EMPTY CART
                                    ================================================== -->
                                    <div v-else class="empty-cart">
                                        <span
                                            class="fa fa-shopping-cart"
                                        ></span>

                                        <p>Your cart is empty.</p>
                                    </div>

                                    <!-- =================================================
                                         VIEW CART
                                    ================================================== -->
                                    <router-link
                                        v-if="cartItems.length > 0"
                                        class="aa-cartbox-checkout aa-primary-btn"
                                        to="/cart"
                                    >
                                        View Cart
                                    </router-link>
                                </div>
                            </div>

                            <!-- =================================================
                                 SEARCH
                            ================================================== -->
                            <div class="aa-search-box">
                                <form @submit.prevent="searchProducts">
                                    <input
                                        type="text"
                                        v-model="search"
                                        @input="liveSearch"
                                        placeholder="
                                            Search here ex. 'man'
                                        "
                                    />

                                    <button type="submit">
                                        <span class="fa fa-search"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================
             LOGIN MODAL
        ========================================================== -->
        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- CLOSE -->
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            @click="resetLoginModal"
                        >
                            &times;
                        </button>

                        <!-- =================================================
                             LOGIN
                        ================================================== -->
                        <div v-if="!forgotPassword">
                            <h4>Login or Register</h4>

                            <form class="aa-login-form" @submit.prevent="login">
                                <!-- EMAIL -->
                                <label>
                                    Email address
                                    <span>*</span>
                                </label>

                                <input
                                    type="email"
                                    v-model="loginForm.email"
                                    placeholder="Email"
                                    required
                                />

                                <!-- PASSWORD -->
                                <label>
                                    Password
                                    <span>*</span>
                                </label>

                                <input
                                    type="password"
                                    v-model="loginForm.password"
                                    placeholder="Password"
                                    required
                                />

                                <!-- LOGIN BUTTON -->
                                <button
                                    class="aa-browse-btn"
                                    type="submit"
                                    :disabled="loginLoading"
                                >
                                    <span
                                        v-if="loginLoading"
                                        class="fa fa-spinner fa-spin"
                                    ></span>

                                    {{ loginLoading ? " Login..." : "Login" }}
                                </button>

                                <!-- REMEMBER -->
                                <label class="rememberme">
                                    <input
                                        type="checkbox"
                                        v-model="loginForm.remember"
                                    />

                                    Remember me
                                </label>

                                <!-- LOGIN MESSAGE -->
                                <div
                                    v-if="loginMessage"
                                    id="login_msg"
                                    :class="{
                                        'login-success': loginSuccess,
                                        'login-error': !loginSuccess,
                                    }"
                                >
                                    {{ loginMessage }}
                                </div>

                                <!-- FORGOT -->
                                <p class="aa-lost-password">
                                    <a href="#" @click.prevent="showForgot">
                                        Lost your password?
                                    </a>
                                </p>

                                <!-- REGISTER -->
                                <div class="aa-register-now">
                                    Don't have an account?

                                    <router-link to="/registration">
                                        Register now!
                                    </router-link>
                                </div>
                            </form>
                        </div>

                        <!-- =================================================
                             FORGOT PASSWORD
                        ================================================== -->
                        <div v-else>
                            <h4>Forgot Password</h4>

                            <form
                                class="aa-login-form"
                                @submit.prevent="forgot"
                            >
                                <label>
                                    Email address
                                    <span>*</span>
                                </label>

                                <input
                                    type="email"
                                    v-model="forgotEmail"
                                    placeholder="Email"
                                    required
                                />

                                <button
                                    class="aa-browse-btn"
                                    type="submit"
                                    :disabled="forgotLoading"
                                >
                                    {{
                                        forgotLoading
                                            ? "Submitting..."
                                            : "Submit"
                                    }}
                                </button>

                                <br />
                                <br />

                                <div id="forgot_msg">
                                    {{ forgotMessage }}
                                </div>

                                <div class="aa-register-now">
                                    Login Form?

                                    <a href="#" @click.prevent="showLogin">
                                        Login now!
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    name: "Header",

    data() {
        return {
            // =====================================================
            // CART
            // =====================================================
            cartItems: [],

            // =====================================================
            // SEARCH
            // =====================================================
            search: "",

            searchTimer: null,

            // =====================================================
            // LOGIN
            // =====================================================
            loginLoading: false,

            loginMessage: "",

            loginSuccess: false,

            loginForm: {
                email: "",

                password: "",

                remember: false,
            },

            // =====================================================
            // FORGOT PASSWORD
            // =====================================================
            forgotPassword: false,

            forgotLoading: false,

            forgotMessage: "",

            forgotEmail: "",
        };
    },

    computed: {
        // =====================================================
        // VUEX
        // =====================================================
        ...mapGetters([
            "isUserAuthenticated",
            "getUserToken",
            "getFrontendUser",
            "getGuestId",
        ]),

        // =====================================================
        // LOGIN STATUS
        // =====================================================
        isLoggedIn() {
            return this.isUserAuthenticated;
        },

        // =====================================================
        // CART TOTAL
        // =====================================================
        cartTotal() {
            return this.cartItems.reduce((total, item) => {
                const qty = Number(item.qty || item.quantity || 1);

                const price = Number(item.price || 0);

                return total + qty * price;
            }, 0);
        },
    },

    methods: {
        // =====================================================
        // OPEN LOGIN
        // =====================================================
        openLogin() {
            this.forgotPassword = false;

            this.loginMessage = "";

            this.forgotMessage = "";
        },

        // =====================================================
        // RESET LOGIN MODAL
        // =====================================================
        resetLoginModal() {
            this.forgotPassword = false;

            this.loginMessage = "";

            this.forgotMessage = "";

            this.loginSuccess = false;
        },

        // =====================================================
        // GET HEADER CART
        // =====================================================
        async getHeaderData() {
            try {
                const headers = {
                    Accept: "application/json",
                };

                // =================================================
                // USER TOKEN FROM VUEX
                // =================================================
                const token = this.getUserToken;

                // =================================================
                // GUEST ID FROM VUEX
                // =================================================
                const guestId = this.getGuestId;

                // =================================================
                // AUTHENTICATED USER
                // =================================================
                if (token) {
                    headers.Authorization = `Bearer ${token}`;
                }

                // =================================================
                // GUEST
                // =================================================
                if (!token && guestId) {
                    headers["X-Guest-ID"] = String(guestId);
                }

                // =================================================
                // API
                // =================================================
                const response = await axios.get("/api/cart", {
                    headers,
                });

                const data = response.data || {};

                // =================================================
                // CART ARRAY
                // =================================================
                if (Array.isArray(data.list)) {
                    this.cartItems = data.list;
                } else if (Array.isArray(data.cart)) {
                    this.cartItems = data.cart;
                } else if (Array.isArray(data.data)) {
                    this.cartItems = data.data;
                } else if (Array.isArray(data.result)) {
                    this.cartItems = data.result;
                } else if (Array.isArray(data.result?.list)) {
                    this.cartItems = data.result.list;
                } else if (Array.isArray(data.result?.cart)) {
                    this.cartItems = data.result.cart;
                } else if (Array.isArray(data.result?.cart_items)) {
                    this.cartItems = data.result.cart_items;
                } else {
                    this.cartItems = [];
                }
            } catch (error) {
                this.cartItems = [];
            }
        },

        // =====================================================
        // QUANTITY
        // =====================================================
        getQty(item) {
            return Number(item.qty || item.quantity || 1);
        },

        // =====================================================
        // SIZE
        // =====================================================
        getSize(item) {
            return item.size || item.size_name || item.sizeName || "";
        },

        // =====================================================
        // COLOR
        // =====================================================
        getColor(item) {
            return item.color || item.color_name || item.colorName || "";
        },

        // =====================================================
        // PRODUCT URL
        // =====================================================
        productUrl(item) {
            return "/product/" + item.slug;
        },

        // =====================================================
        // IMAGE
        // =====================================================
        getImage(image) {
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
        // SEARCH
        // =====================================================
        liveSearch() {
            clearTimeout(this.searchTimer);

            const value = this.search.trim();

            if (!value) {
                return;
            }

            this.searchTimer = setTimeout(() => {
                this.$router.push({
                    path: "/products",

                    query: {
                        search: value,
                    },
                });
            }, 500);
        },

        // =====================================================
        // SEARCH SUBMIT
        // =====================================================
        searchProducts() {
            const value = this.search.trim();

            if (!value) {
                return;
            }

            clearTimeout(this.searchTimer);

            this.$router.push({
                path: "/products",

                query: {
                    search: value,
                },
            });
        },

        // =====================================================
        // SHOW FORGOT
        // =====================================================
        showForgot() {
            this.forgotPassword = true;
            this.loginMessage = "";
        },

        // =====================================================
        // SHOW LOGIN
        // =====================================================
        showLogin() {
            this.forgotPassword = false;
            this.forgotMessage = "";
        },

        // =====================================================
        // LOGIN
        // =====================================================
        async login() {
            this.loginLoading = true;
            this.loginMessage = "";

            try {
                const guestId = this.$store.getters.getGuestId;

                const headers = {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                };

                if (guestId) {
                    headers["X-Guest-ID"] = String(guestId);
                }

                const response = await axios.post(
                    "/api/login",
                    {
                        email: this.loginForm.email,
                        password: this.loginForm.password,
                        remember: this.loginForm.remember,
                    },
                    {
                        headers,
                    },
                );

                if (response.data.status === "success" && response.data.token) {
                    /*
                | Vuex mein Passport token save
                */
                    await this.$store.dispatch(
                        "setUserToken",
                        response.data.token,
                    );

                    /*
                | User save
                */

                    await this.$store.dispatch(
                        "setFrontendUser",
                        response.data.user,
                    );

                    // =========================================
                    // REMOVE GUEST ID AFTER LOGIN
                    // =========================================
                    await this.$store.dispatch("clearGuestId");

                    localStorage.removeItem("guest_id");

                    /*
                | Header login state
                */

                    this.isLoggedIn = true;

                    this.loginMessage =
                        response.data.message || "Login successfully.";
                    // =============================================
                    // SUCCESS TOAST
                    // =============================================
                    this.$toast.success("Login  successfully!");

                    /*
                | Cart reload
                */

                    await this.getHeaderData();

                    /*
                | Modal close
                */
                    setTimeout(() => {
                        if (typeof $ !== "undefined") {
                            $("#login-modal").modal("hide");
                        }

                        this.loginForm = {
                            email: "",
                            password: "",
                            remember: false,
                        };

                        this.loginMessage = "";
                        this.$router.push("/");
                    }, 500);

                    return;
                }

                /*
            |--------------------------------------------------------------------------
            | LOGIN FAILED
            |--------------------------------------------------------------------------
            */

                this.loginMessage =
                    response.data.message || "Invalid email or password.";
            } catch (error) {
                this.loginMessage =
                    error.response?.data?.message || "Unable to login.";
            } finally {
                this.loginLoading = false;
            }
        },

        // =====================================================
        // LOGOUT
        // =====================================================
        async logout() {
            try {
                // =============================================
                // GET CURRENT PASSPORT TOKEN
                // =============================================
                const token = this.$store.getters.getUserToken;

                // =============================================
                // LOGOUT API
                // =============================================
                if (token) {
                    try {
                        const response = await axios.post(
                            "/api/logout",
                            {},
                            {
                                headers: {
                                    Accept: "application/json",
                                    Authorization: `Bearer ${token}`,
                                },
                            },
                        );
                    } catch (error) {}
                }

                // =============================================
                // CLEAR VUEX AUTH
                // =============================================
                await this.$store.dispatch("userLogout");

                // =============================================
                // REMOVE TOKEN
                // =============================================
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                localStorage.removeItem("user_token");

                // =============================================
                // CREATE NEW GUEST ID
                // =============================================
                await this.$store.dispatch("getOrCreateGuestId");

                // =============================================
                // CLEAR CART
                // =============================================
                this.cartItems = [];

                // =============================================
                // UPDATE LOGIN STATE
                // =============================================
                this.isLoggedIn = false;

                // =============================================
                // SUCCESS TOAST
                // =============================================
                this.$toast.success("Logout successfully!");
                // =============================================
                // REDIRECT HOME
                // =============================================
                this.$router.push("/");
            } catch (error) {}
        },
        // =====================================================
        // FORGOT PASSWORD
        // =====================================================
        async forgot() {
            this.forgotLoading = true;

            this.forgotMessage = "";

            try {
                const response = await axios.post("/api/forgot_password", {
                    email: this.forgotEmail,
                });

                this.forgotMessage =
                    response.data?.message || "Password reset link sent.";
                this.$toast.success(this.forgotMessage);
                this.forgotPassword = false;
            } catch (error) {
                this.forgotMessage =
                    error.response?.data?.message ||
                    "Unable to process request.";
            } finally {
                this.forgotLoading = false;
            }
        },

        // =====================================================
        // CART UPDATED
        // =====================================================
        handleCartUpdated() {
            this.getHeaderData();
        },

        // =====================================================
        // GET HEADER CART
        // =====================================================
        getHeaderCart() {
            this.getHeaderData();
        },
    },

    // =========================================================
    // MOUNTED
    // =========================================================
    mounted() {
        // =============================================
        // LOAD CART
        // =============================================
        this.getHeaderData();
        // =============================================
        // CART UPDATE EVENT
        // =============================================
        window.addEventListener("cart-updated", this.getHeaderCart);
    },

    // =========================================================
    // BEFORE UNMOUNT
    // =========================================================
    beforeUnmount() {
        window.removeEventListener("cart-updated", this.getHeaderCart);

        clearTimeout(this.searchTimer);
    },
};
</script>

<!-- =============================================================
     STYLE
============================================================== -->
<style>
/* =========================================================
   CART BOX
========================================================= */

#aa-header .aa-cartbox {
    position: relative;
}

/* =========================================================
   CART DROPDOWN
========================================================= */

#aa-header .aa-cartbox .aa-cartbox-summary {
    display: none;

    position: absolute;

    top: 100%;

    right: 0;

    width: 360px;

    background: #ffffff;

    border: 1px solid #eeeeee;

    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);

    z-index: 99999;
}

/* =========================================================
   SHOW CART ON HOVER
========================================================= */

#aa-header .aa-cartbox:hover .aa-cartbox-summary {
    display: block;
}

/* =========================================================
   CART LINK
========================================================= */

#aa-header .aa-cartbox .aa-cart-link {
    cursor: pointer;
}

/* =========================================================
   CART LIST
========================================================= */

#aa-header .aa-cartbox-summary ul {
    margin: 0;

    padding: 0;

    list-style: none;
}

/* =========================================================
   CART ITEM
========================================================= */

#aa-header .aa-cartbox-summary ul li {
    display: flex;

    width: 100%;

    padding: 12px;

    border-bottom: 1px solid #eeeeee;

    box-sizing: border-box;
}

/* =========================================================
   CART IMAGE
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-img {
    display: block;

    width: 70px;

    min-width: 70px;

    height: 70px;

    margin-right: 12px;
}

#aa-header .aa-cartbox-summary .aa-cartbox-img img {
    width: 70px;

    height: 70px;

    object-fit: cover;

    display: block;
}

/* =========================================================
   CART INFO
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-info {
    flex: 1;

    min-width: 0;
}

#aa-header .aa-cartbox-summary .aa-cartbox-info h4 {
    margin: 0 0 5px 0;

    padding: 0;

    font-size: 15px;

    line-height: 20px;
}

#aa-header .aa-cartbox-summary .aa-cartbox-info h4 a {
    color: #333;

    text-decoration: none;
}

#aa-header .aa-cartbox-summary .aa-cartbox-info h4 a:hover {
    color: #ff6666;
}

/* =========================================================
   QTY PRICE
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-info p {
    margin: 0 0 5px 0;

    font-size: 13px;

    color: #555;
}

/* =========================================================
   SIZE COLOR
========================================================= */

#aa-header .aa-cartbox-summary .cart-option {
    display: block;

    font-size: 12px;

    line-height: 18px;

    color: #777;
}

/* =========================================================
   CART TOTAL
========================================================= */

#aa-header .aa-cartbox-summary .cart-total-row {
    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 12px;

    background: #fafafa;
}

/* =========================================================
   TOTAL TITLE
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-total-title {
    font-weight: 600;

    font-size: 15px;
}

/* =========================================================
   TOTAL PRICE
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-total-price {
    font-weight: 700;

    font-size: 16px;

    color: #ff6666;
}

/* =========================================================
   VIEW CART
========================================================= */

#aa-header .aa-cartbox-summary .aa-cartbox-checkout {
    display: block;

    margin: 12px;

    text-align: center;

    text-decoration: none;
}

/* =========================================================
   EMPTY CART
========================================================= */

#aa-header .aa-cartbox-summary .empty-cart {
    text-align: center;

    padding: 30px 15px;
}

#aa-header .aa-cartbox-summary .empty-cart .fa {
    display: block;

    font-size: 35px;

    margin-bottom: 10px;

    color: #ff6666;
}

#aa-header .aa-cartbox-summary .empty-cart p {
    margin: 0;

    color: #777;
}

/* =========================================================
   LOGIN MESSAGE
========================================================= */

#login_msg {
    margin-top: 12px;

    padding: 10px;

    border-radius: 3px;

    font-size: 13px;
}

#login_msg.login-success {
    color: #155724;

    background: #d4edda;

    border: 1px solid #c3e6cb;
}

#login_msg.login-error {
    color: #721c24;

    background: #f8d7da;

    border: 1px solid #f5c6cb;
}

/* =========================================================
   FORGOT MESSAGE
========================================================= */

#forgot_msg {
    margin-top: 10px;
    color: #11e51f;
}

/* =========================================================
   CART Z-INDEX
========================================================= */

#aa-header .aa-cartbox:hover {
    z-index: 100000;
}
</style>
