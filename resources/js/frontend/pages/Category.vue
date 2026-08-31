<template>
    <!-- ========================================================= -->
    <!-- PRODUCT CATEGORY -->
    <!-- ========================================================= -->

    <section id="aa-product-category">
        <div class="container">
            <div class="row">
                <!-- ===================================================== -->
                <!-- PRODUCT CONTENT -->
                <!-- ===================================================== -->

                <div class="col-lg-9 col-md-9 col-sm-8 col-md-push-3">
                    <div class="aa-product-catg-content">
                        <!-- ================================================= -->
                        <!-- PRODUCT HEADER -->
                        <!-- ================================================= -->

                        <div class="aa-product-catg-head">
                            <div class="aa-product-catg-head-left">
                                <form
                                    action=""
                                    class="aa-sort-form"
                                    @submit.prevent
                                >
                                    <label for="sort_by_value"> Sort by </label>

                                    <select
                                        id="sort_by_value"
                                        v-model="sort"
                                        @change="sortBy"
                                    >
                                        <option value="">Default</option>

                                        <option value="name">Name</option>

                                        <option value="price_desc">
                                            Price - Desc
                                        </option>

                                        <option value="price_asc">
                                            Price - Asc
                                        </option>

                                        <option value="date">Date</option>
                                    </select>
                                </form>

                                <span>
                                    {{ sort_txt }}
                                </span>
                            </div>

                            <!-- GRID / LIST -->

                            <div class="aa-product-catg-head-right">
                                <a
                                    id="grid-catg"
                                    href="javascript:void(0)"
                                    :class="{
                                        active: viewType === 'grid',
                                    }"
                                    @click.prevent="setView('grid')"
                                >
                                    <span class="fa fa-th"></span>
                                </a>

                                <a
                                    id="list-catg"
                                    href="javascript:void(0)"
                                    :class="{
                                        active: viewType === 'list',
                                    }"
                                    @click.prevent="setView('list')"
                                >
                                    <span class="fa fa-list"></span>
                                </a>
                            </div>
                        </div>

                        <!-- ================================================= -->
                        <!-- PRODUCT BODY -->
                        <!-- ================================================= -->

                        <div class="aa-product-catg-body">
                            <ul
                                class="aa-product-catg"
                                :class="{
                                    'aa-product-catg-list': viewType === 'list',
                                }"
                            >
                                <!-- ================================================= -->
                                <!-- LOADING -->
                                <!-- ================================================= -->

                                <li v-if="loading">
                                    <figure>
                                        <div class="text-center">
                                            Loading products...
                                        </div>
                                    </figure>
                                </li>

                                <!-- ================================================= -->
                                <!-- PRODUCTS -->
                                <!-- ================================================= -->

                                <template v-else>
                                    <li
                                        v-for="product in products"
                                        :key="product.id"
                                        :class="{
                                            'product-out-of-stock':
                                                !isProductInStock(product),
                                        }"
                                    >
                                        <figure>
                                            <!-- ================================================= -->
                                            <!-- PRODUCT IMAGE -->
                                            <!-- ================================================= -->

                                            <router-link
                                                class="aa-product-img"
                                                :to="`/product/${product.slug}`"
                                            >
                                                <img
                                                    :src="
                                                        getProductImage(
                                                            product.image,
                                                        )
                                                    "
                                                    :alt="product.name"
                                                    @error="productImageError"
                                                />
                                            </router-link>

                                            <!-- ================================================= -->
                                            <!-- STOCK BADGE -->
                                            <!-- ================================================= -->

                                            <div
                                                v-if="
                                                    !isProductInStock(product)
                                                "
                                                class="out-of-stock-badge"
                                            >
                                                Out of Stock
                                            </div>

                                            <div v-else class="in-stock-badge">
                                                In Stock
                                            </div>

                                            <!-- ================================================= -->
                                            <!-- ADD TO CART -->
                                            <!-- ================================================= -->

                                            <a
                                                v-if="isProductInStock(product)"
                                                class="aa-add-card-btn"
                                                href="javascript:void(0)"
                                                @click.prevent="
                                                    addToCart(product)
                                                "
                                            >
                                                <span
                                                    class="fa fa-shopping-cart"
                                                ></span>

                                                Add To Cart
                                            </a>

                                            <!-- ================================================= -->
                                            <!-- OUT OF STOCK BUTTON -->
                                            <!-- ================================================= -->

                                            <a
                                                v-else
                                                class="aa-add-card-btn out-of-stock-btn"
                                                href="javascript:void(0)"
                                                @click.prevent="
                                                    showOutOfStockMessage
                                                "
                                            >
                                                <span class="fa fa-ban"></span>

                                                Out of Stock
                                            </a>

                                            <!-- ================================================= -->
                                            <!-- PRODUCT DETAILS -->
                                            <!-- ================================================= -->

                                            <figcaption>
                                                <!-- PRODUCT NAME -->

                                                <h4 class="aa-product-title">
                                                    <router-link
                                                        :to="`/product/${product.slug}`"
                                                    >
                                                        {{ product.name }}
                                                    </router-link>
                                                </h4>

                                                <!-- PRICE -->

                                                <span class="aa-product-price">
                                                    Rs
                                                    {{
                                                        getProductPrice(product)
                                                    }}
                                                </span>

                                                <!-- MRP -->

                                                <span class="aa-product-price">
                                                    <del>
                                                        Rs
                                                        {{
                                                            getProductMaximum_retail_price(
                                                                product,
                                                            )
                                                        }}
                                                    </del>
                                                </span>

                                                <!-- STOCK STATUS -->

                                                <div
                                                    class="product-stock-status"
                                                >
                                                    <span
                                                        v-if="
                                                            isProductInStock(
                                                                product,
                                                            )
                                                        "
                                                        class="in-stock"
                                                    >
                                                        In Stock
                                                    </span>

                                                    <span
                                                        v-else
                                                        class="out-of-stock"
                                                    >
                                                        Out of Stock
                                                    </span>
                                                </div>

                                                <!-- QUANTITY -->

                                                <div
                                                    v-if="
                                                        isProductInStock(
                                                            product,
                                                        )
                                                    "
                                                    class="product-quantity-info"
                                                >
                                                    {{
                                                        getProductQuantity(
                                                            product,
                                                        )
                                                    }}
                                                    available
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </li>

                                    <!-- ================================================= -->
                                    <!-- NO DATA -->
                                    <!-- ================================================= -->

                                    <li v-if="products.length === 0">
                                        <figure>No data found</figure>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ===================================================== -->
                <!-- SIDEBAR -->
                <!-- ===================================================== -->

                <div class="col-lg-3 col-md-3 col-sm-4 col-md-pull-9">
                    <aside class="aa-sidebar">
                        <!-- ================================================= -->
                        <!-- CATEGORY -->
                        <!-- ================================================= -->

                        <div class="aa-sidebar-widget">
                            <h3>Category</h3>

                            <ul class="aa-catg-nav">
                                <li
                                    v-for="category in categories_left"
                                    :key="category.id"
                                >
                                    <router-link
                                        :to="`/category/${category.id}`"
                                        :class="{
                                            left_cat_active:
                                                Number(category.id) ===
                                                Number(categoryId),
                                        }"
                                    >
                                        {{
                                            category.name ||
                                            category.category_name
                                        }}
                                    </router-link>
                                </li>
                            </ul>
                        </div>

                        <!-- ================================================= -->
                        <!-- PRICE -->
                        <!-- ================================================= -->

                        <div class="aa-sidebar-widget">
                            <h3>Shop By Price</h3>

                            <div class="aa-sidebar-price-range">
                                <form @submit.prevent>
                                    <div class="price-slider">
                                        <input
                                            type="range"
                                            min="0"
                                            max="5000"
                                            step="50"
                                            v-model.number="filter_price_start"
                                            @input="checkPriceRange"
                                        />

                                        <input
                                            type="range"
                                            min="0"
                                            max="5000"
                                            step="50"
                                            v-model.number="filter_price_end"
                                            @input="checkPriceRange"
                                        />
                                    </div>

                                    <!-- PRICE VALUES -->

                                    <span
                                        id="skip-value-lower"
                                        class="example-val"
                                    >
                                        {{
                                            Number(filter_price_start).toFixed(
                                                2,
                                            )
                                        }}
                                    </span>

                                    <span
                                        id="skip-value-upper"
                                        class="example-val"
                                    >
                                        {{
                                            Number(filter_price_end).toFixed(2)
                                        }}
                                    </span>

                                    <!-- FILTER -->

                                    <button
                                        class="aa-filter-btn"
                                        type="button"
                                        @click="sortPriceFilter"
                                    >
                                        Filter
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- ================================================= -->
                        <!-- COLOR -->
                        <!-- ================================================= -->

                        <div class="aa-sidebar-widget">
                            <h3>Shop By Color</h3>

                            <div class="aa-color-tag">
                                <a
                                    v-for="color in colors"
                                    :key="color.id"
                                    href="javascript:void(0)"
                                    class="aa-color-item"
                                    :class="{
                                        active_color: colorFilterArr.includes(
                                            Number(color.id),
                                        ),
                                    }"
                                    :style="{
                                        backgroundColor: getColorValue(
                                            color.name,
                                        ),
                                    }"
                                    :title="color.color"
                                    @click.prevent="setColor(color.id)"
                                ></a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================= -->
    <!-- ADD TO CART FORM -->
    <!-- ========================================================= -->

    <input type="hidden" id="quantity" value="1" />

    <form id="frmAddToCart" @submit.prevent>
        <input
            type="hidden"
            id="size_id"
            name="size_id"
            v-model="cartForm.size_id"
        />

        <input
            type="hidden"
            id="color_id"
            name="color_id"
            v-model="cartForm.color_id"
        />

        <input
            type="hidden"
            id="productQuantity"
            name="productQuantity"
            v-model="cartForm.productQuantity"
        />

        <input
            type="hidden"
            id="product_id"
            name="product_id"
            v-model="cartForm.product_id"
        />
    </form>

    <!-- ========================================================= -->
    <!-- CATEGORY FILTER -->
    <!-- ========================================================= -->

    <form id="categoryFilter" @submit.prevent>
        <input type="hidden" id="sort" name="sort" v-model="sort" />

        <input
            type="hidden"
            id="filter_price_start"
            name="filter_price_start"
            v-model="filter_price_start"
        />

        <input
            type="hidden"
            id="filter_price_end"
            name="filter_price_end"
            v-model="filter_price_end"
        />

        <input
            type="hidden"
            id="color_filter"
            name="color_filter"
            v-model="color_filter"
        />
    </form>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    name: "Category",

    data() {
        return {
            // =====================================================
            // CATEGORY
            // =====================================================

            categoryId: null,

            // =====================================================
            // PRODUCTS
            // =====================================================

            products: [],

            // =====================================================
            // PRODUCT ATTRIBUTES
            // =====================================================

            product_attributes: {},

            // =====================================================
            // CATEGORIES
            // =====================================================

            categories_left: [],

            // =====================================================
            // COLORS
            // =====================================================

            colors: [],

            colorFilterArr: [],

            color_filter: "",

            // =====================================================
            // SORT
            // =====================================================

            sort: "",

            sort_txt: "",

            // =====================================================
            // PRICE
            // =====================================================

            filter_price_start: 0,

            filter_price_end: 1000,

            sliderMin: 0,

            sliderMax: 1000,

            priceFilterApplied: false,

            // =====================================================
            // VIEW
            // =====================================================

            viewType: "grid",

            // =====================================================
            // LOADING
            // =====================================================

            loading: false,

            // =====================================================
            // SLIDER
            // =====================================================

            priceSlider: null,

            // =====================================================
            // CART
            // =====================================================

            cartForm: {
                size_id: "",
                color_id: "",
                productQuantity: 1,
                product_id: "",
            },
        };
    },

    computed: {
        ...mapGetters(["getUserToken", "getGuestId"]),
    },

    methods: {
        // =========================================================
        // COLOR VALUE
        // =========================================================

        getColorValue(color) {
            if (!color) {
                return "#cccccc";
            }

            const value = String(color).trim().toLowerCase();

            const colorMap = {
                red: "#ff0000",
                blue: "#0000ff",
                green: "#008000",
                yellow: "#ffff00",
                orange: "#ffa500",
                purple: "#800080",
                pink: "#ffc0cb",
                black: "#000000",
                white: "#ffffff",
                gray: "#808080",
                grey: "#808080",
                brown: "#a52a2a",
                cyan: "#00ffff",
                magenta: "#ff00ff",
                silver: "#c0c0c0",
                gold: "#ffd700",
                navy: "#000080",
                maroon: "#800000",
                lime: "#00ff00",
                olive: "#808000",
                teal: "#008080",
                aqua: "#00ffff",
                beige: "#f5f5dc",
                violet: "#ee82ee",
                indigo: "#4b0082",
            };

            if (
                value.startsWith("#") ||
                value.startsWith("rgb(") ||
                value.startsWith("rgba(") ||
                value.startsWith("hsl(") ||
                value.startsWith("hsla(")
            ) {
                return value;
            }

            return colorMap[value] || "#cccccc";
        },

        // =========================================================
        // IMAGE
        // =========================================================

        getProductImage(image) {
            if (!image) {
                return "/assets/img/default-product.jpg";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // =========================================================
        // IMAGE ERROR
        // =========================================================

        productImageError(event) {
            event.target.src = "/assets/img/default-product.jpg";
        },

        // =========================================================
        // ATTRIBUTE
        // =========================================================

        getProductAttribute(product) {
            const attributes = this.product_attributes[product.id];

            if (attributes && attributes.length > 0) {
                return attributes[0];
            }

            return null;
        },

        // =========================================================
        // PRODUCT QUANTITY
        // =========================================================

        getProductQuantity(product) {
            const attributes = this.product_attributes[product.id];

            if (attributes && attributes.length > 0) {
                return attributes.reduce((total, attribute) => {
                    return total + Number(attribute.quantity || 0);
                }, 0);
            }

            return Number(product.quantity || 0);
        },

        // =========================================================
        // PRODUCT STOCK
        // =========================================================

        isProductInStock(product) {
            const attributes = this.product_attributes[product.id];

            if (attributes && attributes.length > 0) {
                return attributes.some((attribute) => {
                    return Number(attribute.quantity || 0) > 0;
                });
            }

            return Number(product.quantity || 0) > 0;
        },

        // =========================================================
        // PRICE
        // =========================================================

        getProductPrice(product) {
            const attribute = this.getProductAttribute(product);

            if (
                attribute &&
                attribute.price !== undefined &&
                attribute.price !== null
            ) {
                return attribute.price;
            }

            return product.price || 0;
        },

        // =========================================================
        // MRP
        // =========================================================

        getProductMaximum_retail_price(product) {
            const attribute = this.getProductAttribute(product);

            if (
                attribute &&
                attribute.maximum_retail_price !== undefined &&
                attribute.maximum_retail_price !== null
            ) {
                return attribute.maximum_retail_price;
            }

            if (
                product.maximum_retail_price !== undefined &&
                product.maximum_retail_price !== null
            ) {
                return product.maximum_retail_price;
            }

            return 0;
        },

        // =========================================================
        // OUT OF STOCK MESSAGE
        // =========================================================

        showOutOfStockMessage() {
            if (this.$toast) {
                this.$toast.error("This product is out of stock.", {
                    position: "top-right",
                    autoClose: 3000,
                });
            } else {
                alert("This product is out of stock.");
            }
        },

        // =========================================================
        // CATEGORY DATA
        // =========================================================

        async getCategoryData() {
            this.loading = true;

            try {
                const id = this.$route.params.id;

                this.categoryId = id;

                const params = {
                    sort: this.sort,
                };

                // =================================================
                // PRICE FILTER
                // =================================================

                if (this.priceFilterApplied) {
                    params.filter_price_start = this.filter_price_start;

                    params.filter_price_end = this.filter_price_end;
                }

                // =================================================
                // COLOR FILTER
                // =================================================

                if (this.color_filter) {
                    params.color_filter = this.color_filter;
                }

                console.log("CATEGORY REQUEST:", `/api/category/${id}`, params);

                const response = await axios.get(`/api/category/${id}`, {
                    params,
                });

                console.log("CATEGORY RESPONSE:", response.data);

                const data = response.data?.result || {};

                // =================================================
                // PRODUCTS
                // =================================================

                this.products = Array.isArray(data.products)
                    ? data.products
                    : [];

                // =================================================
                // ATTRIBUTES
                // =================================================

                this.product_attributes = data.product_attributes || {};

                // =================================================
                // CATEGORIES
                // =================================================

                this.categories_left = Array.isArray(data.categories_left)
                    ? data.categories_left
                    : [];

                // =================================================
                // COLORS
                // =================================================

                this.colors = Array.isArray(data.colors) ? data.colors : [];

                // =================================================
                // SORT TEXT
                // =================================================

                this.sort_txt = data.sort_txt || "";

                // =================================================
                // COLOR FILTER
                // =================================================

                this.color_filter = data.color_filter || "";

                this.colorFilterArr = Array.isArray(data.colorFilterArr)
                    ? data.colorFilterArr.map(Number)
                    : [];

                // =================================================
                // PRICE
                // =================================================

                if (
                    data.filter_price_start !== undefined &&
                    data.filter_price_start !== ""
                ) {
                    this.filter_price_start = Number(data.filter_price_start);
                }

                if (
                    data.filter_price_end !== undefined &&
                    data.filter_price_end !== ""
                ) {
                    this.filter_price_end = Number(data.filter_price_end);
                }

                // =================================================
                // PRICE RANGE
                // =================================================

                this.setPriceRange();

                // =================================================
                // DEBUG STOCK
                // =================================================

                this.products.forEach((product) => {
                    console.log(
                        "PRODUCT STOCK:",
                        product.name,
                        this.getProductQuantity(product),
                        this.isProductInStock(product),
                    );
                });
            } catch (error) {
                console.error(
                    "CATEGORY ERROR:",
                    error.response?.data || error.message,
                );

                this.products = [];

                this.product_attributes = {};

                this.categories_left = [];

                this.colors = [];
            } finally {
                this.loading = false;
            }
        },

        // =========================================================
        // PRICE RANGE
        // =========================================================

        setPriceRange() {
            const prices = [];

            this.products.forEach((product) => {
                const attributes = this.product_attributes[product.id];

                if (attributes && attributes.length) {
                    attributes.forEach((attribute) => {
                        if (
                            attribute.price !== undefined &&
                            attribute.price !== null
                        ) {
                            prices.push(Number(attribute.price));
                        }
                    });
                } else if (
                    product.price !== undefined &&
                    product.price !== null
                ) {
                    prices.push(Number(product.price));
                }
            });

            if (prices.length > 0) {
                const min = Math.floor(Math.min(...prices));

                const max = Math.ceil(Math.max(...prices));

                this.sliderMin = min;

                this.sliderMax = max > min ? max : min + 100;

                if (!this.priceFilterApplied) {
                    this.filter_price_start = this.sliderMin;

                    this.filter_price_end = this.sliderMax;
                }
            }

            this.$nextTick(() => {
                this.initPriceSlider();
            });
        },

        // =========================================================
        // SORT
        // =========================================================

        async sortBy() {
            await this.getCategoryData();
        },

        // =========================================================
        // PRICE RANGE CHECK
        // =========================================================

        checkPriceRange() {
            if (
                Number(this.filter_price_start) > Number(this.filter_price_end)
            ) {
                this.filter_price_start = this.filter_price_end;
            }
        },

        // =========================================================
        // PRICE SLIDER
        // =========================================================

        initPriceSlider() {
            const slider = this.$refs.priceSlider;

            if (!slider) {
                return;
            }

            if (this.priceSlider) {
                this.priceSlider.destroy();

                this.priceSlider = null;
            }

            let min = Number(this.sliderMin);

            let max = Number(this.sliderMax);

            if (max <= min) {
                max = min + 100;
            }

            let start = Number(this.filter_price_start);

            let end = Number(this.filter_price_end);

            if (start < min || start > max || isNaN(start)) {
                start = min;
            }

            if (end < min || end > max || isNaN(end)) {
                end = max;
            }

            this.priceSlider = noUiSlider.create(slider, {
                start: [start, end],

                connect: true,

                range: {
                    min: min,
                    max: max,
                },
            });

            this.priceSlider.on("update", (values) => {
                this.filter_price_start = Number(values[0]).toFixed(2);

                this.filter_price_end = Number(values[1]).toFixed(2);
            });
        },

        // =========================================================
        // PRICE FILTER
        // =========================================================

        async sortPriceFilter() {
            this.priceFilterApplied = true;

            await this.getCategoryData();
        },

        // =========================================================
        // COLOR
        // =========================================================

        async setColor(colorId) {
            const id = Number(colorId);

            if (this.colorFilterArr.includes(id)) {
                this.colorFilterArr = this.colorFilterArr.filter(
                    (color) => color !== id,
                );
            } else {
                this.colorFilterArr.push(id);
            }

            this.color_filter = this.colorFilterArr.join(",");

            await this.getCategoryData();
        },

        // =========================================================
        // VIEW
        // =========================================================

        setView(type) {
            this.viewType = type;
        },

        // =========================================================
        // ADD TO CART
        // =========================================================

        async addToCart(product) {
            try {
                // =================================================
                // STOCK
                // =================================================

                if (!this.isProductInStock(product)) {
                    this.showOutOfStockMessage();

                    return;
                }

                // =================================================
                // ATTRIBUTES
                // =================================================

                const attributes = this.product_attributes[product.id] || [];

                if (!attributes.length) {
                    console.error(
                        "PRODUCT ATTRIBUTES NOT FOUND:",
                        product.id,
                        this.product_attributes,
                    );

                    if (this.$toast) {
                        this.$toast.error(
                            "Product size/color information not found.",
                            {
                                position: "top-right",
                                autoClose: 3000,
                            },
                        );
                    }

                    return;
                }

                // =================================================
                // FIRST ATTRIBUTE
                // =================================================

                const attribute = attributes[0];

                console.log("SELECTED ATTRIBUTE:", attribute);

                const attributeQuantity = Number(attribute.quantity || 0);
                if (attributeQuantity <= 0) {
                    this.showOutOfStockMessage();

                    return;
                }

                // =================================================
                // TOKEN
                // =================================================

                const userToken = this.getUserToken;

                // =================================================
                // GUEST ID
                // =================================================

                const guestId = this.getGuestId;

                // =================================================
                // REQUEST
                // =================================================

                const data = {
                    product_id: product.id,

                    size_id: attribute.size_id ?? attribute.sizeId ?? null,

                    color_id: attribute.color_id ?? attribute.colorId ?? null,

                    quantity: 1,
                };

                // =================================================
                // HEADERS
                // =================================================

                const headers = {
                    Accept: "application/json",

                    "Content-Type": "application/json",
                };

                // =================================================
                // USER
                // =================================================

                if (userToken) {
                    headers.Authorization = `Bearer ${userToken}`;
                }

                // =================================================
                // GUEST
                // =================================================

                if (!userToken) {
                    if (!guestId) {
                        console.error("GUEST ID NOT FOUND");

                        if (this.$toast) {
                            this.$toast.error("Guest ID not found.", {
                                position: "top-right",
                                autoClose: 3000,
                            });
                        }

                        return;
                    }

                    headers["X-Guest-ID"] = guestId;
                }

                // =================================================
                // API
                // =================================================

                console.log("SENDING ADD TO CART REQUEST...");

                const response = await axios.post("/api/add-to-cart", data, {
                    headers,
                });

                console.log("ADD TO CART RESPONSE:", response.data);

                // =================================================
                // SUCCESS
                // =================================================

                if (
                    response.data?.status === true ||
                    response.data?.success === true
                ) {
                    if (this.$toast) {
                        this.$toast.success(
                            response.data?.message || "Product added to cart",
                            {
                                position: "top-right",
                                autoClose: 3000,
                            },
                        );

                        window.dispatchEvent(new Event("cart-updated"));
                    } else {
                        alert("Product added to cart");
                    }

                    return;
                }

                // =================================================
                // FAILED
                // =================================================

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
            }
        },
    },

    // =========================================================
    // MOUNTED
    // =========================================================

    async mounted() {
        this.categoryId = this.$route.params.id;

        await this.getCategoryData();
    },

    // =========================================================
    // WATCH CATEGORY
    // =========================================================

    watch: {
        "$route.params.id": {
            async handler(newId) {
                this.categoryId = newId;

                this.sort = "";

                this.sort_txt = "";

                this.colorFilterArr = [];

                this.color_filter = "";

                this.priceFilterApplied = false;

                await this.getCategoryData();
            },
        },
    },

    // =========================================================
    // BEFORE UNMOUNT
    // =========================================================

    beforeUnmount() {
        if (this.priceSlider) {
            this.priceSlider.destroy();

            this.priceSlider = null;
        }
    },
};
</script>

<style scoped>
/* =========================================================
   PRODUCT CARD
========================================================= */

#aa-product-category .aa-product-catg > li {
    height: 430px !important;

    overflow: hidden !important;

    position: relative !important;

    margin-bottom: 30px;
}

