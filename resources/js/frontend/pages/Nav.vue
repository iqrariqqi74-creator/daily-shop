<template>
    <section id="menu">
        <div class="container">
            <div class="menu-area">
                <nav class="navbar navbar-default">
                    <!-- MOBILE BUTTON -->
                    <div class="navbar-header">
                        <button
                            type="button"
                            class="navbar-toggle collapsed"
                            data-toggle="collapse"
                            data-target="#main-navbar"
                        >
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!-- MENU -->
                    <div id="main-navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <!-- HOME -->
                            <li>
                                <router-link to="/"> Home </router-link>
                            </li>

                            <!-- CATEGORIES -->
                            <li
                                v-for="category in categories"
                                :key="category.id"
                                class="dropdown hover-dropdown"
                            >
                                <router-link :to="`/category/${category.id}`">
                                    {{ category.name }}

                                    <span
                                        v-if="
                                            category.children &&
                                            category.children.length
                                        "
                                        class="caret"
                                    ></span>
                                </router-link>

                                <!-- SUB CATEGORY -->
                                <ul
                                    v-if="
                                        category.children &&
                                        category.children.length
                                    "
                                    class="dropdown-menu"
                                >
                                    <li
                                        v-for="child in category.children"
                                        :key="child.id"
                                    >
                                        <router-link
                                            :to="`/category/${child.id}`"
                                        >
                                            {{ child.name }}
                                        </router-link>
                                    </li>
                                </ul>
                            </li>

                            <!-- PRODUCTS -->
                            <li>
                                <router-link to="/products">
                                    Products
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "Navbar",

    data() {
        return {
            categories: [],
            isLoading: false,
        };
    },

    methods: {
        async getCategories() {
            this.isLoading = true;

            try {
                const response = await axios.get("/api/home");

                this.categories = response.data?.result?.home_categories || [];
            } catch (error) {
                this.categories = [];
            } finally {
                this.isLoading = false;
            }
        },
    },

    mounted() {
        this.getCategories();
    },
};
</script>

<style scoped>
/* =====================================
   BOOTSTRAP NAVBAR
===================================== */

#menu {
    width: 100%;
}

.menu-area {
    width: 100%;
}

/* =====================================
   HOVER DROPDOWN
   Bootstrap ka original background
   aur styling rahegi
===================================== */

@media (min-width: 768px) {
    .hover-dropdown:hover > .dropdown-menu {
        display: block;
    }

    .hover-dropdown:hover > a {
        background-color: #e7e7e7;
    }

    .hover-dropdown > .dropdown-menu {
        margin-top: 0;
    }
}

/* =====================================
   SUB CATEGORY
===================================== */

.dropdown-menu > li > a {
    white-space: nowrap;
}

/* =====================================
   MOBILE
   Bootstrap ka normal click/collapse
   ===================================== */

@media (max-width: 767px) {
    .hover-dropdown > .dropdown-menu {
        position: static;
        float: none;
        width: auto;
        margin-top: 0;
        background-color: transparent;
        border: 0;
        box-shadow: none;
    }
}
</style>
