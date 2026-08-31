<template>
    <section class="content">
        <div class="container-fluid">

            <!-- Loader -->
            <div v-if="isLoading" class="text-center">
                <img width="80" :src="loaderImage" />
            </div>

            <div v-else>

                <!-- Date Filter -->
                <div class="card shadow p-4 mb-4" style="border-radius:12px;">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label>From:</label>
                            <input type="date" v-model="filter.from" class="form-control">
                        </div>
                        <div class="col-auto">
                            <label>To:</label>
                            <input type="date" v-model="filter.to" class="form-control">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary mt-4" @click="applyFilter">
                                Apply Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Metrics -->
                <div class="row mb-4">
                    <div class="col-md-3" v-for="(metric, index) in metrics" :key="metric.label">
                        <div class="card shadow p-3 border-start border-4" :style="{ borderColor: colors[index] }"
                            style="border-radius:12px; height:180px;">

                            <h6>{{ metric.label }}</h6>

                            <canvas :ref="metric.ref" style="height:100px;"></canvas>

                            <h5 class="text-center mt-2">{{ metric.value }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Big Chart -->
                <div class="card shadow p-4 mb-4" style="border-radius:12px; height:400px;">
                    <canvas ref="bigChart"></canvas>
                </div>

            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import Chart from "chart.js/auto";
import { format, differenceInHours, isWithinInterval, parseISO } from "date-fns";
import { nextTick } from "vue";

export default {
    data() {
        return {
            orders: [],
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",

            filter: {
                from: "",
                to: ""
            },

            // 🎨 COLORS
            colors: [
                "#28a745", // green
                "#007bff", // blue
                "#ffc107", // yellow
                "#dc3545"  // red
            ],

            metrics: [
                { label: "TOTAL ORDERS", value: 0, ref: "totalOrdersChart", chart: null },
                { label: "AVG SERVICE TIME", value: 0, ref: "avgServiceChart", chart: null },
                { label: "COMPLETION RATE", value: "0%", ref: "completionChart", chart: null },
                { label: "ORDER VOLUME", value: 0, ref: "orderVolumeChart", chart: null }
            ],

            bigChart: null
        };
    },

    methods: {

        // 🔥 FETCH DATA
        async getOrders() {
            try {
                this.isLoading = true;

                const res = await axios.get("orders");
                this.orders = res.data.orders || [];

                this.calculateMetrics();

                this.isLoading = false;

                await nextTick();

                this.renderMetricCharts();
                this.renderBigChart();

            } catch (e) {
                console.error(e);
                this.isLoading = false;
            }
        },

        // 🔥 FILTER
        async applyFilter() {
            if (!this.filter.from || !this.filter.to) return;

            const fromDate = parseISO(this.filter.from);
            const toDate = parseISO(this.filter.to);

            const filtered = this.orders.filter(o => {
                const d = parseISO(o.order_start_date);
                return isWithinInterval(d, { start: fromDate, end: toDate });
            });

            this.calculateMetrics(filtered);

            await nextTick();

            this.renderMetricCharts();
            this.renderBigChart(filtered);
        },

        // 🔥 CALCULATE
        calculateMetrics(orders = null) {
            orders = orders || this.orders;

            const total = orders.length;

            const completed = orders.filter(o => o.status === "completed").length;
            const rate = total ? ((completed / total) * 100).toFixed(1) : 0;

            let hours = 0;
            orders.forEach(o => {
                const start = new Date(o.order_start_date);
                const end = new Date(o.order_end_date);
                if (start && end) {
                    hours += differenceInHours(end, start);
                }
            });

            const avg = total ? (hours / total).toFixed(2) : 0;

            const volume = orders.reduce((sum, o) => sum + parseFloat(o.amount || 0), 0);

            this.metrics[0].value = total;
            this.metrics[1].value = avg + " hrs";
            this.metrics[2].value = rate + "%";
            this.metrics[3].value = volume.toFixed(2);
        },

        // 🔥 METRIC CHARTS WITH COLORS
        renderMetricCharts() {
            this.metrics.forEach((metric, index) => {

                let canvas = this.$refs[metric.ref];

                if (Array.isArray(canvas)) {
                    canvas = canvas[0];
                }

                if (!canvas || !canvas.getContext) return;

                const ctx = canvas.getContext("2d");

                if (metric.chart) {
                    metric.chart.destroy();
                }

                let data = [100];

                if (metric.label === "COMPLETION RATE") {
                    const val = parseFloat(this.metrics[2].value);
                    data = [val, 100 - val];
                }

                metric.chart = new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        datasets: [{
                            data,
                            backgroundColor: [
                                this.colors[index],
                                "#e9ecef"
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "70%",
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        },

        // 🔥 BIG CHART
        renderBigChart(orders = null) {
            orders = orders || this.orders;

            let canvas = this.$refs.bigChart;

            if (!canvas || !canvas.getContext) return;

            const ctx = canvas.getContext("2d");

            if (this.bigChart) {
                this.bigChart.destroy();
            }

            const labels = [];
            const data = [];

            orders.forEach(o => {
                const d = format(new Date(o.order_start_date), "dd MMM");

                const i = labels.indexOf(d);
                if (i === -1) {
                    labels.push(d);
                    data.push(1);
                } else {
                    data[i]++;
                }
            });

            this.bigChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels,
                    datasets: [{
                        label: "Orders",
                        data,
                        borderColor: "#4e73df",
                        backgroundColor: "rgba(78,115,223,0.15)",
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    },

    mounted() {
        this.getOrders();
    }
};
</script>

<style scoped>
.card {
    text-align: center;
}
</style>