/* =========================================================
   FIGURE
========================================================= */

#aa-product-category .aa-product-catg > li figure {
    height: 100% !important;

    margin: 0 !important;

    position: relative !important;

    overflow: visible !important;
}

/* =========================================================
   PRODUCT IMAGE CONTAINER
========================================================= */

#aa-product-category .aa-product-catg > li .aa-product-img {
    display: flex !important;

    align-items: center !important;

    justify-content: center !important;

    width: 100% !important;

    height: 250px !important;

    overflow: hidden !important;

    background: #fff !important;

    position: relative !important;
}

/* =========================================================
   PRODUCT IMAGE
========================================================= */

#aa-product-category .aa-product-catg > li .aa-product-img img {
    width: 100% !important;

    height: 250px !important;

    max-width: 100% !important;

    max-height: 250px !important;

    object-fit: contain !important;

    object-position: center !important;

    display: block !important;
}

/* =========================================================
   PRODUCT DETAILS
========================================================= */

#aa-product-category .aa-product-catg > li figcaption {
    display: block !important;

    width: 100% !important;

    height: 170px !important;

    padding: 12px 10px !important;

    overflow: hidden !important;

    background: #fff !important;

    position: relative !important;

    z-index: 10 !important;
}

/* =========================================================
   PRODUCT TITLE
========================================================= */

