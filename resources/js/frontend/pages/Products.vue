<template>
    <section id="aa-product-category">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="aa-product-catg-content">
                        <!-- =====================================================
                             SEARCH HEADER
                        ====================================================== -->
                        <div class="aa-product-catg-head">
                            <h2>
                                Search Results
                                <span v-if="search"> for "{{ search }}" </span>
                            </h2>
                        </div>

                        <!-- =====================================================
                             LOADING
                        ====================================================== -->
                        <div v-if="loading" class="search-loading text-center">
                            <span class="fa fa-spinner fa-spin"></span>

                            <h4>Searching products...</h4>
                        </div>

                        <!-- =====================================================
                             PRODUCTS
                        ====================================================== -->
                        <div
                            v-else-if="products.length > 0"
                            class="aa-product-catg-body"
                        >
                            <ul class="aa-product-catg">
                                <li
                                    v-for="product in products"
                                    :key="product.id"
                                >
                                    <figure>
                                        <!-- =================================================
                                             IMAGE
                                        ================================================== -->
                                        <router-link
                                            class="aa-product-img"
                                            :to="'/product/' + product.slug"
                                        >
                                            <img
                                                :src="getImage(product.image)"
                                                :alt="product.name"
                                                @error="productImageError"
                                            />
                                        </router-link>

                                        <!-- =================================================
                                             PRODUCT DETAILS
                                        ================================================== -->
                                        <figcaption>
                                            <!-- PRODUCT NAME -->
                                            <h4 class="aa-product-title">
                                                <router-link
                                                    :to="
                                                        '/product/' +
                                                        product.slug
                                                    "
                                                >
                                                    {{ product.name }}
                                                </router-link>
                                            </h4>

                                            <!-- =================================================
                                                 PRICE
                                            ================================================== -->
                                            <div class="product-price">
                                                <span
                                                    v-if="
                                                        getAttribute(product.id)
                                                    "
                                                    class="aa-product-price"
                                                >
                                                    Rs
                                                    {{ getPrice(product) }}
                                                </span>

                                                <span
                                                    v-if="getMrp(product)"
                                                    class="aa-product-mrp"
                                                >
                                                    <del>
                                                        Rs
                                                        {{ getMrp(product) }}
                                                    </del>
                                                </span>
                                            </div>

                                            <!-- =================================================
                                                 STOCK
                                            ================================================== -->
                                            <div class="product-stock">
                                                <!-- IN STOCK -->
                                                <span
                                                    v-if="isInStock(product)"
                                                    class="in-stock"
                                                >
                                                    <span
                                                        class="fa fa-check-circle"
                                                    ></span>

                                                    In Stock
                                                </span>

                                                <!-- OUT OF STOCK -->
                                                <span v-else class="out-stock">
                                                    <span
                                                        class="fa fa-times-circle"
                                                    ></span>

                                                    Out of Stock
                                                </span>
                                            </div>

                                            <!-- =================================================
                                                 AVAILABLE QUANTITY
                                            ================================================== -->
                                            <p
                                                v-if="isInStock(product)"
                                                class="available-stock"
                                            >
                                                {{ getStock(product) }}
                                                available
                                            </p>

                                            <!-- =================================================
                                                 ADD TO CART
                                            ================================================== -->
                                            <button
                                                v-if="isInStock(product)"
                                                type="button"
                                                class="aa-add-card-btn"
                                                :disabled="
                                                    addingProductId ===
                                                    product.id
                                                "
                                                @click="addToCart(product)"
                                            >
                                                <span
                                                    class="fa fa-shopping-cart"
                                                ></span>

                                                {{
                                                    addingProductId ===
                                                    product.id
                                                        ? "Adding..."
                                                        : "Add To Cart"
                                                }}
                                            </button>

                                            <!-- =================================================
                                                 OUT OF STOCK
                                            ================================================== -->
                                            <button
                                                v-else
                                                type="button"
                                                class="aa-add-card-btn disabled-cart"
                                                disabled
                                            >
                                                <span class="fa fa-ban"></span>

                                                Out of Stock
                                            </button>
                                        </figcaption>
                                    </figure>
                                </li>
                            </ul>
                        </div>

                        <!-- =====================================================
                             NO PRODUCTS
                        ====================================================== -->
                        <div v-else class="no-products text-center">
                            <span class="fa fa-search"></span>

                            <h4>No products found</h4>

                            <p>No products found for "{{ search }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "Product",

    data() {
        return {
            /* =====================================================
               SEARCH
            ====================================================== */
            search: "",

            /* =====================================================
               PRODUCTS
            ====================================================== */
            products: [],

            /* =====================================================
               PRODUCT ATTRIBUTES
            ====================================================== */
            productAttr: {},

            /* =====================================================
               LOADING
            ====================================================== */
            loading: false,

            /* =====================================================
               ADD TO CART LOADING
            ====================================================== */
            addingProductId: null,
        };
    },

    mounted() {
        this.search = this.$route.query.search || "";

        this.searchProducts();
    },

    watch: {
        "$route.query.search"(newValue) {
            this.search = newValue || "";

            this.searchProducts();
        },
    },

    methods: {
        /* =====================================================
           SEARCH PRODUCTS
        ====================================================== */
        async searchProducts() {
            if (!this.search.trim()) {
                this.products = [];
                this.productAttr = [];
                return;
            }

            this.loading = true;

            try {
                const response = await axios.get("/api/search", {
                    params: {
                        search: this.search.trim(),
                    },
                });

                console.log("====================================");

                console.log("SEARCH RESPONSE:", response.data);

                console.log("====================================");

                /* =================================================
                   PRODUCTS
                ================================================== */

                this.products =
                    response.data.products ||
                    response.data.product ||
                    response.data.data ||
                    [];

                /* =================================================
                   ATTRIBUTES
                ================================================== */

                this.productAttr =
                    response.data.product_attr ||
                    response.data.product_attributes ||
                    {};

                console.log("SEARCH PRODUCTS:", this.products);

                console.log("SEARCH ATTRIBUTES:", this.productAttr);
            } catch (error) {
                console.error(
                    "SEARCH ERROR:",
                    error.response?.data || error.message,
                );

                this.products = [];

                this.productAttr = {};
            } finally {
                this.loading = false;
            }
        },

        /* =====================================================
           GET ATTRIBUTE
        ====================================================== */
        getAttribute(productId) {
            const attributes = this.productAttr?.[productId];

            /* ARRAY */

            if (Array.isArray(attributes) && attributes.length > 0) {
                return attributes[0];
            }

            /* OBJECT */

            if (attributes && typeof attributes === "object") {
                return attributes;
            }

            return null;
        },

        /* =====================================================
           GET PRICE
        ====================================================== */
        getPrice(product) {
            const attribute = this.getAttribute(product.id);

            if (attribute) {
                return Number(
                    attribute.price || attribute.selling_price || 0,
                ).toFixed(0);
            }

            return Number(product.price || 0).toFixed(0);
        },

        /* =====================================================
           GET MRP
        ====================================================== */
        getMrp(product) {
            const attribute = this.getAttribute(product.id);

            if (attribute) {
                const mrp = Number(
                    attribute.mrp || attribute.maximum_retail_price || 0,
                );

                return mrp > 0 ? mrp.toFixed(0) : null;
            }

            const mrp = Number(
                product.mrp || product.maximum_retail_price || 0,
            );

            return mrp > 0 ? mrp.toFixed(0) : null;
        },

        /* =====================================================
           GET STOCK
        ====================================================== */
        getStock(product) {
            const attribute = this.getAttribute(product.id);

            if (!attribute) {
                return 0;
            }

            return Number(
                attribute.quantity ?? attribute.qty ?? attribute.stock ?? 0,
            );
        },

        /* =====================================================
           CHECK STOCK
        ====================================================== */
        isInStock(product) {
            return this.getStock(product) > 0;
        },

        /* =====================================================
           GET IMAGE
        ====================================================== */
        getImage(image) {
            if (!image) {
                return "/assets/img/default-product.jpg";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        /* =====================================================
           IMAGE ERROR
        ====================================================== */
        productImageError(event) {
            event.target.src = "/assets/img/default-product.jpg";
        },

        /* =====================================================
           ADD TO CART
        ====================================================== */
        async addToCart(product) {
            /* =================================================
               SET LOADING
            ================================================== */

            this.addingProductId = product.id;

            try {
                console.log("=================================");

                console.log("ADD TO CART PRODUCT:", product);

                console.log("=================================");

                /* =================================================
                   CHECK STOCK
                ================================================== */

                if (!this.isInStock(product)) {
                    alert("Product is out of stock.");

                    return;
                }

                /* =================================================
                   GET PRODUCT ATTRIBUTES
                ================================================== */

                let attributes = this.productAttr?.[product.id];

                /*
                 * Agar API array return karti hai
                 */

                if (!Array.isArray(attributes)) {
                    if (attributes && typeof attributes === "object") {
                        attributes = [attributes];
                    } else {
                        attributes = [];
                    }
                }

                console.log("PRODUCT ATTRIBUTES:", attributes);

                /* =================================================
                   ATTRIBUTE NOT FOUND
                ================================================== */

                if (attributes.length === 0) {
                    console.error(
                        "PRODUCT ATTRIBUTES NOT FOUND:",
                        product.id,
                        this.productAttr,
                    );

                    alert("Product size/color information not found.");

                    return;
                }

                /* =================================================
                   FIRST ATTRIBUTE
                ================================================== */

                const attribute = attributes[0];

                console.log("SELECTED ATTRIBUTE:", attribute);

                /* =================================================
                   QUANTITY
                ================================================== */

                const attributeQuantity = Number(
                    attribute.quantity ?? attribute.qty ?? attribute.stock ?? 0,
                );

                console.log("ATTRIBUTE QUANTITY:", attributeQuantity);

                if (attributeQuantity <= 0) {
                    alert("Product is out of stock.");

                    return;
                }

                /* =================================================
                   TOKEN
                ================================================== */

                const userToken = localStorage.getItem("token");

                /* =================================================
                   GUEST ID
                ================================================== */

                const guestId = localStorage.getItem("guest_id");

                console.log("USER TOKEN:", userToken);

                console.log("GUEST ID:", guestId);

                /* =================================================
                   REQUEST DATA
                ================================================== */

                const data = {
                    product_id: product.id,

                    size_id: attribute.size_id ?? attribute.sizeId ?? null,

                    color_id: attribute.color_id ?? attribute.colorId ?? null,

                    quantity: 1,
                };

                console.log("ADD TO CART DATA:", data);

                /* =================================================
                   HEADERS
                ================================================== */

                const headers = {
                    Accept: "application/json",

                    "Content-Type": "application/json",
                };

                /* =================================================
                   LOGGED USER
                ================================================== */

                if (userToken) {
                    headers.Authorization = `Bearer ${userToken}`;
                } else {

                /* =================================================
                   GUEST USER
                ================================================== */
                    if (!guestId) {
                        console.error("GUEST ID NOT FOUND");

                        alert("Guest ID not found.");

                        return;
                    }

                    headers["X-Guest-ID"] = guestId;
                }

                /* =================================================
                   API REQUEST
                ================================================== */

                console.log("SENDING ADD TO CART REQUEST...");

                const response = await axios.post("/api/add-to-cart", data, {
                    headers,
                });

                console.log("ADD TO CART RESPONSE:", response.data);

                /* =================================================
                   SUCCESS
                ================================================== */

                if (
                    response.data?.status === true ||
                    response.data?.success === true
                ) {
                    const message =
                        response.data?.message || "Product added to cart";

                    /*
                     * Toast available ho to use karo
                     */

                    if (this.$toast) {
                        this.$toast.success(message, {
                            position: "top-right",
                            autoClose: 3000,
                        });
                    } else {
                        alert(message);
                    }

                    /*
                     * Header ko cart reload karwana
                     */

                    window.dispatchEvent(new Event("cart-updated"));

                    return;
                }

                /* =================================================
                   API FAILED
                ================================================== */

                const message =
                    response.data?.message || "Unable to add product to cart.";

                if (this.$toast) {
                    this.$toast.error(message, {
                        position: "top-right",
                        autoClose: 3000,
                    });
                } else {
                    alert(message);
                }
            } catch (error) {
                console.error("ADD TO CART ERROR:", error);

                console.error("ERROR RESPONSE:", error.response?.data);

                console.error("ERROR STATUS:", error.response?.status);

                const message =
                    error.response?.data?.message ||
                    "Something went wrong while adding to cart.";

                if (this.$toast) {
                    this.$toast.error(message, {
                        position: "top-right",
                        autoClose: 3000,
                    });
                } else {
                    alert(message);
                }
            } finally {
                this.addingProductId = null;
            }
        },
    },
};
</script>

<style>
/* =========================================================
   SEARCH LOADING
========================================================= */

.search-loading {
    padding: 50px 20px;
}

.search-loading .fa {
    font-size: 30px;
    margin-bottom: 10px;
}

/* =========================================================
   PRODUCT IMAGE SAME SIZE
========================================================= */

#aa-product-category .aa-product-img {
    display: block;

    width: 100%;

    height: 260px;

    overflow: hidden;

    background: #fff;
}

