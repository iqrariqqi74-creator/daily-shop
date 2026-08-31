<template>
    <section class="content">
        <div class="container-fluid">
            <!-- Loader -->
            <div v-if="isLoading" class="text-center">
                <img width="80" :src="loaderImage" />
            </div>

            <div v-else>
                <!-- TOP CARD -->
                <div class="card shadow p-4 mb-4" style="border-radius: 12px">
                    <div class="row align-items-center">
                        <!-- LEFT IMAGE -->
                        <div class="col-md-4">
                            <!-- <img :src="getImage(customer.image)" class="img-fluid"
                                style="border-radius:12px; height:220px; width:100%; object-fit:cover;"> -->
                        </div>

                        <!-- RIGHT INFO -->
                        <div class="col-md-6">
                            <h4 class="font-weight-bold">
                                {{ customer.name }}
                            </h4>
                            <small class="text-muted">
                                Customer since
                                {{ formatDate(customer.created_at) }}
                            </small>

                            <hr />

                            <p>
                                <i class="fas fa-envelope text-danger mr-2"></i>
                                {{ customer.email }}
                            </p>
                            <p>
                                <i class="fas fa-phone text-danger mr-2"></i>
                                {{ customer.phone_number }}
                            </p>
                            <p>
                                <i
                                    class="fas fa-map-marker-alt text-danger mr-2"
                                ></i>
                                <span v-html="customer.address"></span>
                            </p>
                            <p>
                                <i
                                    class="fas fa-calendar-alt text-danger mr-2"
                                ></i>
                                {{ formatDate(customer.created_at) }}
                            </p>
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-2 text-right">
                            <label class="switch">
                                <input
                                    type="checkbox"
                                    :checked="customer.status == 1"
                                    @change="confirmToggle"
                                />
                                <span class="slider round"></span>
                            </label>

                            <div class="mt-2">
                                <span
                                    v-if="customer.status == 1"
                                    class="badge badge-success"
                                    >Active</span
                                >
                                <span v-else class="badge badge-danger"
                                    >Inactive</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 🔥 TABS (CENTER BELOW IMAGE — SAME AS DESIGN) -->
                <div class="card shadow p-2 mb-3" style="border-radius: 12px">
                    <div class="d-flex justify-content-start">
                        <div
                            class="tab-item"
                            :class="{ active: activeTab === 'profile' }"
                            @click="activeTab = 'profile'"
                        >
                            Profile
                        </div>

                        <div
                            class="tab-item"
                            :class="{ active: activeTab === 'orders' }"
                            @click="activeTab = 'orders'"
                        >
                            Order History
                        </div>

                        <div
                            class="tab-item"
                            :class="{ active: activeTab === 'status' }"
                            @click="activeTab = 'status'"
                        >
                            Status
                        </div>
                    </div>
                </div>

                <!-- TAB CONTENT -->

                <!-- PROFILE -->
                <div v-if="activeTab === 'profile'" class="card shadow p-4">
                    <h5 class="mb-3">Profile Info</h5>
                    <p><b>Name:</b> {{ customer.name }}</p>
                    <p><b>Email:</b> {{ customer.email }}</p>
                    <p><b>Phone:</b> {{ customer.phone_number }}</p>
                    <p>
                        <b>Status:</b>
                        <span v-if="customer.status == 1" class="text-success"
                            >Active</span
                        >
                        <span v-else class="text-danger">Inactive</span>
                    </p>
                </div>

                <!-- ORDERS -->
                <div v-if="activeTab === 'orders'" class="card shadow p-3">
                    <h5 class="mb-3">Order History</h5>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div
                                class="d-flex align-items-center justify-content-center"
                                v-if="isLoading"
                            >
                                <div>
                                    <img width="100" :src="loaderImage" />
                                    <p class="text-center mt-0">
                                        Please wait...
                                    </p>
                                </div>
                            </div>
                            <table
                                v-show="!isLoading"
                                class="table table-striped"
                                id="dataTable"
                                width="100%"
                                cellspacing="0"
                            >
                                <thead>
                                    <tr>


                                        <th>
                                            Payment Type
                                            <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'payment_type',
                                                        'asc',
                                                    )
                                                "
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'payment_type',
                                                        'desc',
                                                    )
                                                "
                                            ></i>
                                        </th>
                                        <th>
                                            Created Date
                                            <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'order_start_date',
                                                        'asc',
                                                    )
                                                "
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'order_start_date',
                                                        'desc',
                                                    )
                                                "
                                            ></i>
                                        </th>
                                        <th>
                                           Total Amount
                                            <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="sortBy('amount', 'asc')"
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy('amount', 'desc')
                                                "
                                            ></i>
                                        </th>
                                        <th>
                                            Payment Status
                                            <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'payment_status',
                                                        'asc',
                                                    )
                                                "
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy(
                                                        'payment_status',
                                                        'desc',
                                                    )
                                                "
                                            ></i>
                                        </th>
                                        <th>
                                            Tax Amount
                                             <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy('promo_code', 'asc')
                                                "
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy('promo_code', 'desc')
                                                "
                                            ></i>
                                        </th>

                                        <th>
                                            Status
                                            <i
                                                class="fas fa-arrow-down sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="sortBy('status', 'asc')"
                                            ></i>
                                            <i
                                                class="fas fa-arrow-up sm-d-none ml-1"
                                                style="font-size: 8.5px"
                                                @click="
                                                    sortBy('status', 'desc')
                                                "
                                            ></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="orders.length == 0">
                                        <td colspan="12">
                                            <h4 class="text-center">
                                                No data found.
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="(order, index) in orders"
                                        :key="index"
                                         @click="openOrderModal(order.id)"
                                         style="cursor: pointer;">
                                        <td>
                                            <span class="badge badge-success" v-if="order.payment_type === 'COD'">Cash on Delivery</span>
                                            <span class="badge badge-primary" v-else-if="order.payment_type === 'card'">Card</span>
                                        </td>
                                        <td>{{ order.added_on }}</td>
                                        <td> RS: {{ order.total_amount }}</td>
                                        <td>
                                            <span
                                                class="badge badge-warning"
                                                v-if="
                                                    order.payment_status ==
                                                1
                                                "
                                            >
                                            Pending
                                             </span>
                                            <span
                                                class="badge badge-success"
                                                v-else-if="
                                                    order.payment_status ==
                                                    2
                                                "
                                                >Completed</span
                                            >
                                            <span
                                                class="badge badge-danger"
                                                v-else-if="
                                                    order.payment_status ==
                                                    3
                                                "
                                                >Cancelled</span
                                            >
                                            <span class="badge badge-info" v-else-if="order.payment_status == 4"
                                                >Processing</span
                                            >

                                        </td>
                                        <td>{{ order.tax_amount }}</td>

                                        <td>
                                            <span
                                                class="badge badge-warning"
                                                v-if="order.order_status == 1"
                                                >Pending</span
                                            >
                                            <span
                                                class="badge badge-success"
                                                v-else-if="
                                                    order.order_status == 2
                                                "
                                                >Completed</span
                                            >
                                            <span
                                                class="badge badge-secondary"
                                                v-else-if="
                                                    order.order_status == 3
                                                "
                                                >Cancelled</span
                                            >
                                            <span
                                                class="badge badge-info"
                                                v-else-if="
                                                    order.order_status == 4
                                                "
                                                >Processing</span
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-show="!isLoading">
                            <pagination
                                :totalPages="totalPages"
                                :perPage="50"
                                :currentPage="currentPage"
                                @pageChanged="onPageChange"
                            />
                        </div>
                    </div>
                </div>

                <!-- STAtus -->
                <div v-if="activeTab === 'status'" class="card shadow p-4">
                    <h5 class="mb-4">Customer Status</h5>
                    <div class="chart-container">
                        <canvas ref="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
       <!-- =====================================================
            ORDER DETAIL MODAL
        ===================================================== -->

        <div
            class="modal fade"
            id="orderDetailModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="orderDetailModalLabel"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-xl"
                role="document"
                style="max-width: 95%;"
            >
                <div class="modal-content">

                    <!-- =================================================
                        MODAL HEADER
                    ================================================== -->

                    <div class="modal-header bg-primary">

                        <h5
                            class="modal-title text-white"
                            id="orderDetailModalLabel"
                        >
                            <i class="fas fa-shopping-cart mr-2"></i>

                            Order Details

                            <span v-if="selectedOrder">
                                #{{ selectedOrder.id }}
                            </span>
                        </h5>

                        <button
                            type="button"
                            class="close text-white"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>


                    <!-- =================================================
                        MODAL BODY
                    ================================================== -->

                    <div class="modal-body">

                        <!-- LOADING -->

                        <div
                            v-if="modalLoading"
                            class="text-center py-5"
                        >

                            <div
                                class="spinner-border text-primary"
                                role="status"
                            >
                                <span class="sr-only">
                                    Loading...
                                </span>
                            </div>

                            <p class="mt-3 text-muted">
                                Loading order details...
                            </p>

                        </div>


                        <!-- DATA -->

                        <div
                            v-else-if="selectedOrder"
                        >

                            <!-- =================================================
                                ORDER + CUSTOMER
                            ================================================== -->

                            <div class="row">

                                <!-- ORDER INFORMATION -->

                                <div class="col-md-8">

                                    <div class="card shadow-sm">

                                        <div class="card-header">

                                            <h6 class="mb-0 font-weight-bold text-primary">

                                                <i class="fas fa-receipt mr-2"></i>

                                                Order Information

                                            </h6>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <!-- ORDER ID -->

                                                <div class="col-md-4 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Order ID
                                                        </small>

                                                        <strong>
                                                            #{{ selectedOrder.id }}
                                                        </strong>

                                                    </div>

                                                </div>


                                                <!-- ORDER DATE -->

                                                <div class="col-md-4 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Order Date
                                                        </small>

                                                        <strong>
                                                            {{ selectedOrder.added_on || "N/A" }}
                                                        </strong>

                                                    </div>

                                                </div>


                                                <!-- PAYMENT TYPE -->

                                                <div class="col-md-4 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Payment Type
                                                        </small>

                                                        <strong>
                                                            {{ selectedOrder.payment_type || "N/A" }}
                                                        </strong>

                                                    </div>

                                                </div>


                                                <!-- ORDER STATUS -->

                                                <div class="col-md-6 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Order Status
                                                        </small>

                                                        <span
                                                            class="badge"
                                                            :class="
                                                                getOrderStatusBadge(
                                                                    selectedOrder.order_status
                                                                )
                                                            "
                                                        >
                                                            {{
                                                                getOrderStatusText(
                                                                    selectedOrder.order_status
                                                                )
                                                            }}
                                                        </span>

                                                    </div>

                                                </div>


                                                <!-- PAYMENT STATUS -->

                                                <div class="col-md-6 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Payment Status
                                                        </small>

                                                        <span
                                                            class="badge"
                                                            :class="
                                                                getPaymentStatusBadge(
                                                                    selectedOrder.payment_status
                                                                )
                                                            "
                                                        >
                                                            {{
                                                                getPaymentStatusText(
                                                                    selectedOrder.payment_status
                                                                )
                                                            }}
                                                        </span>

                                                    </div>

                                                </div>


                                                <!-- PAYMENT ID -->

                                                <div
                                                    class="col-md-6 mb-3"
                                                    v-if="selectedOrder.payment_id"
                                                >

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Payment ID
                                                        </small>

                                                        <strong>
                                                            {{ selectedOrder.payment_id }}
                                                        </strong>

                                                    </div>

                                                </div>


                                                <!-- TRACK DETAILS -->

                                                <div class="col-md-6 mb-3">

                                                    <div class="modal-detail-box">

                                                        <small>
                                                            Track Details
                                                        </small>

                                                        <strong>
                                                            {{ selectedOrder.track_details || "N/A" }}
                                                        </strong>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- CUSTOMER -->

                                <div class="col-md-4">

                                    <div class="card shadow-sm h-100">

                                        <div class="card-header">

                                            <h6 class="mb-0 font-weight-bold text-primary">

                                                <i class="fas fa-user mr-2"></i>

                                                Customer

                                            </h6>

                                        </div>

                                        <div class="card-body">

                                            <h5 class="font-weight-bold">

                                                {{ selectedOrder.name || "N/A" }}

                                            </h5>

                                            <p class="text-muted mb-2">

                                                {{ selectedOrder.email || "N/A" }}

                                            </p>

                                            <p class="text-muted">

                                                <i class="fas fa-phone mr-1"></i>

                                                {{ selectedOrder.mobile || "N/A" }}

                                            </p>

                                            <hr>

                                            <strong>
                                                Address
                                            </strong>

                                            <p class="text-muted mt-2">

                                                {{ selectedOrder.address || "N/A" }}

                                                <br>

                                                {{ selectedOrder.city || "" }}

                                                <br>

                                                {{ selectedOrder.state || "" }}

                                                <br>

                                                {{ selectedOrder.pin_code || "" }}

                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- =================================================
                                PRODUCTS
                            ================================================== -->

                            <div class="card shadow-sm mt-3">

                                <div class="card-header">

                                    <h6 class="mb-0 font-weight-bold text-primary">

                                        <i class="fas fa-box mr-2"></i>

                                        Order Products

                                        <span class="badge badge-primary ml-2">
                                            {{ selectedOrderDetails.length }}
                                            Product(s)
                                        </span>

                                    </h6>

                                </div>


                                <div class="card-body p-0">

                                    <div class="table-responsive">

                                        <table class="table table-hover mb-0">

                                            <thead class="thead-light">

                                                <tr>

                                                    <th>#</th>

                                                    <th>
                                                        Product
                                                    </th>

                                                    <th>
                                                        SKU
                                                    </th>

                                                    <th>
                                                        Size
                                                    </th>

                                                    <th>
                                                        Color
                                                    </th>

                                                    <th>
                                                        Price
                                                    </th>

                                                    <th>
                                                        Tax
                                                    </th>

                                                    <th>
                                                        Qty
                                                    </th>

                                                    <th class="text-right">
                                                        Total
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                <tr
                                                    v-for="(item, index) in selectedOrderDetails"
                                                    :key="item.id || index"
                                                >

                                                    <!-- NUMBER -->

                                                    <td>
                                                        {{ index + 1 }}
                                                    </td>


                                                    <!-- PRODUCT -->

                                                    <td>

                                                        <div class="d-flex align-items-center">

                                                            <div class="modal-product-image mr-2">

                                                                <img
                                                                    v-if="
                                                                        item.product_attribute
                                                                            ?.attribute_image
                                                                    "
                                                                    :src="
                                                                        getImage(
                                                                            item.product_attribute
                                                                                .attribute_image
                                                                        )
                                                                    "
                                                                    alt="Product"
                                                                />

                                                                <i
                                                                    v-else
                                                                    class="fas fa-image text-muted"
                                                                ></i>

                                                            </div>


                                                            <div>

                                                                <strong>

                                                                    {{
                                                                        item
                                                                            .product_attribute
                                                                            ?.product
                                                                            ?.name ||
                                                                        "N/A"
                                                                    }}

                                                                </strong>

                                                                <br>

                                                                <small class="text-muted">

                                                                    Product ID:
                                                                    {{ item.product_id }}

                                                                </small>

                                                            </div>

                                                        </div>

                                                    </td>


                                                    <!-- SKU -->

                                                    <td>

                                                        {{
                                                            item.product_attribute
                                                                ?.sku || "N/A"
                                                        }}

                                                    </td>


                                                    <!-- SIZE -->

                                                    <td>

                                                        {{
                                                            item.product_attribute
                                                                ?.size?.name || "N/A"
                                                        }}

                                                    </td>


                                                    <!-- COLOR -->

                                                    <td>

                                                        {{
                                                            item.product_attribute
                                                                ?.color?.name || "N/A"
                                                        }}

                                                    </td>


                                                    <!-- PRICE -->

                                                    <td>

                                                        Rs
                                                        {{
                                                            Number(
                                                                item.price || 0
                                                            ).toFixed(2)
                                                        }}

                                                    </td>


                                                    <!-- TAX -->

                                                    <td>

                                                        <span
                                                            v-if="
                                                                item.product_attribute
                                                                    ?.product
                                                                    ?.tax
                                                            "
                                                            class="badge badge-warning"
                                                        >

                                                            {{
                                                                item
                                                                    .product_attribute
                                                                    .product
                                                                    .tax
                                                                    .name
                                                            }}

                                                            :

                                                            {{
                                                                item
                                                                    .product_attribute
                                                                    .product
                                                                    .tax
                                                                    .value || 0
                                                            }}

                                                        </span>

                                                        <span v-else>
                                                            N/A
                                                        </span>

                                                    </td>


                                                    <!-- QTY -->

                                                    <td>

                                                        <span class="badge badge-light">

                                                            {{ item.quantity }}

                                                        </span>

                                                    </td>


                                                    <!-- TOTAL -->

                                                    <td class="text-right font-weight-bold">

                                                        Rs
                                                        {{
                                                            (
                                                                Number(
                                                                    item.price || 0
                                                                ) *
                                                                Number(
                                                                    item.quantity || 0
                                                                )
                                                            ).toFixed(2)
                                                        }}

                                                    </td>

                                                </tr>

                                            </tbody>


                                            <!-- =================================================
                                                SUMMARY
                                            ================================================== -->

                                            <tfoot>

                                                <!-- SUB TOTAL -->

                                                <tr>

                                                    <td
                                                        colspan="8"
                                                        class="text-right font-weight-bold"
                                                    >
                                                        Sub Total
                                                    </td>

                                                    <td
                                                        class="text-right font-weight-bold"
                                                    >

                                                        Rs
                                                        {{ modalSubTotal.toFixed(2) }}

                                                    </td>

                                                </tr>


                                                <!-- COUPON CODE -->

                                                <tr>

                                                    <td
                                                        colspan="8"
                                                        class="text-right font-weight-bold"
                                                    >
                                                        Coupon Code
                                                    </td>

                                                    <td
                                                        class="text-right"
                                                    >

                                                        {{
                                                            selectedOrder.coupon_code ||
                                                            "No Coupon"
                                                        }}

                                                    </td>

                                                </tr>


                                                <!-- DISCOUNT -->

                                                <tr>

                                                    <td
                                                        colspan="8"
                                                        class="text-right font-weight-bold"
                                                    >
                                                        Discount
                                                    </td>

                                                    <td
                                                        class="text-right text-danger font-weight-bold"
                                                    >

                                                        -

                                                        Rs
                                                        {{
                                                            Number(
                                                                selectedOrder.coupon_value ||
                                                                    0
                                                            ).toFixed(2)
                                                        }}

                                                    </td>

                                                </tr>


                                                <!-- TAX AMOUNT -->

                                                <tr>

                                                    <td
                                                        colspan="8"
                                                        class="text-right font-weight-bold"
                                                    >
                                                        Tax Amount
                                                    </td>

                                                    <td
                                                        class="text-right text-warning font-weight-bold"
                                                    >

                                                        +

                                                        Rs
                                                        {{
                                                            Number(
                                                                selectedOrder.tax_amount ||
                                                                    0
                                                            ).toFixed(2)
                                                        }}

                                                    </td>

                                                </tr>


                                                <!-- GRAND TOTAL -->

                                                <tr>

                                                    <td
                                                        colspan="8"
                                                        class="text-right font-weight-bold"
                                                    >
                                                        Grand Total
                                                    </td>

                                                    <td
                                                        class="text-right text-success font-weight-bold"
                                                    >

                                                        Rs
                                                        {{
                                                            Number(
                                                                selectedOrder.total_amount ||
                                                                    0
                                                            ).toFixed(2)
                                                        }}

                                                    </td>

                                                </tr>

                                            </tfoot>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- NO DATA -->

                        <div
                            v-else
                            class="text-center py-5"
                        >

                            <i
                                class="fas fa-box-open fa-3x text-muted mb-3"
                            ></i>

                            <h5>
                                Order details not found.
                            </h5>

                        </div>

                    </div>


                    <!-- =================================================
                        MODAL FOOTER
                    ================================================== -->

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </section>
</template>
<script>
import axios from "axios";
import { format } from "date-fns";
import Chart from "chart.js/auto";
export default {
    data() {
        return {
            customer: {},
            orders: [],
            activeTab: "profile",
            isLoading: true,
            loaderImage: "/images/Spinning arrows.gif",
            chart: null,
            selectedOrderId: null,
            modalLoading: false,

            selectedOrder: null,

            selectedOrderDetails: [],
        };
    },
    computed: {

        // aapke existing computed...

        modalSubTotal() {
            return this.selectedOrderDetails.reduce((total, item) => {

                const price = Number(item.price || 0);

                const quantity = Number(item.quantity || 0);

                return total + price * quantity;

            }, 0);
        },

    },

    methods: {
        async openOrderModal(orderId) {

        // OLD DATA CLEAR

        this.selectedOrder = null;

        this.selectedOrderDetails = [];

        this.modalLoading = true;

        // MODAL OPEN
        $("#orderDetailModal").modal("show");


        try {

            // ==========================================
            // GET ORDER DETAILS BY ORDER ID
            // ==========================================

            const response = await axios.get(
                `/order/details/${orderId}`
            );


            // ==========================================
            // SUCCESS
            // ==========================================

            if (response.data.status === "success") {

                this.selectedOrderDetails =
                    response.data.orders_details || [];


                // ======================================
                // MAIN ORDER
                // ======================================

                if (this.selectedOrderDetails.length > 0) {

                    this.selectedOrder =
                        this.selectedOrderDetails[0].order ||
                        this.selectedOrderDetails[0];

                }

            } else {

                this.$toast.error(
                    response.data.message ||
                    "Failed to fetch order details."
                );

            }

        } catch (error) {

            console.error(error);

            this.$toast.error(
                error.response?.data?.message ||
                "Unable to fetch order details."
            );

        } finally {

            this.modalLoading = false;

        }
    },

    // ==========================================
        // DATE
        // ==========================================

        formatDate(date) {
            if (!date) {
                return "N/A";
            }

            try {
                return format(new Date(date), "dd-MM-yyyy hh:mm a");
            } catch (error) {
                return date;
            }
        },

        // ==========================================
        // ORDER STATUS TEXT
        // ==========================================

        getOrderStatusText(status) {
            switch (Number(status)) {
                case 1:
                    return "Placed";

                case 2:
                    return "On The Way";

                case 3:
                    return "Delivered";

                case 4:
                    return "Cancelled";

                default:
                    return "Unknown";
            }
        },

        // ==========================================
        // ORDER STATUS BADGE
        // ==========================================

        getOrderStatusBadge(status) {
            switch (Number(status)) {
                case 1:
                    return "badge-warning";

                case 2:
                    return "badge-primary";

                case 3:
                    return "badge-success";

                case 4:
                    return "badge-danger";

                default:
                    return "badge-secondary";
            }
        },

        // ==========================================
        // PAYMENT STATUS TEXT
        // ==========================================

        getPaymentStatusText(status) {
            switch (Number(status)) {
                case 1:
                    return "Pending";

                case 2:
                    return "Completed";

                case 3:
                    return "Cancelled";

                case 4:
                    return "Processing";

                default:
                    return "Unknown";
            }
        },

        // ==========================================
        // PAYMENT STATUS BADGE
        // ==========================================

        getPaymentStatusBadge(status) {
            switch (Number(status)) {
                case 1:
                    return "badge-warning";

                case 2:
                    return "badge-success";

                case 3:
                    return "badge-danger";

                case 4:
                    return "badge-secondary";

                default:
                    return "badge-dark";
            }
        },

        async fetchCustomer() {
            const id = this.$route.params.id;
            const res = await axios.get(`customers/${id}`);
            this.customer = res.data.customer;
        },

        async fetchOrders() {
            const id = this.$route.params.id;
            const res = await axios.get(`customers/${id}/orders`);
            this.orders = res.data.orders || [];
        },

        getImage(path) {

            if (!path) {
                return "";
            }

            if (
                path.startsWith("http://") ||
                path.startsWith("https://")
            ) {
                return path;
            }

            return `${window.location.origin}/${path}`;
        },


        renderChart() {
            if (!this.orders.length) return;

            const canvas = this.$refs.ordersChart;

            if (!canvas) return;

            const ctx = canvas.getContext("2d");

            // Destroy old chart
            if (this.chart) {
                this.chart.destroy();
            }

            // ==========================================
            // ORDER STATUS COUNT
            // ==========================================

            let statusCounts = {
                1: 0, // Placed
                2: 0, // On The Way
                3: 0, // Delivered
                4: 0, // Cancelled
            };

            this.orders.forEach((order) => {
                const status = Number(order.order_status);

                if (statusCounts[status] !== undefined) {
                    statusCounts[status]++;
                }
            });

            // ==========================================
            // CHART
            // ==========================================

            this.chart = new Chart(ctx, {
                type: "doughnut",

                data: {
                    labels: [
                        "Placed",
                        "On The Way",
                        "Delivered",
                        "Cancelled",
                    ],

                    datasets: [
                        {
                            data: [
                                statusCounts[1],
                                statusCounts[2],
                                statusCounts[3],
                                statusCounts[4],
                            ],

                            backgroundColor: [
                                "#ffc107", // Placed
                                "#17a2b8", // On The Way
                                "#28a745", // Delivered
                                "#dc3545", // Cancelled
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
                            position: "bottom",
                        },
                    },
                },
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
                const id = this.customer.id;

                const res = await axios.post(`customers/${id}/status`, {
                    status: this.customer.status == 1 ? 0 : 1,
                });

                this.customer.status = res.data.status;
                this.fetchCustomer();
                if (res.data.status == 1) {
                    this.$toast.success("Customer activated successfully");
                } else {
                    this.$toast.success("Customer deactivated successfully");
                }
            }
        },
    },

    watch: {
        activeTab(newTab) {
            if (newTab === "status") {
                setTimeout(() => {
                    this.renderChart();
                }, 300);
            }
        },
    },
    async mounted() {
        await this.fetchCustomer();
        await this.fetchOrders();
        this.isLoading = false;

        if (this.activeTab === "status") {
            this.$nextTick(() => {
                this.renderChart();
            });
        }
    },
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

input:checked + .slider {
    background: green;
}

input:checked + .slider:before {
    transform: translateX(20px);
}

.chart-container {
    position: relative;
    height: 300px;
    /* yahan height control hogi */
    width: 100%;
}
/* =====================================================
   ORDER MODAL
===================================================== */

.modal-detail-box {
    background: #f8f9fa;
    border: 1px solid #eeeeee;
    border-radius: 6px;
    padding: 12px 15px;
    min-height: 70px;
}

.modal-detail-box small {
    display: block;
    color: #858796;
    font-size: 12px;
    margin-bottom: 6px;
}

.modal-detail-box strong {
    display: block;
    word-break: break-word;
}


/* =====================================================
   MODAL PRODUCT IMAGE
===================================================== */

.modal-product-image {
    width: 55px;
    height: 55px;
    border-radius: 6px;
    overflow: hidden;
    background: #f5f5f5;
    border: 1px solid #eeeeee;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;
}

.modal-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


/* =====================================================
   CLICKABLE ORDER ROW
===================================================== */

.table tbody tr {
    cursor: pointer;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
