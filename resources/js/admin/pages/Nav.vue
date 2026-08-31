<template>
    <!--begin::Header-->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <router-link to="/admin" class="nav-link">Home</router-link>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <router-link :to="pageLink" class="nav-link text-capitalize">
                    {{ currentPage.charAt(0).toUpperCase() + currentPage.slice(1) }}
                </router-link>
            </li>

            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link" @click.prevent="logout">Logout</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                aria-label="Search" />
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!--end::Header-->
</template>

<script>
export default {
    name: "Nav",

    data() {
        return {
            // You can add reactive data properties here if needed
        };
    },

    computed: {
        currentPage() {
            let path = this.$route.path;

            // remove /admin/
            path = path.replace('/admin/', '');

            // agar nested route ho (orders/status etc)
            if (path.includes('/')) {
                path = path.split('/')[0];
            }

            // agar empty ya dashboard
            if (!path || path === 'dashboard') {
                return 'dashboard';
            }

            return path;
        },

        pageLink() {
            // Special case for dashboard
            if (this.currentPage === 'dashboard') {
                return '/admin';
            }
            return `/admin/${this.currentPage}`;
        },

        pageLink() {
            // Special case for dashboard
            if (this.currentPage === 'dashboard') {
                return '/admin';
            }
            return `/admin/${this.currentPage}`;
        }
    },

    methods: {
        logout() {
            localStorage.removeItem("admin_token");
            sessionStorage.removeItem("admin_token");

            this.$toast.success("Logout Successfully!", {
                position: 'top-right',
                duration: 3000
            });

            this.$router.push("/admin/login");
        }
    }
};
</script>
