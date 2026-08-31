<template>
    <!-- ========================================================= -->
    <!-- PRODUCT CATEGORY -->
    <!-- ========================================================= -->

    <section id="aa-product-category">
        <div class="container">
            <div class="row">
                <!-- ================================================= -->
                <!-- PRODUCT CONTENT -->
                <!-- ================================================= -->

                <div class="col-lg-9 col-md-9 col-sm-8 col-md-push-3">
                    <div class="aa-product-catg-content">
                        <!-- ================================================= -->
                        <!-- PRODUCT HEADER -->
                        <!-- ================================================= -->

                        <div class="aa-product-catg-head">
                            <div class="aa-product-catg-head-left">
                                <form class="aa-sort-form" @submit.prevent>
                                    <label for="sort_by_value"> Sort by </label>

                                    <select
                                        id="sort_by_value"
                                        v-model="sort"
                                        @change="sortProducts"
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

                                <span v-if="sort_txt">
                                    {{ sort_txt }}
                                </span>
                            </div>

                            <!-- ================================================= -->
                            <!-- GRID / LIST -->
                            <!-- ================================================= -->

                            <div class="aa-product-catg-head-right">
                                <a
                                    id="grid-catg"
                                    href="#"
                                    :class="{
                                        active: viewType === 'grid',
                                    }"
                                    @click.prevent="setView('grid')"
                                >
                                    <span class="fa fa-th"></span>
                                </a>

                                <a
                                    id="list-catg"
                                    href="#"
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
                                <!-- PRODUCTS -->
                                <!-- ================================================= -->

                                <li
                                    v-for="product in filteredProducts"
                                    :key="product.id"
                                >
                                    <figure>
                                        <!-- PRODUCT IMAGE -->

                                        <a
                                            class="aa-product-img"
                                            href="#"
                                            @click.prevent
                                        >
                                            <img
                                                :src="product.image"
                                                :alt="product.name"
                                            />
                                        </a>

                                        <!-- ADD TO CART -->

                                        <a
                                            class="aa-add-card-btn"
                                            href="#"
                                            @click.prevent="addToCart(product)"
                                        >
                                            <span
                                                class="fa fa-shopping-cart"
                                            ></span>

                                            Add To Cart
                                        </a>

                                        <!-- PRODUCT DETAILS -->

                                        <figcaption>
                                            <h4 class="aa-product-title">
                                                <a href="#" @click.prevent>
                                                    {{ product.name }}
                                                </a>
                                            </h4>

                                            <span class="aa-product-price">
                                                Rs
                                                {{ product.price.toFixed(2) }}
                                            </span>

                                            <span class="aa-product-price">
                                                <del>
                                                    Rs
                                                    {{ product.mrp.toFixed(2) }}
                                                </del>
                                            </span>
                                        </figcaption>
                                    </figure>
                                </li>

                                <!-- ================================================= -->
                                <!-- NO DATA -->
                                <!-- ================================================= -->

                                <li v-if="filteredProducts.length === 0">
                                    <figure>No data found</figure>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ================================================= -->
                <!-- SIDEBAR -->
                <!-- ================================================= -->

                <div class="col-lg-3 col-md-3 col-sm-4 col-md-pull-9">
                    <aside class="aa-sidebar">
                        <!-- ================================================= -->
                        <!-- CATEGORY -->
                        <!-- ================================================= -->

                        <div class="aa-sidebar-widget">
                            <h3>Category</h3>

                            <ul class="aa-catg-nav">
                                <li
                                    v-for="category in categories"
                                    :key="category.id"
                                >
                                    <a
                                        href="#"
                                        :class="{
                                            left_cat_active:
                                                selectedCategory ===
                                                category.id,
                                        }"
                                        @click.prevent="
                                            selectCategory(category.id)
                                        "
                                    >
                                        {{ category.name }}
                                    </a>
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
                                    <!-- PRICE SLIDER -->

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

                                    <button
                                        class="aa-filter-btn"
                                        type="button"
                                        @click="filterPrice"
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
                                    href="#"
                                    :class="[
                                        'aa-color-' + color.className,
                                        {
                                            active_color:
                                                selectedColors.includes(
                                                    color.id,
                                                ),
                                        },
                                    ]"
                                    @click.prevent="toggleColor(color.id)"
                                ></a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================= -->
    <!-- CART MESSAGE -->
    <!-- ========================================================= -->

    <div v-if="cartMessage" class="cart-message">
        {{ cartMessage }}
    </div>
</template>

