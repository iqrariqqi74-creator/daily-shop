<template>
    <div>
        <!-- =====================================================
             HOME SLIDER
        ===================================================== -->
        <section id="aa-slider">
            <div class="aa-slider-area">
                <div
                    id="homeSlider"
                    class="carousel slide"
                    data-ride="carousel"
                    data-interval="4000"
                >
                    <!-- DOTS -->
                    <ol v-if="banners.length > 1" class="carousel-indicators">
                        <li
                            v-for="(banner, index) in banners"
                            :key="'dot-' + banner.id"
                            data-target="#homeSlider"
                            :data-slide-to="index"
                            :class="{ active: index === 0 }"
                        ></li>
                    </ol>

                    <!-- SLIDES -->
                    <div class="carousel-inner">
                        <div
                            v-for="(banner, index) in banners"
                            :key="banner.id"
                            class="item"
                            :class="{ active: index === 0 }"
                        >
                            <img
                                class="banner-image"
                                :src="getImage(banner.image)"
                                :alt="banner.name"
                                @error="imageError"
                            />

                            <div
                                v-if="banner.name || banner.text"
                                class="banner-text"
                            >
                                <h2 v-if="banner.name">
                                    {{ banner.name }}
                                </h2>

                                <p v-if="banner.text">
                                    {{ banner.text }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIOUS -->
                    <a
                        v-if="banners.length > 1"
                        class="left carousel-control"
                        href="#homeSlider"
                        data-slide="prev"
                    >
                        <span class="fa fa-angle-left"></span>
                    </a>

                    <!-- NEXT -->
                    <a
                        v-if="banners.length > 1"
                        class="right carousel-control"
                        href="#homeSlider"
                        data-slide="next"
                    >
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
        </section>

        <!-- =====================================================
             PROMO / HOME CATEGORIES
        ===================================================== -->
        <section id="aa-promo">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-promo-area">
                            <div class="row">
                                <div class="col-md-12 no-padding">
                                    <div class="aa-promo-right">
                                        <div
                                            v-for="category in categories"
                                            :key="'promo-' + category.id"
                                            class="aa-single-promo-right"
                                        >
                                            <div class="aa-promo-banner">
                                                <img
                                                    :src="
                                                        getCategoryImage(
                                                            category.image,
                                                        )
                                                    "
                                                    :alt="category.name"
                                                    @error="categoryImageError"
                                                />

                                                <div class="aa-prom-content">
                                                    <h4>
                                                        <router-link
                                                            :to="`/category/${category.id}`"
                                                        >
                                                            {{ category.name }}
                                                        </router-link>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             CATEGORY PRODUCTS
        ===================================================== -->
        <section id="aa-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="aa-product-area">
                                <div class="aa-product-inner">
                                    <!-- PRODUCT CATEGORY TABS -->
                                    <ul class="nav nav-tabs aa-products-tab">
                                        <li
                                            v-for="(
                                                category, index
                                            ) in categories"
                                            :key="'tab-' + category.id"
                                            :class="{ active: index === 0 }"
                                        >
                                            <a
                                                :href="`#cat${category.id}`"
                                                data-toggle="tab"
                                            >
                                                {{ category.name }}
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- TAB CONTENT -->
                                    <div class="tab-content">
                                        <div
                                            v-for="(
                                                category, index
                                            ) in categories"
                                            :key="'content-' + category.id"
                                            :id="`cat${category.id}`"
                                            class="tab-pane fade"
                                            :class="{
                                                'in active': index === 0,
                                            }"
                                        >
                                            <ul class="aa-product-catg">
                                                <!-- PRODUCTS -->
                                                <li
                                                    v-for="product in getCategoryProducts(
                                                        category.id,
                                                    )"
                                                    :key="
                                                        'product-' + product.id
                                                    "
                                                >
                                                    <figure>
                                                        <!-- PRODUCT IMAGE -->
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
                                                                :alt="
                                                                    product.name
                                                                "
                                                                @error="
                                                                    productImageError
                                                                "
                                                            />
                                                        </router-link>

                                                        <!-- STOCK STATUS -->
                                                        <span
                                                            class="product-stock-status"
                                                            :class="
                                                                isProductInStock(
                                                                    product,
                                                                    'category',
                                                                )
                                                                    ? 'in-stock'
                                                                    : 'out-of-stock'
                                                            "
                                                        >
                                                            {{
                                                                isProductInStock(
                                                                    product,
                                                                    "category",
                                                                )
                                                                    ? "In Stock"
                                                                    : "Out of Stock"
                                                            }}
                                                        </span>

                                                        <!-- ADD TO CART -->
                                                        <a
                                                            v-if="
                                                                isProductInStock(
                                                                    product,
                                                                    'category',
                                                                )
                                                            "
                                                            class="aa-add-card-btn"
                                                            href="javascript:void(0)"
                                                            @click="
                                                                addToCart(
                                                                    product,
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="fa fa-shopping-cart"
                                                            ></span>
                                                            Add To Cart
                                                        </a>

                                                        <!-- OUT OF STOCK BUTTON -->
                                                        <a
                                                            v-else
                                                            class="aa-add-card-btn out-of-stock-btn"
                                                            href="javascript:void(0)"
                                                            @click.prevent
                                                        >
                                                            <span
                                                                class="fa fa-ban"
                                                            ></span>
                                                            Out of Stock
                                                        </a>

                                                        <!-- PRODUCT DETAILS -->
                                                        <figcaption>
                                                            <h4
                                                                class="aa-product-title"
                                                            >
                                                                <router-link
                                                                    :to="`/product/${product.slug}`"
                                                                >
                                                                    {{
                                                                        product.name
                                                                    }}
                                                                </router-link>
                                                            </h4>

                                                            <span
                                                                class="aa-product-price"
                                                            >
                                                                Rs
                                                                {{
                                                                    getProductPrice(
                                                                        product,
                                                                    )
                                                                }}
                                                            </span>

                                                            <span
                                                                v-if="
                                                                    getProductMrp(
                                                                        product,
                                                                    )
                                                                "
                                                                class="aa-product-price"
                                                            >
                                                                <del>
                                                                    Rs
                                                                    {{
                                                                        getProductMrp(
                                                                            product,
                                                                        )
                                                                    }}
                                                                </del>
                                                            </span>
                                                        </figcaption>
                                                    </figure>
                                                </li>

                                                <!-- NO PRODUCTS -->
                                                <li
                                                    v-if="
                                                        getCategoryProducts(
                                                            category.id,
                                                        ).length === 0
                                                    "
                                                >
                                                    <figure>
                                                        No data found
                                                    </figure>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
            FEATURED / TRENDING / DISCOUNTED PRODUCTS
        ===================================================== -->
        <section id="aa-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="aa-product-area">
                                <div class="aa-product-inner">
                                    <!-- PRODUCT TYPE TABS -->
                                    <ul class="nav nav-tabs aa-products-tab">
                                        <li class="active">
                                            <a
                                                href="#featured-products"
                                                data-toggle="tab"
                                            >
                                                Featured
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                href="#trending-products"
                                                data-toggle="tab"
                                            >
                                                Trending
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                href="#discounted-products"
                                                data-toggle="tab"
                                            >
                                                Discounted
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- TAB CONTENT -->
                                    <div class="tab-content">
                                        <!-- =================================================
                                            FEATURED PRODUCTS
                                        ================================================== -->
                                        <div
                                            id="featured-products"
                                            class="tab-pane fade in active"
                                        >
                                            <ul class="aa-product-catg">
                                                <li
                                                    v-for="product in featuredProducts"
                                                    :key="
                                                        'featured-' + product.id
                                                    "
                                                >
                                                    <figure>
                                                        <!-- IMAGE -->
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
                                                                :alt="
                                                                    product.name
                                                                "
                                                                @error="
                                                                    productImageError
                                                                "
                                                            />
                                                        </router-link>

                                                        <!-- STOCK STATUS -->
                                                        <span
                                                            class="product-stock-status"
                                                            :class="
                                                                isProductInStock(
                                                                    product,
                                                                    'featured',
                                                                )
                                                                    ? 'in-stock'
                                                                    : 'out-of-stock'
                                                            "
                                                        >
                                                            {{
                                                                isProductInStock(
                                                                    product,
                                                                    "featured",
                                                                )
                                                                    ? "In Stock"
                                                                    : "Out of Stock"
                                                            }}
                                                        </span>

                                                        <!-- ADD TO CART -->
                                                        <a
                                                            v-if="
                                                                isProductInStock(
                                                                    product,
                                                                    'featured',
                                                                )
                                                            "
                                                            class="aa-add-card-btn"
                                                            href="javascript:void(0)"
                                                            @click="
                                                                addToCart(
                                                                    product,
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="fa fa-shopping-cart"
                                                            ></span>

                                                            Add To Cart
                                                        </a>

                                                        <!-- OUT OF STOCK -->
                                                        <a
                                                            v-else
                                                            class="aa-add-card-btn out-of-stock-btn"
                                                            href="javascript:void(0)"
                                                            @click.prevent
                                                        >
                                                            <span
                                                                class="fa fa-ban"
                                                            ></span>

                                                            Out of Stock
                                                        </a>

                                                        <!-- DETAILS -->
                                                        <figcaption>
                                                            <h4
                                                                class="aa-product-title"
                                                            >
                                                                <router-link
                                                                    :to="`/product/${product.slug}`"
                                                                >
                                                                    {{
                                                                        product.name
                                                                    }}
                                                                </router-link>
                                                            </h4>

                                                            <span
                                                                class="aa-product-price"
                                                            >
                                                                Rs
                                                                {{
                                                                    getFeaturedProductPrice(
                                                                        product,
                                                                    )
                                                                }}
                                                            </span>

                                                            <span
                                                                v-if="
                                                                    getFeaturedProductMrp(
                                                                        product,
                                                                    )
                                                                "
                                                                class="aa-product-price"
                                                            >
                                                                <del>
                                                                    Rs
                                                                    {{
                                                                        getFeaturedProductMrp(
                                                                            product,
                                                                        )
                                                                    }}
                                                                </del>
                                                            </span>
                                                        </figcaption>
                                                    </figure>
                                                </li>

                                                <!-- NO DATA -->
                                                <li
                                                    v-if="
                                                        featuredProducts.length ===
                                                        0
                                                    "
                                                >
                                                    <figure>
                                                        No data found
                                                    </figure>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- =================================================
                                            TRENDING PRODUCTS
                                        ================================================== -->
                                        <div
                                            id="trending-products"
                                            class="tab-pane fade"
                                        >
                                            <ul class="aa-product-catg">
                                                <li
                                                    v-for="product in trendingProducts"
                                                    :key="
                                                        'trending-' + product.id
                                                    "
                                                >
                                                    <figure>
                                                        <!-- IMAGE -->
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
                                                                :alt="
                                                                    product.name
                                                                "
                                                                @error="
                                                                    productImageError
                                                                "
                                                            />
                                                        </router-link>

                                                        <!-- STOCK STATUS -->
                                                        <span
                                                            class="product-stock-status"
                                                            :class="
                                                                isProductInStock(
                                                                    product,
                                                                    'trending',
                                                                )
                                                                    ? 'in-stock'
                                                                    : 'out-of-stock'
                                                            "
                                                        >
                                                            {{
                                                                isProductInStock(
                                                                    product,
                                                                    "trending",
                                                                )
                                                                    ? "In Stock"
                                                                    : "Out of Stock"
                                                            }}
                                                        </span>

                                                        <!-- ADD TO CART -->
                                                        <a
                                                            v-if="
                                                                isProductInStock(
                                                                    product,
                                                                    'trending',
                                                                )
                                                            "
                                                            class="aa-add-card-btn"
                                                            href="javascript:void(0)"
                                                            @click="
                                                                addToCart(
                                                                    product,
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="fa fa-shopping-cart"
                                                            ></span>

                                                            Add To Cart
                                                        </a>

                                                        <!-- OUT OF STOCK -->
                                                        <a
                                                            v-else
                                                            class="aa-add-card-btn out-of-stock-btn"
                                                            href="javascript:void(0)"
                                                            @click.prevent
                                                        >
                                                            <span
                                                                class="fa fa-ban"
                                                            ></span>

                                                            Out of Stock
                                                        </a>

                                                        <!-- DETAILS -->
                                                        <figcaption>
                                                            <h4
                                                                class="aa-product-title"
                                                            >
                                                                <router-link
                                                                    :to="`/product/${product.slug}`"
                                                                >
                                                                    {{
                                                                        product.name
                                                                    }}
                                                                </router-link>
                                                            </h4>

                                                            <span
                                                                class="aa-product-price"
                                                            >
                                                                Rs
                                                                {{
                                                                    getTrendingProductPrice(
                                                                        product,
                                                                    )
                                                                }}
                                                            </span>

                                                            <span
                                                                v-if="
                                                                    getTrendingProductMrp(
                                                                        product,
                                                                    )
                                                                "
                                                                class="aa-product-price"
                                                            >
                                                                <del>
                                                                    Rs
                                                                    {{
                                                                        getTrendingProductMrp(
                                                                            product,
                                                                        )
                                                                    }}
                                                                </del>
                                                            </span>
                                                        </figcaption>
                                                    </figure>
                                                </li>

                                                <!-- NO DATA -->
                                                <li
                                                    v-if="
                                                        trendingProducts.length ===
                                                        0
                                                    "
                                                >
                                                    <figure>
                                                        No data found
                                                    </figure>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- =================================================
                                            DISCOUNTED PRODUCTS
                                        ================================================== -->
                                        <div
                                            id="discounted-products"
                                            class="tab-pane fade"
                                        >
                                            <ul class="aa-product-catg">
                                                <li
                                                    v-for="product in discountedProducts"
                                                    :key="
                                                        'discounted-' +
                                                        product.id
                                                    "
                                                >
                                                    <figure>
                                                        <!-- IMAGE -->
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
                                                                :alt="
                                                                    product.name
                                                                "
                                                                @error="
                                                                    productImageError
                                                                "
                                                            />
                                                        </router-link>

                                                        <!-- STOCK STATUS -->
                                                        <span
                                                            class="product-stock-status"
                                                            :class="
                                                                isProductInStock(
                                                                    product,
                                                                    'discounted',
                                                                )
                                                                    ? 'in-stock'
                                                                    : 'out-of-stock'
                                                            "
                                                        >
                                                            {{
                                                                isProductInStock(
                                                                    product,
                                                                    "discounted",
                                                                )
                                                                    ? "In Stock"
                                                                    : "Out of Stock"
                                                            }}
                                                        </span>

                                                        <!-- ADD TO CART -->
                                                        <a
                                                            v-if="
                                                                isProductInStock(
                                                                    product,
                                                                    'discounted',
                                                                )
                                                            "
                                                            class="aa-add-card-btn"
                                                            href="javascript:void(0)"
                                                            @click="
                                                                addToCart(
                                                                    product,
                                                                )
                                                            "
                                                        >
                                                            <span
                                                                class="fa fa-shopping-cart"
                                                            ></span>

                                                            Add To Cart
                                                        </a>

                                                        <!-- OUT OF STOCK -->
                                                        <a
                                                            v-else
                                                            class="aa-add-card-btn out-of-stock-btn"
                                                            href="javascript:void(0)"
                                                            @click.prevent
                                                        >
                                                            <span
                                                                class="fa fa-ban"
                                                            ></span>

                                                            Out of Stock
                                                        </a>

                                                        <!-- DETAILS -->
                                                        <figcaption>
                                                            <h4
                                                                class="aa-product-title"
                                                            >
                                                                <router-link
                                                                    :to="`/product/${product.slug}`"
                                                                >
                                                                    {{
                                                                        product.name
                                                                    }}
                                                                </router-link>
                                                            </h4>

                                                            <span
                                                                class="aa-product-price"
                                                            >
                                                                Rs
                                                                {{
                                                                    getDiscountedProductPrice(
                                                                        product,
                                                                    )
                                                                }}
                                                            </span>

                                                            <span
                                                                v-if="
                                                                    getDiscountedProductMrp(
                                                                        product,
                                                                    )
                                                                "
                                                                class="aa-product-price"
                                                            >
                                                                <del>
                                                                    Rs
                                                                    {{
                                                                        getDiscountedProductMrp(
                                                                            product,
                                                                        )
                                                                    }}
                                                                </del>
                                                            </span>
                                                        </figcaption>
                                                    </figure>
                                                </li>

                                                <!-- NO DATA -->
                                                <li
                                                    v-if="
                                                        discountedProducts.length ===
                                                        0
                                                    "
                                                >
                                                    <figure>
                                                        No data found
                                                    </figure>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
            SUPPORT SECTION
        ===================================================== -->
        <section id="aa-support">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-support-area">
                            <!-- FREE SHIPPING -->
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="aa-support-single">
                                    <span class="fa fa-truck"></span>

                                    <h4>FREE SHIPPING</h4>

                                    <p>Enjoy free shipping on your orders.</p>
                                </div>
                            </div>

                            <!-- MONEY BACK -->
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="aa-support-single">
                                    <span class="fa fa-clock-o"></span>

                                    <h4>30 DAYS MONEY BACK</h4>

                                    <p>
                                        Shop with confidence with our 30 days
                                        money back guarantee.
                                    </p>
                                </div>
                            </div>

                            <!-- SUPPORT -->
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="aa-support-single">
                                    <span class="fa fa-phone"></span>

                                    <h4>SUPPORT 24/7</h4>

                                    <p>
                                        Our support team is available 24/7 to
                                        help you.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
            CLIENT BRANDS
        ===================================================== -->
        <section id="aa-client-brand">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="aa-client-brand-area">
                            <ul class="aa-client-brand-slider">
                                <!-- BRANDS FROM API -->
                                <li
                                    v-for="brand in brands"
                                    :key="'brand-' + brand.id"
                                >
                                    <a href="javascript:void(0)">
                                        <img
                                            :src="getBrandImage(brand.image)"
                                            :alt="brand.name"
                                            @error="brandImageError"
                                        />
                                    </a>
                                </li>

                                <!-- NO BRANDS -->
                                <li v-if="brands.length === 0">
                                    <span>No brands found</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =====================================================
             LOADING
        ===================================================== -->
        <div v-if="loading" class="banner-loading">
            <div id="wpf-loader-two">
                <div class="wpf-loader-two-inner">
                    <span>Loading</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
    name: "Home",

    // =====================================================
    // DATA
    // =====================================================
    data() {
        return {
            // HOME BANNERS
            banners: [],

            // HOME CATEGORIES
            categories: [],

            // CATEGORY PRODUCTS
            homeCategoriesProduct: {},

            // CATEGORY PRODUCT ATTRIBUTES
            homeProductAttr: {},

            // FEATURED
            featuredProducts: [],
            featuredProductAttr: {},

            // TRENDING
            trendingProducts: [],
            trendingProductAttr: {},

            // ACTIVE PRODUCT TAB
            activeProductTab: "featured",

            // DISCOUNTED
            discountedProducts: [],
            discountedProductAttr: {},

            // BRANDS
            brands: [],

            // LOADING
            loading: false,

            // MOBILE MENU
            menuOpen: false,
        };
    },

    computed: {
        ...mapGetters(["getUserToken", "getGuestId", "getFrontendUser"]),
    },

    // =====================================================
    // METHODS
    // =====================================================
    methods: {
        // ==========================================
        // BANNER IMAGE
        // ==========================================
        getImage(image) {
            if (!image) {
                return "";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // ==========================================
        // CATEGORY IMAGE
        // ==========================================
        getCategoryImage(image) {
            if (!image) {
                return "";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // ==========================================
        // PRODUCT IMAGE
        // ==========================================
        getProductImage(image) {
            if (!image) {
                return "";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // ==========================================
        // CATEGORY PRODUCTS
        // ==========================================
        getCategoryProducts(categoryId) {
            return this.homeCategoriesProduct[categoryId] || [];
        },

        // ==========================================
        // PRODUCT ATTRIBUTES
        // ==========================================
        getProductAttributes(product) {
            if (!product || !product.id) {
                return [];
            }

            return this.homeProductAttr[product.id] || [];
        },

        // ==========================================
        // FEATURED PRODUCT ATTRIBUTES
        // ==========================================
        getFeaturedProductAttributes(product) {
            if (!product || !product.id) {
                return [];
            }

            return this.featuredProductAttr[product.id] || [];
        },

        // ==========================================
        // TRENDING PRODUCT ATTRIBUTES
        // ==========================================
        getTrendingProductAttributes(product) {
            if (!product || !product.id) {
                return [];
            }

            return this.trendingProductAttr[product.id] || [];
        },

        // ==========================================
        // DISCOUNTED PRODUCT ATTRIBUTES
        // ==========================================
        getDiscountedProductAttributes(product) {
            if (!product || !product.id) {
                return [];
            }

            return this.discountedProductAttr[product.id] || [];
        },

        // =====================================================
        // STOCK
        // =====================================================
        isProductInStock(product, type = "category") {
            if (!product || !product.id) {
                return false;
            }

            let attributes = [];

            if (type === "featured") {
                attributes = this.getFeaturedProductAttributes(product);
            } else if (type === "trending") {
                attributes = this.getTrendingProductAttributes(product);
            } else if (type === "discounted") {
                attributes = this.getDiscountedProductAttributes(product);
            } else {
                attributes = this.getProductAttributes(product);
            }

            // ==========================================
            // ATTRIBUTE STOCK
            // ==========================================
            if (attributes && attributes.length > 0) {
                return attributes.some((attribute) => {
                    return Number(attribute.quantity || 0) > 0;
                });
            }

            // ==========================================
            // DIRECT PRODUCT STOCK
            // ==========================================
            if (product.quantity !== undefined && product.quantity !== null) {
                return Number(product.quantity) > 0;
            }

            if (product.stock !== undefined && product.stock !== null) {
                return Number(product.stock) > 0;
            }

            // ==========================================
            // NO STOCK INFORMATION
            // ==========================================
            return false;
        },

        // ==========================================
        // PRODUCT PRICE
        // ==========================================
        getProductPrice(product) {
            if (
                product &&
                product.price !== undefined &&
                product.price !== null
            ) {
                return product.price;
            }

            const attributes = this.getProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].price || 0;
            }

            return 0;
        },

        // ==========================================
        // PRODUCT MRP
        // ==========================================
        getProductMrp(product) {
            if (
                product &&
                product.maximum_retail_price !== undefined &&
                product.maximum_retail_price !== null
            ) {
                return product.maximum_retail_price;
            }

            const attributes = this.getProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].mrp || 0;
            }

            return 0;
        },

        // ==========================================
        // FEATURED PRICE
        // ==========================================
        getFeaturedProductPrice(product) {
            const attributes = this.getFeaturedProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].price || 0;
            }

            return product.price || 0;
        },

        // ==========================================
        // FEATURED MRP
        // ==========================================
        getFeaturedProductMrp(product) {
            const attributes = this.getFeaturedProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].mrp || 0;
            }

            return product.maximum_retail_price || 0;
        },

        // ==========================================
        // TRENDING PRICE
        // ==========================================
        getTrendingProductPrice(product) {
            const attributes = this.getTrendingProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].price || 0;
            }

            return product.price || 0;
        },

        // ==========================================
        // TRENDING MRP
        // ==========================================
        getTrendingProductMrp(product) {
            const attributes = this.getTrendingProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].mrp || 0;
            }

            return product.maximum_retail_price || 0;
        },

        // ==========================================
        // DISCOUNTED PRICE
        // ==========================================
        getDiscountedProductPrice(product) {
            const attributes = this.getDiscountedProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].price || 0;
            }

            return product.price || 0;
        },

        // ==========================================
        // DISCOUNTED MRP
        // ==========================================
        getDiscountedProductMrp(product) {
            const attributes = this.getDiscountedProductAttributes(product);

            if (attributes.length > 0) {
                return attributes[0].mrp || 0;
            }

            return product.maximum_retail_price || 0;
        },

        // =====================================================
        // ADD TO CART
        // =====================================================
        async addToCart(product) {
            try {
                // ==========================================
                // PRODUCT ATTRIBUTES
                // ==========================================
                const attributes = this.getProductAttributes(product);

                if (!attributes || attributes.length === 0) {
                    if (this.$toast) {
                        this.$toast.error(
                            "Product stock information not found.",
                            {
                                position: "top-right",
                                autoClose: 5000,
                            },
                        );
                    }

                    return;
                }

                // ==========================================
                // CHECK STOCK
                // ==========================================
                if (!this.isProductInStock(product, "category")) {
                    if (this.$toast) {
                        this.$toast.error("Product is out of stock.", {
                            position: "top-right",
                            autoClose: 5000,
                        });
                    }

                    return;
                }

                // ==========================================
                // FIRST ATTRIBUTE
                // ==========================================
                const attribute = attributes[0];

                // ==========================================
                // GET USER TOKEN
                // ==========================================
                const userToken = this.getUserToken;

                // ==========================================
                // GET GUEST ID
                // ==========================================
                let guestId = this.getGuestId;

                // ==========================================
                // CREATE GUEST ID
                // ==========================================
                if (!userToken && !guestId) {
                    guestId =
                        "guest_" +
                        Date.now() +
                        "_" +
                        Math.random().toString(36).substring(2, 10);

                    await this.$store.dispatch("setGuestId", guestId);
                }

                // ==========================================
                // API DATA
                // ==========================================
                const data = {
                    product_id: product.id,

                    size_id: attribute.size_id,

                    color_id: attribute.color_id,

                    quantity: 1,

                    guest_id: guestId,
                };

                // ==========================================
                // API HEADERS
                // ==========================================
                const headers = {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                };

                if (userToken) {
                    headers.Authorization = `Bearer ${userToken}`;
                }

                // ==========================================
                // API CALL
                // ==========================================
                const response = await axios.post("/api/add-to-cart", data, {
                    headers,
                });

                // ==========================================
                // SUCCESS
                // ==========================================
                if (response.data.status) {
                    if (this.$toast) {
                        this.$toast.success(
                            "Product added to cart successfully!",
                            {
                                position: "top-right",
                                autoClose: 5000,
                            },
                        );
                    }

                    window.dispatchEvent(new Event("cart-updated"));
                } else {
                    if (this.$toast) {
                        this.$toast.error(
                            response.data.message ||
                                "Unable to add product to cart",
                            {
                                position: "top-right",
                                autoClose: 5000,
                            },
                        );
                    }
                }
            } catch (error) {
                if (this.$toast) {
                    this.$toast.error(
                        error.response?.data?.data ||
                            error.response?.data?.message ||
                            "Something went wrong",
                        {
                            position: "top-right",
                            autoClose: 5000,
                        },
                    );
                }
            }
        },

        // ==========================================
        // CATEGORY IMAGE ERROR
        // ==========================================
        categoryImageError(event) {},

        // ==========================================
        // BANNER IMAGE ERROR
        // ==========================================
        imageError(event) {},

        // ==========================================
        // PRODUCT IMAGE ERROR
        // ==========================================
        productImageError(event) {},

        // ==========================================
        // BRAND IMAGE
        // ==========================================
        getBrandImage(image) {
            if (!image) {
                return "";
            }

            if (image.startsWith("http://") || image.startsWith("https://")) {
                return image;
            }

            return `http://127.0.0.1:8000/${image}`;
        },

        // ==========================================
        // HOME DATA
        // ==========================================
        async getHomeData() {
            this.loading = true;

            try {
                const response = await axios.get("/api/home");

                const result = response.data?.result || {};

                // =====================================
                // BANNERS
                // =====================================
                this.banners = result.home_banner || [];

                // =====================================
                // CATEGORIES
                // =====================================
                this.categories = result.home_categories || [];

                // =====================================
                // CATEGORY PRODUCTS
                // =====================================
                this.homeCategoriesProduct =
                    result.home_categories_product || {};

                // =====================================
                // CATEGORY ATTRIBUTES
                // =====================================
                this.homeProductAttr = result.home_product_attr || {};

                // =====================================
                // FEATURED PRODUCTS
                // =====================================
                this.featuredProducts = result.home_featured_product || [];

                // =====================================
                // FEATURED ATTRIBUTES
                // =====================================
                this.featuredProductAttr =
                    result.home_featured_product_attr || {};

                // =====================================
                // TRENDING PRODUCTS
                // =====================================
                this.trendingProducts = result.home_tranding_product || [];

                // =====================================
                // TRENDING ATTRIBUTES
                // =====================================
                this.trendingProductAttr =
                    result.home_tranding_product_attr || {};

                // =====================================
                // DISCOUNTED PRODUCTS
                // =====================================
                this.discountedProducts = result.home_discounted_product || [];

                // =====================================
                // DISCOUNTED ATTRIBUTES
                // =====================================
                this.discountedProductAttr =
                    result.home_discounted_product_attr || {};

                // =====================================
                // BRANDS
                // =====================================
                this.brands = result.home_brand || [];
            } catch (error) {
                this.banners = [];
                this.categories = [];
                this.homeCategoriesProduct = {};
                this.homeProductAttr = {};

                this.featuredProducts = [];
                this.featuredProductAttr = {};

                this.trendingProducts = [];
                this.trendingProductAttr = {};

                this.discountedProducts = [];
                this.discountedProductAttr = {};

                this.brands = [];
            } finally {
                this.loading = false;
            }
        },
    },

    // =====================================================
    // MOUNTED
    // =====================================================
    async mounted() {
        this.getHomeData();

        if (!this.getUserToken && !this.getGuestId) {
            await this.$store.dispatch("getOrCreateGuestId");
        }
    },
};
</script>