#aa-product-category .aa-product-title {
    display: block !important;

    height: 42px !important;

    margin: 0 0 8px !important;

    overflow: hidden !important;

    line-height: 21px !important;
}

#aa-product-category .aa-product-title a {
    display: -webkit-box !important;

    -webkit-line-clamp: 2 !important;

    -webkit-box-orient: vertical !important;

    overflow: hidden !important;

    line-height: 21px !important;
}

/* =========================================================
   PRICE
========================================================= */

#aa-product-category .aa-product-price {
    display: inline-block !important;

    margin-right: 8px !important;

    font-size: 15px !important;

    line-height: 22px !important;
}

/* =========================================================
   STOCK STATUS
========================================================= */

#aa-product-category .product-stock-status {
    margin-top: 7px !important;

    margin-bottom: 4px !important;
}

.in-stock {
    display: inline-block;

    padding: 4px 10px;

    background: #e8f8ee;

    color: #198754;

    border-radius: 4px;

    font-size: 13px;

    font-weight: 600;
}

.out-of-stock {
    display: inline-block;

    padding: 4px 10px;

    background: #fdeaea;

    color: #dc3545;

    border-radius: 4px;

    font-size: 13px;

    font-weight: 600;
}

/* =========================================================
   QUANTITY
========================================================= */

