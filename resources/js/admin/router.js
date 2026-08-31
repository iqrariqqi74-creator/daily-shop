import { createRouter, createWebHistory } from "vue-router";
import AdminLayout from "./pages/AdminLayout.vue";

const routes = [
    // =====================================================
    // ADMIN LOGIN
    // =====================================================
    {
        path: "/admin/login",
        name: "AdminLogin",
        component: () => import("./pages/auth/login.vue"),
    },

    {
        path: "/admin/forgot",
        name: "AdminForgot",
        component: () => import("./pages/auth/forgot.vue"),
    },

    {
        path: "/admin/reset",
        name: "AdminRest",
        component: () => import("./pages/auth/reset.vue"),
    },

    // =====================================================
    // ADMIN DASHBOARD
    // =====================================================
    {
        path: "/admin",
        name: "AdminHome",
        component: () => import("./pages/Dashboard.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
        },
    },

    // =====================================================
    // BRANDS
    // =====================================================
    {
        path: "/admin/brands",
        name: "BrandsIndex",
        component: () => import("./pages/brands/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Brand",
        },
    },

    {
        path: "/admin/brands/create",
        name: "BrandsCreate",
        component: () => import("./pages/brands/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Brand",
        },
    },

    {
        path: "/admin/brands/:id/edit",
        name: "BrandsEdit",
        component: () => import("./pages/brands/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Brand",
        },
    },

    // =====================================================
    // COLORS
    // =====================================================
    {
        path: "/admin/colors",
        name: "ColorsIndex",
        component: () => import("./pages/colors/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Color",
        },
    },

    {
        path: "/admin/colors/create",
        name: "ColorsCreate",
        component: () => import("./pages/colors/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Color",
        },
    },

    {
        path: "/admin/colors/:id/edit",
        name: "ColorsEdit",
        component: () => import("./pages/colors/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Color",
        },
    },

    {
        path: "/admin/colors/:id/details",
        name: "color-details",
        component: () => import("./pages/colors/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Brand",
        },
    },

    // =====================================================
    // SIZES
    // =====================================================
    {
        path: "/admin/sizes",
        name: "SizesIndex",
        component: () => import("./pages/sizes/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Size",
        },
    },

    {
        path: "/admin/sizes/create",
        name: "SizesCreate",
        component: () => import("./pages/sizes/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Size",
        },
    },

    {
        path: "/admin/sizes/:id/edit",
        name: "SizesEdit",
        component: () => import("./pages/sizes/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Size",
        },
    },

    {
        path: "/admin/sizes/:id/details",
        name: "size-details",
        component: () => import("./pages/sizes/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Size",
        },
    },

    // =====================================================
    // BANNERS
    // =====================================================
    {
        path: "/admin/banners",
        name: "bannersIndex",
        component: () => import("./pages/banners/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Banner",
        },
    },

    {
        path: "/admin/banners/create",
        name: "bannersCreate",
        component: () => import("./pages/banners/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Banner",
        },
    },

    {
        path: "/admin/banners/:id/edit",
        name: "bannersEdit",
        component: () => import("./pages/banners/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Banner",
        },
    },

    {
        path: "/admin/banners/:id/details",
        name: "banner-details",
        component: () => import("./pages/banners/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Banner",
        },
    },

    // =====================================================
    // TAXES
    // =====================================================
    {
        path: "/admin/taxes",
        name: "taxIndex",
        component: () => import("./pages/taxes/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Tax",
        },
    },

    {
        path: "/admin/taxes/create",
        name: "taxesCreate",
        component: () => import("./pages/taxes/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Tax",
        },
    },

    {
        path: "/admin/taxes/:id/edit",
        name: "taxesEdit",
        component: () => import("./pages/taxes/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Tax",
        },
    },

    {
        path: "/admin/taxes/:id/details",
        name: "Tax-details",
        component: () => import("./pages/taxes/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Tax",
        },
    },

    // =====================================================
    // PRODUCTS
    // =====================================================
    {
        path: "/admin/products",
        name: "ProductIndex",
        component: () => import("./pages/products/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Size",
        },
    },

    {
        path: "/admin/products/create",
        name: "productCreate",
        component: () => import("./pages/products/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Size",
        },
    },

    {
        path: "/admin/products/:id/edit",
        name: "productEdit",
        component: () => import("./pages/products/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Product",
        },
    },

    {
        path: "/admin/products/:id/details",
        name: "productDetails",
        component: () => import("./pages/products/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Product",
        },
    },

    // =====================================================
    // CATEGORIES
    // =====================================================
    {
        path: "/admin/categories",
        name: "CategoriesIndex",
        component: () => import("./pages/categories/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Category",
        },
    },

    {
        path: "/admin/categories/create",
        name: "CategoriesCreate",
        component: () => import("./pages/categories/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Category",
        },
    },

    {
        path: "/admin/categories/:id/edit",
        name: "CategoriesEdit",
        component: () => import("./pages/categories/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Category",
        },
    },

    {
        path: "/admin/categories/:id/details",
        name: "category-details",
        component: () => import("./pages/categories/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Category",
        },
    },

    // =====================================================
    // COUPONS
    // =====================================================
    {
        path: "/admin/coupons",
        name: "couponsIndex",
        component: () => import("./pages/coupons/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Coupon",
        },
    },

    {
        path: "/admin/coupons/create",
        name: "couponsCreate",
        component: () => import("./pages/coupons/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Coupon",
        },
    },

    {
        path: "/admin/coupons/:id/edit",
        name: "couponsEdit",
        component: () => import("./pages/coupons/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Coupon",
        },
    },

    {
        path: "/admin/coupons/:id/details",
        name: "coupon-details",
        component: () => import("./pages/coupons/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Coupon",
        },
    },

    // =====================================================
    // ROLES
    // =====================================================
    {
        path: "/admin/roles",
        name: "RolesIndex",
        component: () => import("./pages/roles/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Show Role",
        },
    },

    {
        path: "/admin/roles/create",
        name: "RolesCreate",
        component: () => import("./pages/roles/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add Role",
        },
    },

    {
        path: "/admin/roles/:id/edit",
        name: "RolesEdit",
        component: () => import("./pages/roles/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Role",
        },
    },

    {
        path: "/admin/roles/:id/permissions",
        name: "GivePermissions",
        component: () => import("./pages/roles/permissions.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Give Permissions",
        },
    },

    // =====================================================
    // USERS
    // =====================================================
    {
        path: "/admin/users",
        name: "UsersIndex",
        component: () => import("./pages/users/index.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View User",
        },
    },

    {
        path: "/admin/users/create",
        name: "UsersCreate",
        component: () => import("./pages/users/create.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Add User",
        },
    },

    {
        path: "/admin/users/:id/edit",
        name: "UsersEdit",
        component: () => import("./pages/users/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit User",
        },
    },

    // =====================================================
    // CUSTOMERS
    // =====================================================
    {
        path: "/admin/customers",
        name: "customerIndex",
        component: () => import("./pages/customers/index.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Show Customer",
        },
    },

    {
        path: "/admin/customers/create",
        name: "customerCreate",
        component: () => import("./pages/customers/create.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Add Customer",
        },
    },

    {
        path: "/admin/customers/:id/edit",
        name: "customerEdit",
        component: () => import("./pages/customers/edit.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "Edit Customer",
        },
    },

    {
        path: "/admin/customers/:id/details",
        name: "customerDetails",
        component: () => import("./pages/customers/detail.vue"),
        meta: {
            auth: true,
            layout: AdminLayout,
            Permissions: "View Customer",
        },
    },

    // =====================================================
    // VENDORS
    // =====================================================
    {
        path: "/admin/vendors",
        name: "vendorIndex",
        component: () => import("./pages/venders/index.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Show Vendor",
        },
    },

    {
        path: "/admin/vendors/create",
        name: "vendorCreate",
        component: () => import("./pages/venders/create.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Add Vendor",
        },
    },

    {
        path: "/admin/vendors/:id/edit",
        name: "vendorEdit",
        component: () => import("./pages/venders/edit.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Edit Vendor",
        },
    },

    {
        path: "/admin/vendors/:id/details",
        name: "vendorDetails",
        component: () => import("./pages/venders/detail.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "View Vendor",
        },
    },

    // =====================================================
    // ADMIN ORDERS
    // =====================================================
    {
        path: "/admin/orders",
        name: "orderIndex",
        component: () => import("./pages/orders/index.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Show Order",
        },
    },

    {
        path: "/admin/orders/create",
        name: "orderCreate",
        component: () => import("./pages/orders/create.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Add Order",
        },
    },

    {
        path: "/admin/orders/:id/edit",
        name: "orderEdit",
        component: () => import("./pages/orders/edit.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Edit Order",
        },
    },

    {
        path: "/admin/orders/details/:id",
        name: "orderDetails",
        component: () => import("./pages/orders/details.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Edit Order",
        },
    },

    // =====================================================
    // TRANSACTIONS
    // =====================================================
    {
        path: "/admin/transactions",
        name: "transactionIndex",
        component: () => import("./pages/transactions/index.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Show Transaction",
        },
    },

    {
        path: "/admin/transactions/create",
        name: "transactionCreate",
        component: () => import("./pages/transactions/create.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Add Transaction",
        },
    },

    {
        path: "/admin/transactions/status",
        name: "transactionStatus",
        component: () => import("./pages/transactions/status.vue"),
        meta: {
            layout: AdminLayout,
            auth: true,
            Permissions: "Show Transaction",
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

// =====================================================
// ADMIN AUTH GUARD
// =====================================================
router.beforeEach((to) => {
    const isAuthenticated = localStorage.getItem("admin_token");

    // Sirf admin routes jin par meta.auth=true hai
    // unko admin token chahiye.
    if (to.meta.auth && !isAuthenticated) {
        return { name: "AdminLogin" };
    }

    return true;
});

export default router;