<style scoped>
/* =====================================================
   SLIDER
===================================================== */

#aa-slider {
    width: 100%;
    margin: 0;
    padding: 0;
}

.aa-slider-area {
    width: 100%;
    margin: 0;
    padding: 0;
}

/* =====================================================
   CAROUSEL
===================================================== */

#homeSlider {
    width: 100%;
    position: relative;
    overflow: hidden;
}

#homeSlider .carousel-inner {
    width: 100%;
    overflow: hidden;
}

#homeSlider .item {
    width: 100%;
    position: relative;
    overflow: hidden;
}

/* =====================================================
   BANNER IMAGE
===================================================== */

.banner-image {
    width: 100%;
    height: 350px !important;

    display: block;

    object-fit: contain;
    object-position: center center;

    background: #ffffff;
}

/* =====================================================
   BANNER TEXT
===================================================== */

.banner-text {
    position: absolute;

    left: 8%;
    bottom: 15%;

    z-index: 10;

    max-width: 500px;

    color: #ffffff;

    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
}

.banner-text h2 {
    margin: 0 0 10px;

    font-size: 36px;
    font-weight: 700;
}

.banner-text p {
    margin: 0;

    font-size: 18px;
}

/* =====================================================
   ARROWS
===================================================== */

#homeSlider .carousel-control {
    position: absolute !important;

    top: 0 !important;
    bottom: 0 !important;

    width: 8%;

    opacity: 1;

    background: transparent !important;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    z-index: 50;

    text-shadow: none;
}