.product-quantity-info {
    display: block;

    margin-top: 4px;

    color: #777;

    font-size: 12px;
}

/* =========================================================
   ADD TO CART BUTTON
========================================================= */

#aa-product-category .aa-product-catg > li .aa-add-card-btn {
    position: absolute !important;

    left: 50% !important;

    bottom: 175px !important;

    transform: translateX(-50%) translateY(15px) !important;

    width: 85% !important;

    padding: 10px 12px !important;

    text-align: center !important;

    opacity: 0 !important;

    visibility: hidden !important;

    z-index: 30 !important;

    transition: all 0.3s ease !important;

    cursor: pointer !important;
}

/* =========================================================
   HOVER ADD TO CART
========================================================= */

#aa-product-category .aa-product-catg > li:hover .aa-add-card-btn {
    opacity: 1 !important;

    visibility: visible !important;

    transform: translateX(-50%) translateY(0) !important;
}

/* =========================================================
   OUT OF STOCK BUTTON
========================================================= */

.out-of-stock-btn {
    background: #999 !important;

    color: #fff !important;

    cursor: not-allowed !important;

    opacity: 0.8;
}

/* =========================================================
   STOCK BADGE ON IMAGE
========================================================= */

.product-out-of-stock {
    position: relative !important;
}

.out-of-stock-badge,
.in-stock-badge {
    position: absolute !important;

    top: 10px !important;

    left: 10px !important;

    z-index: 25 !important;

    padding: 5px 10px !important;

    border-radius: 4px !important;

    font-size: 12px !important;

    font-weight: 600 !important;
}

