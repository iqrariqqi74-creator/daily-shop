<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <h3>Dashboard</h3>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <!-- Loader -->
                <div v-if="isLoading" class="text-center">
                    <img width="80" :src="loaderImage" />
                </div>

                <div v-else>
                    <!-- ===== CARDS ===== -->
                    <div class="row">
                        <div class="col-lg-4 col-6">
                            <router-link to="/admin/customers" class="small-box-link">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ activeCustomers }}</h3>
                                        <p>Active Customers</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <!-- Active Vendors with router-link -->
                        <div class="col-lg-4 col-6">
                            <router-link to="/admin/vendors" class="small-box-link">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ activeVendors }}</h3>
                                        <p>Active Vendors</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <!-- Active Orders with router-link -->
                        <div class="col-lg-4 col-6">
                            <router-link to="/admin/orders" class="small-box-link">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ activeOrders }}</h3>
                                        <p>Active Orders</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>

                    <!-- ===== BIG CHART ===== -->
                    <div class="card shadow mt-4 p-4">
                        <h5>Order Status Overview</h5>
                        <div style="height: 350px;">
                            <canvas ref="statusChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</template>

<script>
import axios from "axios";
import Chart from "chart.js/auto";
import router from "../router";

export default {
    data() {
        return {
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",

            orders: [],
            customers: [],
            vendors: [],

            chart: null
        };
    },

    computed: {
        activeCustomers() {
            return this.customers.filter(c => c.status == 1).length;
        },
        activeVendors() {
            return this.vendors.filter(v => v.status == 1).length;
        },
        activeOrders() {
            return this.orders.length;
        }
    },

    methods: {
        async fetchData() {
            try {
                this.isLoading = true;

                const [ordersRes, customersRes, vendorsRes] = await Promise.all([
                    axios.get("orders"),
                    axios.get("customers"),
                    axios.get("vendors")
                ]);

                this.orders = ordersRes.data.orders || [];
                this.customers = customersRes.data.customers || [];
                this.vendors = vendorsRes.data.vendors || [];

                this.isLoading = false;

                this.$nextTick(() => this.renderChart());

            } catch (error) {
                console.log(error);
                this.isLoading = false;
            }
        },

       renderChart() {
    const canvas = this.$refs.statusChart;
    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    if (this.chart) {
        this.chart.destroy();
    }

    // =========================================
    // ORDER STATUS COUNT
    // 1 = Pending
    // 2 = Completed
    // 3 = Cancelled
    // 4 = Processing
    // =========================================

    let statusCounts = {
        pending: 0,
        completed: 0,
        cancelled: 0,
        processing: 0,
    };

    this.orders.forEach((order) => {
        switch (Number(order.order_status)) {
            case 1:
                statusCounts.pending++;
                break;

            case 2:
                statusCounts.completed++;
                break;

            case 3:
                statusCounts.cancelled++;
                break;

            case 4:
                statusCounts.processing++;
                break;
        }
    });

    // =========================================
    // COLORS
    // =========================================

    const colors = {
        pending: "#f39c12",
        completed: "#00a65a",
        cancelled: "#dd4b39",
        processing: "#00c0ef",
    };

    this.chart = new Chart(ctx, {
        type: "bar",

        data: {
            labels: [
                "Pending",
                "Completed",
                "Cancelled",
                "Processing",
            ],

            datasets: [
                {
                    label: "Orders",

                    data: [
                        statusCounts.pending,
                        statusCounts.completed,
                        statusCounts.cancelled,
                        statusCounts.processing,
                    ],

                    backgroundColor: [
                        colors.pending,
                        colors.completed,
                        colors.cancelled,
                        colors.processing,
                    ],

                    borderWidth: 1,
                },
            ],
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false,
                },
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
},
    },

    mounted() {
        this.fetchData();
    }
};
</script>

<style scoped>
.small-box {
    border-radius: 10px;
    padding: 20px;
    color: white;
    text-align: center;
}

.small-box h3 {
    font-size: 2.2rem;
}

.small-box p {
    font-size: 1.1rem;
}

/* router-link wrapper hover */
.small-box-link {
    text-decoration: none;
}

.small-box-link:hover .small-box {
    opacity: 0.85;
    cursor: pointer;
}
</style>