#homeSlider .carousel-control.left {
    left: 0 !important;
}

#homeSlider .carousel-control.right {
    right: 0 !important;
}

#homeSlider .carousel-control .fa {
    width: 45px;
    height: 45px;

    display: flex !important;

    align-items: center !important;
    justify-content: center !important;

    border-radius: 50%;

    background: rgba(0, 0, 0, 0.55);

    color: #ffffff;

    font-size: 28px;

    line-height: 45px;

    text-shadow: none;

    transition: all 0.2s ease;
}

#homeSlider .carousel-control:hover .fa {
    background: rgba(0, 0, 0, 0.85);
}

/* =====================================================
   DOTS
===================================================== */

#homeSlider .carousel-indicators {
    position: absolute;

    bottom: 10px;

    left: 50%;

    z-index: 60;

    margin: 0;

    padding: 0;

    transform: translateX(-50%);
}

#homeSlider .carousel-indicators li {
    width: 10px;
    height: 10px;

    margin: 0 4px;

    border-radius: 50%;
}

#homeSlider .carousel-indicators .active {
    width: 12px;
    height: 12px;
}

/* =====================================================
   PRODUCT STOCK STATUS
===================================================== */

#aa-product .aa-product-catg li figure {
    position: relative;
}

.product-stock-status {
    position: absolute;

    top: 10px;
    left: 10px;

    z-index: 10;

    padding: 6px 12px;

    border-radius: 4px;

    font-size: 12px;

    font-weight: 600;

    line-height: 1;

    text-transform: uppercase;
}

