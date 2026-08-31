import { createRouter, createWebHistory } from "vue-router";
import HomeLayout from "../frontend/pages/HomeLayout.vue";

const routes = [
    // =====================================================
    // HOME
    // =====================================================
    {
        path: "/",
        name: "Home",
        component: () => import("./pages/Home.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // CATEGORY
    // =====================================================
    {
        path: "/category/:id",
        name: "Category",
        component: () => import("../frontend/pages/Category.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // CART
    // =====================================================
    {
        path: "/cart",
        name: "Cart",
        component: () => import("../frontend/pages/Cart.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // CHECKOUT
    // =====================================================
    {
        path: "/checkout",
        name: "Checkout",
        component: () => import("../frontend/pages/Checkout.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // PRODUCTS
    // =====================================================
    {
        path: "/products",
        name: "Products",
        component: () => import("../frontend/pages/Products.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // PRODUCT DETAIL
    // =====================================================
    {
        path: "/product/:slug",
        name: "ProductDetails",
        component: () => import("../frontend/pages/ProductDetails.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // REGISTRATION
    // =====================================================
    {
        path: "/registration",
        name: "Registration",
        component: () => import("./pages/Registration.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // EMAIL VERIFICATION
    // =====================================================
    {
        path: "/email-verification/:rand_id",
        name: "EmailVerification",
        component: () => import("./pages/EmailVerification.vue"),
        meta: {
            layout: HomeLayout,
        },
    },

    // =====================================================
    // CUSTOMER ORDERS
    // =====================================================
    {
        path: "/orders",
        name: "Orders",
        component: () => import("./pages/Order.vue"),
        meta: {
            auth: true,
            layout: HomeLayout,
        },
    },

    // =====================================================
    // CUSTOMER ORDER DETAIL
    // =====================================================
    {
        path: "/orders/:id",
        name: "OrderDetail",
        component: () => import("./pages/OrderDetail.vue"),
        meta: {
            auth: true,
            layout: HomeLayout,
        },
    },

    {
        path: "/forgot_password_change/:rand_id",
        name: "ForgotPasswordChange",
        component: () => import("./pages/ChangePassword.vue"),
        meta: {
            layout: HomeLayout,
        },
    },
];

// =====================================================
// CREATE ROUTER
// =====================================================
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
