<template>
    <!-- =========================================================
         CATEGORY HEADER BANNER
    ========================================================== -->

    <section id="aa-catg-head-banner">
        <div class="aa-catg-head-banner-area">
            <div class="container"></div>
        </div>
    </section>

    <!-- =========================================================
         PRODUCT DETAILS
    ========================================================== -->

    <section id="aa-product-details">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-product-details-area">
                        <!-- =================================================
                             LOADING
                        ================================================== -->

                        <div
                            v-if="loading"
                            class="text-center"
                            style="padding: 80px 0"
                        >
                            <i
                                class="fa fa-spinner fa-spin"
                                style="font-size: 40px"
                            ></i>

                            <h4>Loading product...</h4>
                        </div>

                        <!-- =================================================
                             PRODUCT
                        ================================================== -->

                        <div
                            v-else-if="product"
                            class="aa-product-details-content"
                        >
                            <div class="row">
                                <!-- =================================================
                                     PRODUCT IMAGE
                                ================================================= -->

                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <div class="aa-product-view-slider">
                                        <div
                                            id="demo-1"
                                            class="simpleLens-gallery-container"
                                        >
                                            <!-- BIG IMAGE -->

                                            <div class="simpleLens-container">
                                                <div
                                                    class="simpleLens-big-image-container"
                                                >
                                                    <a
                                                        class="simpleLens-lens-image"
                                                        :data-lens-image="
                                                            selectedImage
                                                        "
                                                    >
                                                        <img
                                                            :src="selectedImage"
                                                            class="simpleLens-big-image"
                                                            :alt="product.name"
                                                            @error="
                                                                productImageError
                                                            "
                                                        />
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- THUMBNAILS -->

                                            <div
                                                class="simpleLens-thumbnails-container"
                                            >
                                                <!-- MAIN PRODUCT IMAGE -->

                                                <a
                                                    href="javascript:void(0)"
                                                    class="simpleLens-thumbnail-wrapper"
                                                    @click="
                                                        changeImage(
                                                            productImage(
                                                                product.image,
                                                            ),
                                                        )
                                                    "
                                                >
                                                    <img
                                                        :src="
                                                            productImage(
                                                                product.image,
                                                            )
                                                        "
                                                        width="70"
                                                        :alt="product.name"
                                                        @error="
                                                            productImageError
                                                        "
                                                    />
                                                </a>

                                                <!-- OTHER PRODUCT IMAGES -->

                                                <a
                                                    v-for="image in product_images"
                                                    :key="image.id"
                                                    href="javascript:void(0)"
                                                    class="simpleLens-thumbnail-wrapper"
                                                    @click="
                                                        changeImage(
                                                            productImage(
                                                                image.image,
                                                            ),
                                                        )
                                                    "
                                                >
                                                    <img
                                                        :src="
                                                            productImage(
                                                                image.image,
                                                            )
                                                        "
                                                        width="70"
                                                        :alt="product.name"
                                                        @error="
                                                            productImageError
                                                        "
                                                    />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- =================================================
                                     PRODUCT CONTENT
                                ================================================== -->

                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <div class="aa-product-view-content">
                                        <!-- PRODUCT NAME -->

                                        <h3>
                                            {{ product.name }}
                                        </h3>

                                        <!-- =================================================
                                             PRICE
                                        ================================================== -->

                                        <div class="aa-price-block">
                                            <span class="aa-product-view-price">
                                                Rs
                                                {{
                                                    selectedAttribute
                                                        ? Number(
                                                              selectedAttribute.price ||
                                                                  0,
                                                          ).toFixed(2)
                                                        : "0.00"
                                                }}
                                            </span>

                                            &nbsp;&nbsp;

                                            <span class="aa-product-view-price">
                                                <del>
                                                    Rs
                                                    {{
                                                        selectedAttribute
                                                            ? Number(
                                                                  selectedAttribute.maximum_retail_price ||
                                                                      0,
                                                              ).toFixed(2)
                                                            : "0.00"
                                                    }}
                                                </del>
                                            </span>

                                            <!-- =================================================
                                                 STOCK STATUS
                                            ================================================== -->

                                            <p class="aa-product-avilability">
                                                Availability:

                                                <span
                                                    v-if="isOutOfStock"
                                                    class="out-of-stock-text"
                                                >
                                                    Out of stock
                                                </span>

                                                <span
                                                    v-else
                                                    class="in-stock-text"
                                                >
                                                    In stock
                                                </span>
                                            </p>

                                            <!-- STOCK QUANTITY -->

                                            <p
                                                v-if="selectedAttribute"
                                                class="stock-info"
                                            >
                                                {{
                                                    isOutOfStock
                                                        ? "Currently unavailable"
                                                        : `${availableStock} item(s) available`
                                                }}
                                            </p>

                                            <!-- LEAD TIME -->

                                            <p
                                                v-if="product.lead_time"
                                                class="lead_time"
                                            >
                                                {{ product.lead_time }}
                                            </p>
                                        </div>

                                        <!-- =================================================
                                             SHORT DESCRIPTION
                                        ================================================== -->

                                        <div
                                            class="product-short-description"
                                            v-html="product.short_description"
                                        ></div>

                                        <!-- =================================================
                                             SIZE
                                        ================================================== -->

                                        <div v-if="sizes.length">
                                            <h4>Size</h4>

                                            <div class="aa-prod-view-size">
                                                <a
                                                    v-for="size in sizes"
                                                    :key="size.id"
                                                    href="javascript:void(0)"
                                                    class="size_link"
                                                    :class="{
                                                        active:
                                                            selectedSizeId ===
                                                            Number(size.id),
                                                    }"
                                                    @click="selectSize(size.id)"
                                                >
                                                    {{ size.name }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- =================================================
                                             COLOR
                                        ================================================== -->

                                        <div v-if="availableColors.length">
                                            <h4>Color</h4>

                                            <div class="aa-color-tag">
                                                <a
                                                    v-for="color in availableColors"
                                                    :key="color.id"
                                                    href="javascript:void(0)"
                                                    class="product_color"
                                                    :class="{
                                                        active:
                                                            selectedColorId ===
                                                            Number(color.id),
                                                    }"
                                                    :title="color.name"
                                                    :style="{
                                                        backgroundColor:
                                                            getColorValue(
                                                                color.name,
                                                            ),
                                                    }"
                                                    @click="
                                                        selectColor(color.id)
                                                    "
                                                ></a>
                                            </div>

                                            <!-- SELECTED COLOR NAME -->

                                            <div
                                                v-if="selectedColor"
                                                class="selected-option-text"
                                            >
                                                Selected Color:
                                                <strong>
                                                    {{ selectedColor.name }}
                                                </strong>
                                            </div>
                                        </div>

                                        <!-- =================================================
                                             SELECTED SIZE
                                        ================================================== -->

                                        <div
                                            v-if="selectedSize"
                                            class="selected-option-text"
                                        >
                                            Selected Size:
                                            <strong>
                                                {{ selectedSize.name }}
                                            </strong>
                                        </div>

                                        <!-- =================================================
                                             QUANTITY
                                        ================================================== -->

                                        <div
                                            class="aa-prod-quantity"
                                            v-if="!isOutOfStock"
                                        >
                                            <form @submit.prevent>
                                                <label for="qty">
                                                    Quantity:
                                                </label>

                                                <select
                                                    v-model.number="quantity"
                                                    id="qty"
                                                    name="qty"
                                                    :disabled="isOutOfStock"
                                                >
                                                    <option
                                                        v-for="i in quantityOptions"
                                                        :key="i"
                                                        :value="i"
                                                    >
                                                        {{ i }}
                                                    </option>
                                                </select>
                                            </form>

                                            <p class="aa-prod-category">
                                                Model:

                                                <a href="javascript:void(0)">
                                                    {{ product.model }}
                                                </a>
                                            </p>
                                        </div>

                                        <!-- =================================================
                                             OUT OF STOCK MESSAGE
                                        ================================================== -->

                                        <div
                                            v-if="isOutOfStock"
                                            class="out-of-stock-box"
                                        >
                                            <i class="fa fa-times-circle"></i>

                                            <strong> Out of Stock </strong>

                                            <p>
                                                This selected size/color is
                                                currently unavailable.
                                            </p>
                                        </div>

                                        <!-- =================================================
                                             ADD TO CART
                                        ================================================== -->

                                        <div class="aa-prod-view-bottom">
                                            <a
                                                href="javascript:void(0)"
                                                class="aa-add-to-cart-btn"
                                                :class="{
                                                    disabled:
                                                        cartLoading ||
                                                        isOutOfStock,
                                                }"
                                                @click.prevent="addToCart"
                                            >
                                                <i
                                                    class="fa fa-shopping-cart"
                                                ></i>

                                                <span v-if="cartLoading">
                                                    Adding...
                                                </span>

                                                <span v-else-if="isOutOfStock">
                                                    Out of Stock
                                                </span>

                                                <span v-else>
                                                    Add To Cart
                                                </span>
                                            </a>
                                        </div>

                                        <div
                                            id="add_to_cart_msg"
                                            class="mt-2"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- =================================================
                                 PRODUCT TABS
                            ================================================== -->

                            <div class="aa-product-details-bottom">
                                <ul class="nav nav-tabs" id="myTab2">
                                    <li class="active">
                                        <a
                                            href="#description"
                                            data-toggle="tab"
                                        >
                                            Description
                                        </a>
                                    </li>

                                    <li>
                                        <a
                                            href="#technical_specification"
                                            data-toggle="tab"
                                        >
                                            Technical Specification
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#uses" data-toggle="tab">
                                            Uses
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#warranty" data-toggle="tab">
                                            Warranty
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#review" data-toggle="tab">
                                            Reviews
                                        </a>
                                    </li>
                                </ul>

                                <!-- TAB CONTENT -->

                                <div class="tab-content">
                                    <!-- DESCRIPTION -->

                                    <div
                                        class="tab-pane fade in active"
                                        id="description"
                                    >
                                        <div v-html="product.description"></div>
                                    </div>

                                    <!-- TECHNICAL SPECIFICATION -->

                                    <div
                                        class="tab-pane fade"
                                        id="technical_specification"
                                    >
                                        <div
                                            v-html="
                                                product.technical_specification
                                            "
                                        ></div>
                                    </div>

                                    <!-- USES -->

                                    <div class="tab-pane fade" id="uses">
                                        <div v-html="product.uses"></div>
                                    </div>

                                    <!-- WARRANTY -->

                                    <div class="tab-pane fade" id="warranty">
                                        <div v-html="product.warranty"></div>
                                    </div>

                                    <!-- =================================================
                                         REVIEWS
                                    ================================================== -->

                                    <div class="tab-pane fade" id="review">
                                        <div class="aa-product-review-area">
                                            <!-- REVIEWS -->

                                            <div v-if="product_reviews.length">
                                                <h4>
                                                    {{ product_reviews.length }}

                                                    Review(s) for

                                                    {{ product.name }}
                                                </h4>

                                                <ul class="aa-review-nav">
                                                    <li
                                                        v-for="review in product_reviews"
                                                        :key="review.id"
                                                    >
                                                        <div class="media">
                                                            <div
                                                                class="media-body"
                                                            >
                                                                <h4
                                                                    class="media-heading"
                                                                >
                                                                    <strong>
                                                                        {{
                                                                            review.name
                                                                        }}
                                                                    </strong>

                                                                    -

                                                                    <span>
                                                                        {{
                                                                            formatDate(
                                                                                review.added_on,
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </h4>

                                                                <div
                                                                    class="aa-product-rating"
                                                                >
                                                                    <span
                                                                        class="rating_txt"
                                                                    >
                                                                        {{
                                                                            review.rating
                                                                        }}
                                                                    </span>
                                                                </div>

                                                                <p>
                                                                    {{
                                                                        review.review
                                                                    }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- NO REVIEWS -->

                                            <div v-else>
                                                <h2>No review found</h2>
                                            </div>

                                            <!-- ADD REVIEW -->

                                            <form
                                                class="aa-review-form"
                                                @submit.prevent="submitReview"
                                            >
                                                <h4>Add a review</h4>

                                                <div class="aa-your-rating">
                                                    <p>Your Rating</p>

                                                    <select
                                                        v-model="
                                                            reviewForm.rating
                                                        "
                                                        class="form-control"
                                                        required
                                                    >
                                                        <option value="">
                                                            Select Rating
                                                        </option>

                                                        <option value="Worst">
                                                            Worst
                                                        </option>

                                                        <option value="Bad">
                                                            Bad
                                                        </option>

                                                        <option value="Good">
                                                            Good
                                                        </option>

                                                        <option
                                                            value="Very Good"
                                                        >
                                                            Very Good
                                                        </option>

                                                        <option
                                                            value="Fantastic"
                                                        >
                                                            Fantastic
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label> Your Review </label>

                                                    <textarea
                                                        class="form-control"
                                                        rows="3"
                                                        v-model="
                                                            reviewForm.review
                                                        "
                                                        required
                                                    ></textarea>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="btn btn-default aa-review-submit"
                                                    :disabled="reviewLoading"
                                                >
                                                    {{
                                                        reviewLoading
                                                            ? "Submitting..."
                                                            : "Submit"
                                                    }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- =================================================
                                 RELATED PRODUCTS
                            ================================================== -->

                            <div class="aa-product-related-item">
                                <h3>Related Products</h3>

                                <ul
                                    class="aa-product-catg aa-related-item-slider"
                                >
                                    <li
                                        v-for="related in relatedProducts"
                                        :key="related.id"
                                    >
                                        <figure>
                                            <router-link
                                                class="aa-product-img"
                                                :to="`/product/${related.slug}`"
                                            >
                                                <img
                                                    :src="
                                                        productImage(
                                                            related.image,
                                                        )
                                                    "
                                                    :alt="related.name"
                                                />
                                            </router-link>

                                            <router-link
                                                class="aa-add-card-btn"
                                                :to="`/product/${related.slug}`"
                                            >
                                                <span
                                                    class="fa fa-shopping-cart"
                                                ></span>

                                                Add To Cart
                                            </router-link>

                                            <figcaption>
                                                <h4 class="aa-product-title">
                                                    <router-link
                                                        :to="`/product/${related.slug}`"
                                                    >
                                                        {{ related.name }}
                                                    </router-link>
                                                </h4>

                                                <template
                                                    v-if="
                                                        getRelatedAttribute(
                                                            related.id,
                                                        )
                                                    "
                                                >
                                                    <span
                                                        class="aa-product-price"
                                                    >
                                                        Rs
                                                        {{
                                                            getRelatedAttribute(
                                                                related.id,
                                                            ).price
                                                        }}
                                                    </span>

                                                    <span
                                                        class="aa-product-price"
                                                    >
                                                        <del>
                                                            Rs
                                                            {{
                                                                getRelatedAttribute(
                                                                    related.id,
                                                                )
                                                                    .maximum_retail_price
                                                            }}
                                                        </del>
                                                    </span>
                                                </template>
                                            </figcaption>
                                        </figure>
                                    </li>

                                    <li v-if="!relatedProducts.length">
                                        <figure>No data found</figure>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- PRODUCT NOT FOUND -->

                        <div v-else class="text-center" style="padding: 80px 0">
                            <h2>Product not found</h2>
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
    name: "Product",

    computed: {
        ...mapGetters(["getUserToken", "getGuestId"]),

        // =====================================================
        // SELECTED ATTRIBUTE
        // =====================================================

        selectedAttribute() {
            if (!this.product_attributes.length) {
                return null;
            }

            /*
             * If both size and color are selected,
             * find exact combination.
             */

            if (this.selectedSizeId && this.selectedColorId) {
                const exactAttribute = this.product_attributes.find(
                    (attr) =>
                        Number(attr.size_id) === Number(this.selectedSizeId) &&
                        Number(attr.color_id) === Number(this.selectedColorId),
                );

                if (exactAttribute) {
                    return exactAttribute;
                }
            }

            /*
             * Size only
             */

            if (this.selectedSizeId) {
                const sizeAttribute = this.product_attributes.find(
                    (attr) =>
                        Number(attr.size_id) === Number(this.selectedSizeId),
                );

                if (sizeAttribute) {
                    return sizeAttribute;
                }
            }

            /*
             * Color only
             */

            if (this.selectedColorId) {
                const colorAttribute = this.product_attributes.find(
                    (attr) =>
                        Number(attr.color_id) === Number(this.selectedColorId),
                );

                if (colorAttribute) {
                    return colorAttribute;
                }
            }

            return this.product_attributes[0];
        },

        // =====================================================
        // SELECTED SIZE
        // =====================================================

        selectedSize() {
            if (!this.selectedSizeId) {
                return null;
            }

            return (
                this.sizes.find(
                    (size) => Number(size.id) === Number(this.selectedSizeId),
                ) || null
            );
        },

        // =====================================================
        // SELECTED COLOR
        // =====================================================

        selectedColor() {
            if (!this.selectedColorId) {
                return null;
            }

            return (
                this.availableColors.find(
                    (color) =>
                        Number(color.id) === Number(this.selectedColorId),
                ) || null
            );
        },

        // =====================================================
        // SIZES
        // =====================================================

        sizes() {
            const result = [];

            this.product_attributes.forEach((attr) => {
                if (attr.size_id && attr.size) {
                    const exists = result.find(
                        (item) => Number(item.id) === Number(attr.size_id),
                    );

                    if (!exists) {
                        result.push({
                            id: Number(attr.size_id),
                            name: attr.size,
                        });
                    }
                }
            });

            return result;
        },

        // =====================================================
        // COLORS
        // =====================================================

        availableColors() {
            const result = [];

            this.product_attributes.forEach((attr) => {
                /*
                 * If size selected,
                 * only show colors for that size.
                 */

                if (
                    this.selectedSizeId &&
                    Number(attr.size_id) !== Number(this.selectedSizeId)
                ) {
                    return;
                }

                if (attr.color_id && attr.color) {
                    const exists = result.find(
                        (item) => Number(item.id) === Number(attr.color_id),
                    );

                    if (!exists) {
                        result.push({
                            id: Number(attr.color_id),
                            name: attr.color,
                        });
                    }
                }
            });

            return result;
        },

        // =====================================================
        // AVAILABLE STOCK
        // =====================================================

        availableStock() {
            if (!this.selectedAttribute) {
                return 0;
            }

            /*
             * Try common stock field names.
             */

            return Number(
                this.selectedAttribute.quantity ??
                    this.selectedAttribute.qty ??
                    this.selectedAttribute.stock ??
                    this.selectedAttribute.stock_quantity ??
                    this.selectedAttribute.available_quantity ??
                    0,
            );
        },

        // =====================================================
        // OUT OF STOCK
        // =====================================================

        isOutOfStock() {
            return this.availableStock <= 0;
        },

        // =====================================================
        // QUANTITY OPTIONS
        // =====================================================

        quantityOptions() {
            const stock = this.availableStock;

            if (stock <= 0) {
                return [];
            }

            /*
             * Maximum 10 in dropdown,
             * but never more than actual stock.
             */

            const max = Math.min(stock, 10);

            return Array.from({ length: max }, (_, index) => index + 1);
        },
    },

    data() {
        return {
            // =====================================================
            // PRODUCT
            // =====================================================

            product: null,

            product_attributes: [],

            product_images: [],

            product_reviews: [],

            // =====================================================
            // RELATED
            // =====================================================

            relatedProducts: [],

            relatedProductAttributes: {},

            // =====================================================
            // IMAGE
            // =====================================================

            selectedImage: "",

            // =====================================================
            // OPTIONS
            // =====================================================

            selectedSizeId: null,

            selectedColorId: null,

            quantity: 1,

            // =====================================================
            // LOADING
            // =====================================================

            loading: false,

            cartLoading: false,

            reviewLoading: false,

            // =====================================================
            // REVIEW
            // =====================================================

            reviewForm: {
                rating: "",
                review: "",
            },
        };
    },

    methods: {
        // =====================================================
        // PRODUCT IMAGE
        // =====================================================

        productImage(image) {
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
        // COLOR VALUE
        // =====================================================

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

        // =====================================================
        // GET PRODUCT
        // =====================================================

        async getProduct() {
            this.loading = true;

            try {
                const slug = this.$route.params.slug;

                console.log("PRODUCT REQUEST:", `/api/product/${slug}`);

                const response = await axios.get(`/api/product/${slug}`);

                console.log("PRODUCT RESPONSE:", response.data);

                if (!response.data || response.data.status !== true) {
                    throw new Error(
                        response.data?.message || "Product not found.",
                    );
                }

                const result = response.data.result;

                // =================================================
                // PRODUCT
                // =================================================

                this.product = Array.isArray(result.product)
                    ? result.product[0] || null
                    : result.product || null;

                if (!this.product) {
                    throw new Error("Product data not found.");
                }

                // =================================================
                // ATTRIBUTES
                // =================================================

                this.product_attributes =
                    result.product_attr?.[this.product.id] || [];

                console.log("PRODUCT ATTRIBUTES:", this.product_attributes);

                // =================================================
                // IMAGES
                // =================================================

                this.product_images =
                    result.product_images?.[this.product.id] || [];

                console.log("PRODUCT IMAGES:", this.product_images);

                // =================================================
                // RELATED PRODUCTS
                // =================================================

                this.relatedProducts = Array.isArray(result.related_product)
                    ? result.related_product
                    : [];

                // =================================================
                // RELATED ATTRIBUTES
                // =================================================

                this.relatedProductAttributes =
                    result.related_product_attr || {};

                // =================================================
                // REVIEWS
                // =================================================

                this.product_reviews = Array.isArray(result.product_review)
                    ? result.product_review
                    : [];

                // =================================================
                // DEFAULT ATTRIBUTE
                // =================================================

                if (this.product_attributes.length) {
                    const first = this.product_attributes[0];

                    this.selectedSizeId = first.size_id
                        ? Number(first.size_id)
                        : null;

                    this.selectedColorId = first.color_id
                        ? Number(first.color_id)
                        : null;
                }

                // =================================================
                // DEFAULT IMAGE
                // =================================================

                this.selectedImage = this.productImage(this.product.image);

                // =================================================
                // RESET QUANTITY
                // =================================================

                this.quantity = 1;

                this.$nextTick(() => {
                    this.initLens();
                });
            } catch (error) {
                console.error("PRODUCT ERROR:", error);

                console.error("PRODUCT ERROR RESPONSE:", error.response?.data);

                if (this.$toast) {
                    this.$toast.error(
                        error.response?.data?.message ||
                            error.message ||
                            "Unable to load product.",
                    );
                }

                this.product = null;
            } finally {
                this.loading = false;
            }
        },

        // =====================================================
        // CHANGE IMAGE
        // =====================================================

        changeImage(image) {
            this.selectedImage = image;
        },

        // =====================================================
        // SELECT SIZE
        // =====================================================

        selectSize(sizeId) {
            this.selectedSizeId = Number(sizeId);

            /*
             * Find attributes belonging
             * to selected size.
             */

            const available = this.product_attributes.filter(
                (attr) => Number(attr.size_id) === Number(sizeId),
            );

            /*
             * Reset color.
             */

            this.selectedColorId = null;

            /*
             * If only one color exists,
             * automatically select it.
             */

            if (available.length === 1) {
                const first = available[0];

                if (first.color_id) {
                    this.selectedColorId = Number(first.color_id);
                }
            }

            /*
             * Reset quantity.
             */

            this.quantity = 1;

            /*
             * If selected size has no stock,
             * show toast.
             */

            const stock = Number(
                available[0]?.quantity ??
                    available[0]?.qty ??
                    available[0]?.stock ??
                    available[0]?.stock_quantity ??
                    available[0]?.available_quantity ??
                    0,
            );

            if (available.length && stock <= 0) {
                this.$toast?.warning("Selected size is out of stock.");
            }
        },

        // =====================================================
        // SELECT COLOR
        // =====================================================

        // =====================================================
        // SELECT COLOR
        // =====================================================

        selectColor(colorId) {
            this.selectedColorId = Number(colorId);

            // Exact selected SIZE + COLOR attribute
            const attribute = this.product_attributes.find((attr) => {
                const sizeMatch =
                    !this.selectedSizeId ||
                    Number(attr.size_id) === Number(this.selectedSizeId);

                const colorMatch = Number(attr.color_id) === Number(colorId);

                return sizeMatch && colorMatch;
            });

            // Reset quantity
            this.quantity = 1;

            // =================================================
            // CHANGE IMAGE ACCORDING TO SELECTED COLOR
            // =================================================

            if (attribute) {
                // Color attribute ki base image
                const colorImage =
                    attribute.attribute_image ||
                    attribute.image ||
                    attribute.product_image ||
                    null;

                if (colorImage) {
                    this.selectedImage = this.productImage(colorImage);
                }

                // =================================================
                // STOCK
                // =================================================

                const stock = Number(
                    attribute.quantity ??
                        attribute.qty ??
                        attribute.stock ??
                        attribute.stock_quantity ??
                        attribute.available_quantity ??
                        0,
                );

                if (stock <= 0) {
                    this.$toast?.warning(
                        `Color ${attribute.color || ""} is out of stock.`,
                    );
                }
            }
        },

        // =====================================================
        // ADD TO CART
        // =====================================================

        async addToCart() {
            if (this.cartLoading) {
                return;
            }

            // =================================================
            // PRODUCT CHECK
            // =================================================

            if (!this.product) {
                this.$toast?.error("Product not found.");

                return;
            }

            // =================================================
            // SIZE REQUIRED
            // =================================================

            if (this.sizes.length && !this.selectedSizeId) {
                this.$toast?.warning("Please select Size first.");

                return;
            }

            // =================================================
            // COLOR REQUIRED
            // =================================================

            if (this.availableColors.length && !this.selectedColorId) {
                this.$toast?.warning("Please select Color first.");

                return;
            }

            // =================================================
            // ATTRIBUTE
            // =================================================

            const attribute = this.selectedAttribute;

            if (!attribute) {
                this.$toast?.error(
                    "Selected size/color combination not found.",
                );

                return;
            }

            // =================================================
            // STOCK
            // =================================================

            const stock = Number(
                attribute.quantity ??
                    attribute.qty ??
                    attribute.stock ??
                    attribute.stock_quantity ??
                    attribute.available_quantity ??
                    0,
            );

            if (stock <= 0) {
                this.$toast?.error("This selected product is Out of Stock.");

                return;
            }

            // =================================================
            // QUANTITY
            // =================================================

            const requestedQuantity = Number(this.quantity) || 1;

            if (requestedQuantity > stock) {
                this.$toast?.error(`Only ${stock} item(s) available.`);

                this.quantity = stock;

                return;
            }

            // =================================================
            // ADD TO CART
            // =================================================

            try {
                this.cartLoading = true;

                const userToken = this.getUserToken;

                const guestId = this.getGuestId;

                const data = {
                    product_id: this.product.id,

                    size_id: attribute.size_id ?? null,

                    color_id: attribute.color_id ?? null,

                    quantity: requestedQuantity,

                    attribute_image: attribute.attribute_image ?? null,
                };

                const headers = {
                    Accept: "application/json",

                    "Content-Type": "application/json",
                };

                // =================================================
                // LOGIN USER
                // =================================================

                if (userToken) {
                    headers.Authorization = `Bearer ${userToken}`;
                }

                // =================================================
                // GUEST USER
                // =================================================
                else {
                    if (!guestId) {
                        this.$toast?.error("Guest ID not found.");

                        return;
                    }

                    headers["X-Guest-ID"] = guestId;
                }

                console.log("ADD TO CART DATA:", data);

                const response = await axios.post("/api/add-to-cart", data, {
                    headers,
                });

                if (
                    response.data?.status === true ||
                    response.data?.success === true
                ) {
                    /*
                     * IMPORTANT:
                     * Toast will show selected
                     * size and color.
                     */

                    let message = `${this.product.name} added to cart.`;

                    if (this.selectedSize) {
                        message += ` Size: ${this.selectedSize.name}.`;
                    }

                    if (this.selectedColor) {
                        message += ` Color: ${this.selectedColor.name}.`;
                    }

                    message += ` Quantity: ${requestedQuantity}.`;

                    this.$toast?.success(message);
                    window.dispatchEvent(new Event("cart-updated"));
                } else {
                    this.$toast?.error(
                        response.data?.message ||
                            "Unable to add product to cart.",
                    );
                }
            } catch (error) {
                console.error("ADD TO CART ERROR:", error);

                console.error(
                    "ADD TO CART ERROR RESPONSE:",
                    error.response?.data,
                );

                this.$toast?.error(
                    error.response?.data?.message ||
                        "Something went wrong while adding to cart.",
                );
            } finally {
                this.cartLoading = false;
            }
        },

        // =====================================================
        // RELATED ATTRIBUTE
        // =====================================================

        getRelatedAttribute(productId) {
            const attributes = this.relatedProductAttributes[productId];

            if (attributes && attributes.length) {
                return attributes[0];
            }

            return null;
        },

        // =====================================================
        // REVIEW
        // =====================================================

        async submitReview() {
            if (this.reviewLoading) {
                return;
            }

            try {
                this.reviewLoading = true;

                const userToken = this.getUserToken;

                if (!userToken) {
                    this.$toast?.error("Please login to submit a review.");

                    return;
                }

                const data = {
                    product_id: this.product.id,

                    rating: this.reviewForm.rating,

                    review: this.reviewForm.review,
                };

                const response = await axios.post("/api/product-review", data, {
                    headers: {
                        Accept: "application/json",

                        Authorization: `Bearer ${userToken}`,
                    },
                });

                if (
                    response.data?.status === true ||
                    response.data?.success === true
                ) {
                    this.$toast?.success(
                        response.data?.message ||
                            "Review submitted successfully.",
                    );

                    this.reviewForm = {
                        rating: "",
                        review: "",
                    };

                    await this.getProduct();
                } else {
                    this.$toast?.error(
                        response.data?.message || "Unable to submit review.",
                    );
                }
            } catch (error) {
                console.error("REVIEW ERROR:", error);

                this.$toast?.error(
                    error.response?.data?.message ||
                        "Something went wrong while submitting review.",
                );
            } finally {
                this.reviewLoading = false;
            }
        },

        // =====================================================
        // DATE
        // =====================================================

        formatDate(date) {
            if (!date) {
                return "";
            }

            try {
                return new Date(date).toLocaleDateString();
            } catch (e) {
                return date;
            }
        },

        // =====================================================
        // SIMPLE LENS
        // =====================================================

        initLens() {
            try {
                if (
                    typeof window !== "undefined" &&
                    window.jQuery &&
                    window.jQuery.fn.simpleLens
                ) {
                    window
                        .jQuery("#demo-1 .simpleLens-lens-image")
                        .simpleLens();
                }
            } catch (error) {
                console.log("SimpleLens init skipped:", error);
            }
        },
    },

    // =========================================================
    // MOUNTED
    // =========================================================

    async mounted() {
        await this.getProduct();
    },

    // =========================================================
    // WATCH SLUG
    // =========================================================

    watch: {
        "$route.params.slug": {
            async handler() {
                this.product = null;

                this.product_attributes = [];

                this.product_images = [];

                this.selectedSizeId = null;

                this.selectedColorId = null;

                this.selectedImage = "";

                this.quantity = 1;

                await this.getProduct();
            },
        },

        // =====================================================
        // STOCK WATCHER
        // =====================================================

        availableStock(newStock) {
            if (newStock <= 0) {
                this.quantity = 1;

                return;
            }

            if (this.quantity > newStock) {
                this.quantity = newStock;
            }
        },
    },
};
</script>

<style scoped>
/* =========================================================
   SELECTED SIZE / COLOR
========================================================= */

.selected-option-text {
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 14px;
}

.selected-option-text strong {
    font-weight: 600;
}

/* =========================================================
   STOCK
========================================================= */

.stock-info {
    font-size: 13px;
    color: #777;
    margin-top: 5px;
}

.in-stock-text {
    color: #2e7d32;
    font-weight: 600;
}

.out-of-stock-text {
    color: #d32f2f;
    font-weight: 600;
}

/* =========================================================
   OUT OF STOCK BOX
========================================================= */

.out-of-stock-box {
    margin-top: 15px;
    padding: 15px;
    border: 1px solid #f1b0b0;
    background: #fff5f5;
    color: #d32f2f;
}

.out-of-stock-box i {
    margin-right: 8px;
}

.out-of-stock-box strong {
    font-size: 16px;
}

.out-of-stock-box p {
    margin: 5px 0 0;
}

/* =========================================================
   ADD TO CART DISABLED
========================================================= */

.aa-add-to-cart-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: auto;
}

/* =========================================================
   COLOR ACTIVE
========================================================= */

.product_color {
    display: inline-block;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    margin-right: 8px;
    cursor: pointer;
    border: 2px solid #ddd;
    vertical-align: middle;
}

.product_color.active {
    border: 3px solid #000;
    box-shadow:
        0 0 0 2px #fff,
        0 0 0 4px #000;
}

/* =========================================================
   SIZE ACTIVE
========================================================= */

.size_link {
    display: inline-block;
    padding: 7px 14px;
    margin-right: 5px;
    margin-bottom: 5px;
    border: 1px solid #ddd;
    cursor: pointer;
}

.size_link.active {
    background: #ff6666;
    color: #fff;
    border-color: #ff6666;
}

/* =========================================================
   QUANTITY
========================================================= */

.aa-prod-quantity select {
    min-width: 70px;
    height: 35px;
    padding: 5px 10px;
}
</style>
s