<script>
export default {
    name: "Category",

    data() {
        return {
            // =====================================================
            // VIEW
            // =====================================================

            viewType: "grid",

            // =====================================================
            // SORT
            // =====================================================

            sort: "",

            sort_txt: "",

            // =====================================================
            // CATEGORY
            // =====================================================

            selectedCategory: 1,

            categories: [
                {
                    id: 1,
                    name: "Mobile",
                },

                {
                    id: 2,
                    name: "Laptop",
                },

                {
                    id: 3,
                    name: "Headphone",
                },

                {
                    id: 4,
                    name: "Smart Watch",
                },

                {
                    id: 5,
                    name: "Accessories",
                },
            ],

            // =====================================================
            // COLORS
            // =====================================================

            selectedColors: [],

            colors: [
                {
                    id: 1,
                    name: "Black",
                    className: "black",
                },

                {
                    id: 2,
                    name: "White",
                    className: "white",
                },

                {
                    id: 3,
                    name: "Red",
                    className: "red",
                },

                {
                    id: 4,
                    name: "Blue",
                    className: "blue",
                },

                {
                    id: 5,
                    name: "Green",
                    className: "green",
                },

                {
                    id: 6,
                    name: "Yellow",
                    className: "yellow",
                },
            ],

            // =====================================================
            // PRICE
            // =====================================================

            filter_price_start: 0,

            filter_price_end: 5000,

            // =====================================================
            // CART
            // =====================================================

            cartMessage: "",

            // =====================================================
            // STATIC PRODUCTS
            // =====================================================

            products: [
                {
                    id: 1,
                    category_id: 1,
                    name: "iPhone 15 Pro Max",
                    price: 3499,
                    mrp: 3999,
                    color_id: 1,
                    date: 10,
                    image: "https://picsum.photos/300/300?random=11",
                },

                {
                    id: 2,
                    category_id: 1,
                    name: "Samsung Galaxy S24 Ultra",
                    price: 2999,
                    mrp: 3499,
                    color_id: 2,
                    date: 20,
                    image: "https://picsum.photos/300/300?random=12",
                },

                {
                    id: 3,
                    category_id: 1,
                    name: "Google Pixel 9 Pro",
                    price: 2499,
                    mrp: 2999,
                    color_id: 3,
                    date: 30,
                    image: "https://picsum.photos/300/300?random=13",
                },

                {
                    id: 4,
                    category_id: 2,
                    name: "MacBook Pro M3",
                    price: 4599,
                    mrp: 4999,
                    color_id: 1,
                    date: 40,
                    image: "https://picsum.photos/300/300?random=14",
                },

                {
                    id: 5,
                    category_id: 2,
                    name: "Dell XPS 15",
                    price: 3899,
                    mrp: 4299,
                    color_id: 2,
                    date: 50,
                    image: "https://picsum.photos/300/300?random=15",
                },

                {
                    id: 6,
                    category_id: 3,
                    name: "AirPods Pro 2",
                    price: 899,
                    mrp: 1099,
                    color_id: 2,
                    date: 60,
                    image: "https://picsum.photos/300/300?random=16",
                },

                {
                    id: 7,
                    category_id: 3,
                    name: "Sony WH-1000XM5",
                    price: 1299,
                    mrp: 1499,
                    color_id: 1,
                    date: 70,
                    image: "https://picsum.photos/300/300?random=17",
                },

                {
                    id: 8,
                    category_id: 4,
                    name: "Apple Watch Series 10",
                    price: 1799,
                    mrp: 1999,
                    color_id: 4,
                    date: 80,
                    image: "https://picsum.photos/300/300?random=18",
                },

                {
                    id: 9,
                    category_id: 4,
                    name: "Galaxy Watch 7",
                    price: 1099,
                    mrp: 1299,
                    color_id: 5,
                    date: 90,
                    image: "https://picsum.photos/300/300?random=19",
                },

                {
                    id: 10,
                    category_id: 5,
                    name: "Apple MagSafe Charger",
                    price: 499,
                    mrp: 599,
                    color_id: 1,
                    date: 100,
                    image: "https://picsum.photos/300/300?random=20",
                },

                {
                    id: 11,
                    category_id: 5,
                    name: "USB-C Fast Charger",
                    price: 299,
                    mrp: 399,
                    color_id: 4,
                    date: 110,
                    image: "https://picsum.photos/300/300?random=21",
                },

                {
                    id: 12,
                    category_id: 5,
                    name: "Wireless Power Bank",
                    price: 699,
                    mrp: 899,
                    color_id: 3,
                    date: 120,
                    image: "https://picsum.photos/300/300?random=22",
                },
            ],
        };
    },

    computed: {
        // =========================================================
        // FILTER + SORT PRODUCTS
        // =========================================================

        filteredProducts() {
            let list = [...this.products];

            // =====================================================
            // CATEGORY
            // =====================================================

            if (this.selectedCategory) {
                list = list.filter(
                    (product) => product.category_id === this.selectedCategory,
                );
            }

            // =====================================================
            // PRICE
            // =====================================================

            list = list.filter(
                (product) =>
                    product.price >= Number(this.filter_price_start) &&
                    product.price <= Number(this.filter_price_end),
            );

            // =====================================================
            // COLOR
            // =====================================================

            if (this.selectedColors.length > 0) {
                list = list.filter((product) =>
                    this.selectedColors.includes(product.color_id),
                );
            }

            // =====================================================
            // SORT
            // =====================================================

            if (this.sort === "name") {
                list.sort((a, b) => a.name.localeCompare(b.name));
            }

            if (this.sort === "price_desc") {
                list.sort((a, b) => b.price - a.price);
            }

            if (this.sort === "price_asc") {
                list.sort((a, b) => a.price - b.price);
            }

            if (this.sort === "date") {
                list.sort((a, b) => b.date - a.date);
            }

            return list;
        },
    },

    methods: {
        // =========================================================
        // GRID / LIST
        // =========================================================

        setView(type) {
            this.viewType = type;

            console.log("VIEW:", this.viewType);
        },

        // =========================================================
        // SORT
        // =========================================================

        sortProducts() {
            if (this.sort === "name") {
                this.sort_txt = "Product Name";
            } else if (this.sort === "price_desc") {
                this.sort_txt = "Price - DESC";
            } else if (this.sort === "price_asc") {
                this.sort_txt = "Price - ASC";
            } else if (this.sort === "date") {
                this.sort_txt = "Date";
            } else {
                this.sort_txt = "";
            }
        },

        // =========================================================
        // CATEGORY
        // =========================================================

        selectCategory(id) {
            this.selectedCategory = id;

            this.selectedColors = [];

            this.filter_price_start = 0;

            this.filter_price_end = 5000;

            console.log("CATEGORY:", id);
        },

        // =========================================================
        // COLOR
        // =========================================================

        toggleColor(id) {
            const index = this.selectedColors.indexOf(id);

            if (index === -1) {
                this.selectedColors.push(id);
            } else {
                this.selectedColors.splice(index, 1);
            }

            console.log("SELECTED COLORS:", this.selectedColors);
        },

        // =========================================================
        // PRICE
        // =========================================================

        checkPriceRange() {
            if (
                Number(this.filter_price_start) > Number(this.filter_price_end)
            ) {
                const temp = this.filter_price_start;

                this.filter_price_start = this.filter_price_end;

                this.filter_price_end = temp;
            }
        },

        filterPrice() {
            console.log(
                "PRICE:",
                this.filter_price_start,
                this.filter_price_end,
            );
        },

        // =========================================================
        // ADD TO CART
        // =========================================================

        addToCart(product) {
            console.log("ADD TO CART:", product);

            this.cartMessage = product.name + " added to cart";

            setTimeout(() => {
                this.cartMessage = "";
            }, 2000);
        },
    },
};
</script>

<style scoped>
/* ========================================================= */
/* PRICE SLIDER */
/* ========================================================= */

.price-slider {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
}

.price-slider input[type="range"] {
    width: 100%;
}

/* ========================================================= */
/* CART MESSAGE */
/* ========================================================= */

.cart-message {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;

    background: #59b600;
    color: #fff;

    padding: 12px 20px;

    border-radius: 3px;

    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* ========================================================= */
/* GRID / LIST ACTIVE */
/* ========================================================= */

#aa-product-category .aa-product-catg-head-right a.active {
    background: #59b600;
    color: #fff;
}

/* ========================================================= */
/* COLOR ACTIVE */
/* ========================================================= */

#aa-product-category .aa-color-tag a.active_color {
    border: 3px solid #000;
    transform: scale(1.15);
}

/* ========================================================= */
/* COLOR FALLBACK */
/* ========================================================= */

.aa-color-black {
    background: #000 !important;
}

.aa-color-white {
    background: #fff !important;
    border: 1px solid #ccc;
}

.aa-color-red {
    background: red !important;
}

.aa-color-blue {
    background: blue !important;
}

.aa-color-green {
    background: green !important;
}

.aa-color-yellow {
    background: yellow !important;
}
</style>