#aa-product-category .aa-product-img img {
    width: 100%;

    height: 260px;

    object-fit: contain;

    display: block;
}

/* =========================================================
   PRODUCT STOCK
========================================================= */

.product-stock {
    margin-top: 7px;

    margin-bottom: 3px;
}

.product-stock .in-stock {
    color: #28a745;

    font-weight: 600;

    font-size: 13px;
}

.product-stock .out-stock {
    color: #dc3545;

    font-weight: 600;

    font-size: 13px;
}

/* =========================================================
   AVAILABLE STOCK
========================================================= */

.available-stock {
    margin: 2px 0 8px 0;

    color: #777;

    font-size: 12px;
}

/* =========================================================
   PRICE
========================================================= */

.product-price {
    margin-top: 5px;
}

.product-price .aa-product-price {
    margin-right: 8px;
}

.product-price .aa-product-mrp {
    color: #999;

    font-size: 14px;
}

/* =========================================================
   ADD TO CART
========================================================= */

.aa-add-card-btn {
    display: inline-block;

    padding: 9px 16px;

    margin-top: 5px;

    border: none;

    background: #ff6666;

    color: #fff;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;

    border-radius: 2px;

    transition: all 0.2s ease;
}

.aa-add-card-btn:hover {
    background: #333;
}

.aa-add-card-btn:disabled {
    opacity: 0.6;

    cursor: not-allowed;
}

/* =========================================================
   OUT OF STOCK BUTTON
========================================================= */

.aa-add-card-btn.disabled-cart {
    background: #999;

    cursor: not-allowed;

    opacity: 1;
}

/* =========================================================
   NO PRODUCTS
========================================================= */

.no-products {
    padding: 60px 20px;
}

.no-products .fa {
    font-size: 45px;

    color: #ccc;

    margin-bottom: 15px;
}

.no-products h4 {
    margin-bottom: 8px;
}
</style>
