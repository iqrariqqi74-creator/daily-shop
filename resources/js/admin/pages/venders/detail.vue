<template>
    <section class="content">
        <div class="container-fluid">

            <!-- Loader -->
            <div v-if="isLoading" class="text-center">
                <img width="80" :src="loaderImage" />
            </div>

            <div v-else>

                <!-- TOP CARD -->
                <div class="card shadow p-4 mb-4" style="border-radius:12px;">
                    <div class="row align-items-center">

                        <!-- LEFT IMAGE -->
                        <div class="col-md-4">
                            <img :src="getImage(vendor.image)" class="img-fluid"
                                style="border-radius:12px; height:220px; width:100%; object-fit:cover;">
                        </div>

                        <!-- RIGHT INFO -->
                        <div class="col-md-6">
                            <h4 class="font-weight-bold">{{ vendor.name }}</h4>
                            <small class="text-muted">
                                vendor since {{ formatDate(vendor.created_at) }}
                            </small>

                            <hr>

                            <p><i class="fas fa-envelope text-danger mr-2"></i> {{ vendor.email }}</p>
                            <p><i class="fas fa-phone text-danger mr-2"></i> {{ vendor.phone_number }}</p>
                            <p><i class="fas fa-map-marker-alt text-danger mr-2"></i> <span
                                    v-html="vendor.address"></span></p>
                            <p><i class="fas fa-calendar-alt text-danger mr-2"></i> {{ formatDate(vendor.created_at)
                                }}</p>

                        </div>

                        <!-- STATUS -->
                        <div class="col-md-2 text-right">
                            <label class="switch">
                                <input type="checkbox" :checked="vendor.status == 1" @change="confirmToggle">
                                <span class="slider round"></span>
                            </label>

                            <div class="mt-2">
                                <span v-if="vendor.status == 1" class="badge badge-success">Active</span>
                                <span v-else class="badge badge-danger">Inactive</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 🔥 TABS (CENTER BELOW IMAGE — SAME AS DESIGN) -->
                <div class="card shadow p-2 mb-3" style="border-radius:12px;">
                    <div class="d-flex justify-content-start">

                        <div class="tab-item" :class="{ active: activeTab === 'profile' }"
                            @click="activeTab = 'profile'">
                            Profile
                        </div>

                        <div class="tab-item" :class="{ active: activeTab === 'transactions' }"
                            @click="activeTab = 'transactions'">
                            Transaction History
                        </div>

                        <div class="tab-item" :class="{ active: activeTab === 'stats' }" @click="activeTab = 'stats'">
                            Stats
                        </div>

                    </div>
                </div>

                <!-- TAB CONTENT -->

                <!-- PROFILE -->
                <div v-if="activeTab === 'profile'" class="card shadow p-4">
                    <h5 class="mb-3">Profile Info</h5>
                    <p><b>Name:</b> {{ vendor.name }}</p>
                    <p><b>Email:</b> {{ vendor.email }}</p>
                    <p><b>Phone:</b> {{ vendor.phone_number }}</p>
                    <p><b>Status:</b>
                        <span v-if="vendor.status == 1" class="text-success">Active</span>
                        <span v-else class="text-danger">Inactive</span>
                    </p>
                </div>

                <!-- transactions -->
                <div v-if="activeTab === 'transactions'" class="card shadow p-3">
                    <h5 class="mb-3">Transaction History</h5>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="d-flex align-items-center justify-content-center" v-if="isLoading">
                                <div>
                                    <img width="100" :src="loaderImage" />
                                    <p class="text-center mt-0">Please wait...</p>
                                </div>
                            </div>
                            <table v-show="!isLoading" class="table table-striped" id="dataTable" width="100%"
                                cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th> Customer</th>
                                        <th>Transaction ID</th>
                                        <th> Date Time </th>
                                        <th> Amount</th>
                                        <th> Payment Method</th>
                                        <th> Transaction Type</th>
                                        <th> Service Type</th>
                                        <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="transactions.length == 0">
                                        <td colspan="12">
                                            <h4 class="text-center">No data found.</h4>
                                        </td>
                                    </tr>
                                    <tr v-for="(transaction, index) in transactions" :key="index">

                                        <td>
                                            {{ transaction.id }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img :src="getImage(transaction.customer.image)" width="50" height="50"
                                                    class="rounded-circle mr-2" alt="vendor Image" />
                                                <div>
                                                    <small class="d-block font-weight-bold">{{ transaction.customer.name
                                                        }}</small>
                                                    <small class="d-block text-muted">{{ transaction.customer.email
                                                        }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ transaction.transaction_number }}</td>
                                        <td>{{ transaction.transaction_date_time }}</td>
                                        <td>{{ transaction.amount }}</td>
                                        <td>

                                            <span class="badge badge-warning"
                                                v-if="transaction.payment_method == 'credit_card'">
                                                Credit Card
                                            </span>
                                            <span class="badge badge-info"
                                                v-else-if="transaction.payment_method == 'debit_card'">Debit
                                                Card</span>
                                            <span class="badge badge-success"
                                                v-else-if="transaction.payment_method == 'cash'">Cash</span>
                                        </td>
                                        <td>

                                            <span class="badge badge-warning"
                                                v-if="transaction.transaction_type == 'vendor_payout'">Vendor
                                                Payout</span>
                                            <span class="badge badge-success"
                                                v-else-if="transaction.transaction_type == 'customer_payment'">Customer
                                                Payment</span>
                                        </td>
                                        <td>{{ transaction.service_type }}</td>

                                        <td>
                                            <span class="badge badge-success"
                                                v-if="transaction.status == 'completed'">completed</span>
                                            <span class="badge badge-danger"
                                                v-else-if="transaction.status == 'cancelled'">cancelled</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- STATS -->
                <div v-if="activeTab === 'stats'" class="card shadow p-4">
                    <h5 class="mb-4">vendor Stats</h5>
                    <div class="chart-container">
                        <canvas ref="transactionsChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