.out-of-stock-badge {
    background: #dc3545;

    color: #fff;
}

.in-stock-badge {
    background: #198754;

    color: #fff;
}

/* =========================================================
   COLOR
========================================================= */

.aa-color-tag {
    display: flex;

    flex-wrap: wrap;

    gap: 8px;
}

.aa-color-item {
    display: inline-block !important;

    width: 30px !important;

    height: 30px !important;

    min-width: 30px !important;

    min-height: 30px !important;

    border-radius: 50% !important;

    border: 2px solid #ddd !important;

    cursor: pointer;

    box-sizing: border-box;
}

.aa-color-item:hover {
    border-color: #333 !important;
}

.aa-color-item.active_color {
    border: 3px solid #000 !important;

    box-shadow:
        0 0 0 2px #fff,
        0 0 0 4px #000 !important;
}

/* =========================================================
   PRICE SIDEBAR
========================================================= */

#aa-product-category .aa-sidebar-price-range {
    width: 100%;

    padding: 10px 0;
}

.price-slider {
    position: relative;

    width: 100%;

    height: 35px;

    margin: 20px 0;
}

.price-slider input[type="range"] {
    position: absolute;

    left: 0;

    top: 10px;

    width: 100%;

    height: 6px;

    margin: 0;

    padding: 0;

    pointer-events: none;

    appearance: none;

    background: transparent;
}