.product-stock-status.in-stock {
    background: #28a745;
    color: #ffffff;
}

.product-stock-status.out-of-stock {
    background: #dc3545;
    color: #ffffff;
}

/* =====================================================
   OUT OF STOCK BUTTON
===================================================== */

#aa-product .aa-product-catg li .out-of-stock-btn {
    background: #999999 !important;
    color: #ffffff !important;

    cursor: not-allowed !important;

    pointer-events: none;

    opacity: 0.8;
}

/* =====================================================
   BRAND IMAGES
===================================================== */

#aa-client-brand .aa-client-brand-slider li {
    display: flex;
    align-items: center;
    justify-content: center;

    height: 100px;
}

#aa-client-brand .aa-client-brand-slider li a {
    display: flex;

    align-items: center;
    justify-content: center;

    width: 150px;
    height: 80px;
}

#aa-client-brand .aa-client-brand-slider li img {
    width: 150px !important;
    height: 80px !important;

    object-fit: contain;
    object-position: center;

    display: block;
}

/* =====================================================
   LOADING
===================================================== */

.banner-loading {
    width: 100%;

    text-align: center;

    padding: 30px;

    font-size: 18px;
}

/* =====================================================
   NO BANNER
===================================================== */

.no-banner {
    width: 100%;

    height: 350px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #f5f5f5;

    color: #777;

    font-size: 20px;
}