<script>
import axios from "axios";
import { format } from "date-fns";
import Chart from 'chart.js/auto';

export default {
    components: {
    },
    data() {
        return {
            vendor: {},
            transactions: [],
            activeTab: "profile",
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",
            chart: null
        };
    },

    methods: {

        async fetchVendors() {
            const id = this.$route.params.id;
            const res = await axios.get(`vendors/${id}`);
            this.vendor = res.data.vendor;
        },

        async fetchTransactions() {
            const id = this.$route.params.id;
            const res = await axios.get(`transactions/${id}/vendors`);
            this.transactions = res.data.transactions || [];
        },

        getImage(path) {
            if (!path) return "/images/default.png";
            return "http://dev.task-local/" + path;
        },


        renderChart() {

            if (!this.transactions.length) return;

            const canvas = this.$refs.transactionsChart;
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            // destroy old chart
            if (this.chart) {
                this.chart.destroy();
            }

            // 👉 STATUS COUNT
            let statusCounts = {
                pending: 0,
                processing: 0,
                completed: 0,
                cancelled: 0
            };

            this.transactions.forEach(transaction => {
                if (statusCounts[transaction.status] !== undefined) {
                    statusCounts[transaction.status]++;
                }
            });

            this.chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            statusCounts.completed,
                            statusCounts.cancelled
                        ],
                        backgroundColor: [
                            '#ffc107', // yellow
                            '#17a2b8', // blue
                            '#28a745', // green
                            '#dc3545'  // red
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        formatDate(date) {
            return format(new Date(date), "dd MMM yyyy");
        },

        async confirmToggle() {
            const confirm = await this.$swal({
                title: "Are you sure?",
                text: "Change status?",
                icon: "warning",
                showCancelButton: true,
            });

            if (confirm.isConfirmed) {
                const id = this.vendor.id;

                const res = await axios.post(`vendors/${id}/status`, {
                    status: this.vendor.status == 1 ? 0 : 1
                });

                this.vendor.status = res.data.status;
                this.fetchVendors();
                if (res.data.status == 1) {
                    this.$toast.success("vendor activated successfully");
                } else {
                    this.$toast.success("vendor deactivated successfully");
                }
            }
        }
    },

    watch: {
        activeTab(newTab) {
            if (newTab === 'stats') {
                setTimeout(() => {
                    this.renderChart();
                }, 300);
            }
        }
    },
    async mounted() {
        await this.fetchVendors();
        await this.fetchTransactions();
        this.isLoading = false;

        if (this.activeTab === 'stats') {
            this.$nextTick(() => {
                this.renderChart();
            });
        }
    }
};
</script>
<style scoped>
.tab-item {
    padding: 10px 25px;
    cursor: pointer;
    font-weight: 600;
    border-bottom: 2px solid transparent;
}

.tab-item.active {
    border-bottom: 3px solid black;
}

/* Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 45px;
    height: 24px;
}

.switch input {
    display: none;
}

.slider {
    position: absolute;
    background: #ccc;
    border-radius: 34px;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.slider:before {
    content: "";
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
}

input:checked+.slider {
    background: green;
}

input:checked+.slider:before {
    transform: translateX(20px);
}

.chart-container {
    position: relative;
    height: 300px;
    /* yahan height control hogi */
    width: 100%;
}
</style>