.price-slider input[type="range"]::-webkit-slider-runnable-track {
    height: 6px;

    background: #3db6ae;

    border-radius: 5px;
}

.price-slider input[type="range"]::-webkit-slider-thumb {
    appearance: none;

    width: 38px;

    height: 34px;

    margin-top: -14px;

    background: #fff;

    border: 1px solid #ddd;

    border-radius: 3px;

    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);

    pointer-events: auto;

    cursor: pointer;
}

.price-slider input[type="range"]::-moz-range-track {
    height: 6px;

    background: #3db6ae;

    border-radius: 5px;
}

.price-slider input[type="range"]::-moz-range-thumb {
    width: 38px;

    height: 34px;

    background: #fff;

    border: 1px solid #ddd;

    border-radius: 3px;

    pointer-events: auto;

    cursor: pointer;
}

/* =========================================================
   PRICE VALUES
========================================================= */

#aa-product-category .aa-sidebar-price-range .example-val {
    display: inline-block;

    min-width: 100px;

    padding: 10px 15px;

    margin-right: 10px;

    border: 1px solid #ddd;

    background: #fff;

    text-align: center;

    box-sizing: border-box;
}

#aa-product-category .aa-sidebar-price-range .aa-filter-btn {
    margin-top: 0;
}

/* =========================================================
   NO UI SLIDER
========================================================= */