/* =====================================================
   MOBILE SLIDER
===================================================== */

@media (max-width: 767px) {
    #homeSlider .item {
        width: 100%;
    }

    .banner-image {
        width: 100%;
        height: 220px !important;

        object-fit: contain;
        object-position: center center;

        background: #ffffff;
    }

    #homeSlider .carousel-control {
        width: 14% !important;
    }

    #homeSlider .carousel-control .fa {
        width: 35px;
        height: 35px;

        font-size: 20px;

        line-height: 35px;
    }

    .banner-text {
        left: 5%;
        bottom: 12%;

        max-width: 70%;
    }

    .banner-text h2 {
        font-size: 20px;
    }

    .banner-text p {
        font-size: 13px;
    }

    #homeSlider .carousel-indicators {
        bottom: 5px;
    }
}

/* =====================================================
   MENU
===================================================== */

#menu {
    width: 100%;
    margin: 0;
    padding: 0;
}

#menu .menu-area {
    width: 100%;
}

#menu .navbar {
    margin-bottom: 0;
}

/* =====================================================
   DESKTOP CATEGORY DROPDOWN
===================================================== */

@media (min-width: 768px) {
    #menu .navbar-nav > li.dropdown:hover > .dropdown-menu {
        display: block;
    }
}

/* =====================================================
   MOBILE MENU
===================================================== */

@media (max-width: 767px) {
    #menu .navbar-collapse {
        border-top: 1px solid #eeeeee;
    }
}

/* =====================================================
   PRODUCT IMAGES
===================================================== */

#aa-product .aa-product-catg li figure {
    width: 100%;
}

#aa-product .aa-product-catg li .aa-product-img {
    width: 100%;
    height: 250px !important;

    display: flex !important;

    align-items: center;
    justify-content: center;

    overflow: hidden;

    background: #ffffff;
}

#aa-product .aa-product-catg li .aa-product-img img {
    width: 100% !important;
    height: 250px !important;

    object-fit: contain !important;
    object-position: center center;

    display: block;
}
</style>
