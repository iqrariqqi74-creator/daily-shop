<template>
    <aside class="main-sidebar sidebar-white-dark elevation-4">
        <!-- Logo -->
        <router-link to="/admin" class="brand-link">
            <img
                :src="logoImage"
                alt="Logo"
                class="brand-image img-circle elevation-3"
            />
            <span class="brand-text font-weight-light text-danger">Task</span>
        </router-link>

        <div class="sidebar">
            <!-- User Panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="userImage"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <router-link to="/admin" class="d-block text-danger">{{
                        getUser?.name
                    }}</router-link>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    role="menu"
                    data-accordion="false"
                >

                    <!-- ORDERS MENU -->
                    <li
                        :class="[
                            'nav-item',
                            menuOpen === 'orders' ? 'menu-open' : '',
                        ]"
                    >
                        <a
                            href="#"
                            class="nav-link text-danger"
                            :class="{ active: menuOpen === 'orders' }"
                            @click.prevent="toggleMenu('orders')"
                        >
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Orders
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link
                                    to="/admin/orders"
                                    class="nav-link text-danger"
                                    active-class="active"
                                >
                                    <i
                                        class="fas fa-clipboard-check nav-icon"
                                    ></i>
                                    <p>Orders List</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <!-- categories -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/categories"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Categories</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/brands"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-copyright"></i>
                            <p>Brands</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/sizes"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-ruler-combined"></i>
                            <p>Sizes</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            to="/admin/colors"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-palette"></i>
                            <p>Colors</p>
                        </router-link>
                    </li>
                    <!-- Products -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/products"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Products</p>
                        </router-link>
                    </li>

                    <!-- Banners -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/banners"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-images"></i>
                            <p>Banners</p>
                        </router-link>
                    </li>
                    <!-- Coupons -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/coupons"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            <p>Coupon Code</p>
                        </router-link>
                    </li>

                    <!-- Taxes -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/taxes"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-percent"></i>
                            <p>Taxes</p>
                        </router-link>
                    </li>

                    <!-- USERS MENU -->
                    <li
                        :class="[
                            'nav-item',
                            menuOpen === 'users' ? 'menu-open' : '',
                        ]"
                    >
                        <a
                            href="#"
                            class="nav-link text-danger"
                            :class="{ active: menuOpen === 'users' }"
                            @click.prevent="toggleMenu('users')"
                        >
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link
                                    to="/admin/users"
                                    class="nav-link text-danger"
                                    active-class="active"
                                >
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>All Users</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link
                                    to="/admin/roles"
                                    class="nav-link text-danger"
                                    active-class="active"
                                >
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>All Roles</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <!-- CUSTOMERS -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/customers"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Customers</p>
                        </router-link>
                    </li>

                    <!-- VENDORS -->
                    <li class="nav-item">
                        <router-link
                            to="/admin/vendors"
                            class="nav-link text-danger"
                            active-class="active"
                        >
                            <i class="nav-icon fas fa-store"></i>
                            <p>Vendors</p>
                        </router-link>
                    </li>

                    <!-- Transaction MENU -->
                    <li
                        :class="[
                            'nav-item',
                            menuOpen === 'transactions' ? 'menu-open' : '',
                        ]"
                    >
                        <a
                            href="#"
                            class="nav-link text-danger"
                            :class="{ active: menuOpen === 'transactions' }"
                            @click.prevent="toggleMenu('transactions')"
                        >
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>
                                Transactions
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link
                                    to="/admin/transactions"
                                    class="nav-link text-danger"
                                    active-class="active"
                                >
                                    <i
                                        class="fas fa-file-invoice-dollar nav-icon"
                                    ></i>
                                    <p>Transactions List</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "Sidebar",
    data() {
        return {
            logoImage: "/adminlte/dist/img/AdminLTELogo.png",
            userImage: "/adminlte/dist/img/user2-160x160.jpg",
            menuOpen: "", // track which menu is open
        };
    },
    computed: {
        ...mapGetters(["getUser"]),
    },
    mounted() {
        this.setActiveMenuBasedOnRoute();
    },
    watch: {
        $route() {
            this.setActiveMenuBasedOnRoute();
        },
    },
    methods: {
        toggleMenu(menu) {
            this.menuOpen = this.menuOpen === menu ? "" : menu;
        },
        setActiveMenuBasedOnRoute() {
            const path = this.$route.path;

            if (
                path.includes("/admin/users") ||
                path.includes("/admin/roles")
            ) {
                this.menuOpen = "users";
            } else if (
                path.includes("/admin/orders") ||
                path.includes("/admin/orders/status")
            ) {
                this.menuOpen = "orders";
            } else if (
                path.includes("/admin/transactions") ||
                path.includes("/admin/transaction/status")
            ) {
                this.menuOpen = "transactions";
            } else {
                this.menuOpen = "";
            }
        },
    },
};
</script>

<style scoped>
.nav-pills .nav-link.active {
    color: #fff !important;
    background-color: #e32537 !important;
}

/* PARENT ACTIVE */
.nav-sidebar > .nav-item > .nav-link.active {
    background-color: #e32537 !important;
    color: #fff !important;
}

/* CHILD ACTIVE (LIGHT COLOR) */
.nav-treeview .nav-link.active {
    background-color: #f8b4ba !important;
    /* light red */
    color: #ffffff !important;
}

/* CHILD HOVER */
.nav-treeview .nav-link:hover {
    background-color: #fde2e5 !important;
    color: #e32537 !important;
}

/* OPTIONAL: parent open but not active */
.menu-open > .nav-link {
    background-color: #e32537 !important;
    color: #fff !important;
}
</style>