#aa-product-category .aa-price-slider {
    width: 100% !important;

    margin: 15px 0 25px !important;

    position: relative !important;

    box-sizing: border-box;
}

#aa-product-category .aa-price-slider.noUi-target {
    height: 6px !important;
}

#aa-product-category .aa-price-slider .noUi-base {
    width: 100% !important;
}

#aa-product-category .aa-price-slider .noUi-handle {
    width: 40px !important;

    height: 34px !important;

    right: -20px !important;

    top: -15px !important;
}

#aa-product-category .aa-price-slider .noUi-connect {
    background: #3db6ae !important;
}

#aa-product-category .aa-price-slider .noUi-handle::before,
#aa-product-category .aa-price-slider .noUi-handle::after {
    display: none !important;
}

#aa-product-category .aa-sidebar-price-range form {
    width: 100%;
}

#aa-product-category #skip-value-lower,
#aa-product-category #skip-value-upper {
    display: inline-block;

    box-sizing: border-box;
}

#aa-product-category #skip-value-lower {
    margin-right: 12px;
}

#aa-product-category .aa-filter-btn {
    margin-left: 12px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991px) {
    #aa-product-category .aa-product-catg > li {
        height: 420px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-product-img {
        height: 230px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-product-img img {
        height: 230px !important;

        max-height: 230px !important;
    }

    #aa-product-category .aa-product-catg > li figcaption {
        height: 170px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-add-card-btn {
        bottom: 175px !important;
    }
}

@media (max-width: 767px) {
    #aa-product-category .aa-product-catg > li {
        height: 400px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-product-img {
        height: 210px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-product-img img {
        height: 210px !important;

        max-height: 210px !important;
    }

    #aa-product-category .aa-product-catg > li figcaption {
        height: 160px !important;

        padding: 10px !important;
    }

    #aa-product-category .aa-product-catg > li .aa-add-card-btn {
        bottom: 165px !important;

        width: 90% !important;
    }
}
</style>
