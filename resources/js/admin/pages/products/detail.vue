<template>
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow m-4">
                <div class="card-header">
                    <h4>Product Details</h4>
                </div>

                <div class="card-body" v-if="product">
                    <!-- Main Image -->

                    <div class="text-center mb-4">
                        <img
                            :src="getImage(product.image)"
                            width="220"
                            class="img-thumbnail rounded shadow"
                        />
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="220">ID</th>
                                    <td>{{ product.id }}</td>
                                </tr>

                                <tr>
                                    <th>Category</th>
                                    <td>{{ product.category?.name }}</td>
                                </tr>

                                <tr>
                                    <th>Brand</th>
                                    <td>{{ product.brand?.name }}</td>
                                </tr>

                                <tr>
                                    <th>Name</th>
                                    <td>{{ product.name }}</td>
                                </tr>

                                <tr>
                                    <th>Slug</th>
                                    <td>{{ product.slug }}</td>
                                </tr>

                                <tr>
                                    <th>Model</th>
                                    <td>{{ product.model }}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>

                                    <td>
                                        <span
                                            class="badge bg-success"
                                            v-if="product.status == 'active'"
                                        >
                                            Active
                                        </span>

                                        <span class="badge bg-danger" v-else>
                                            Inactive
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Featured</th>

                                    <td>
                                        {{ product.is_featured ? "Yes" : "No" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Promo</th>

                                    <td>
                                        {{ product.is_promo ? "Yes" : "No" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Trending</th>

                                    <td>
                                        {{ product.is_tranding ? "Yes" : "No" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Discounted</th>

                                    <td>
                                        {{
                                            product.is_discounted ? "Yes" : "No"
                                        }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Lead Time</th>

                                    <td>{{ product.lead_time }}</td>
                                </tr>

                                <tr>
                                    <th>Created By</th>

                                    <td>
                                        {{ product.created_by?.name ?? "N/A" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Updated By</th>

                                    <td>
                                        {{ product.updated_by?.name ?? "N/A" }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Created At</th>

                                    <td>
                                        {{ formatDate(product.created_at) }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Short Description</th>
                                </tr>

                                <tr>
                                    <td v-html="product.short_description"></td>
                                </tr>

                                <tr>
                                    <th>Description</th>
                                </tr>

                                <tr>
                                    <td v-html="product.description"></td>
                                </tr>

                                <tr>
                                    <th>Keywords</th>
                                </tr>

                                <tr>
                                    <td>{{ product.keywords }}</td>
                                </tr>

                                <tr>
                                    <th>Technical Specification</th>
                                </tr>

                                <tr>
                                    <td
                                        v-html="product.technical_specification"
                                    ></td>
                                </tr>

                                <tr>
                                    <th>Uses</th>
                                </tr>

                                <tr>
                                    <td v-html="product.uses"></td>
                                </tr>

                                <tr>
                                    <th>Warranty</th>
                                </tr>

                                <tr>
                                    <td v-html="product.warranty"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr />

                    <h4 class="mb-3">Product Images</h4>

                    <div class="row">
                        <div
                            class="col-md-2 mb-3"
                            v-for="image in product.product_images"
                            :key="image.id"
                        >
                            <img
                                :src="getImage(image.image)"
                                class="img-fluid rounded border shadow-sm"
                            />
                        </div>
                    </div>

                    <hr />

                    <h4 class="mb-3">Product Attributes</h4>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>

                                <th>Color</th>

                                <th>Size</th>

                                <th>SKU</th>

                                <th>Price</th>

                                <th>MRP</th>

                                <th>Qty</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="attribute in product.product_attributes"
                                :key="attribute.id"
                            >
                                <td width="100">
                                    <img
                                        :src="
                                            getImage(attribute.attribute_image)
                                        "
                                        width="70"
                                        class="rounded"
                                    />
                                </td>

                                <td>
                                    {{ attribute.color?.name }}
                                </td>

                                <td>
                                    {{ attribute.size?.name }}
                                </td>

                                <td>
                                    {{ attribute.sku }}
                                </td>

                                <td>
                                    {{ attribute.price }}
                                </td>

                                <td>
                                    {{ attribute.maximum_retail_price }}
                                </td>

                                <td>
                                    {{ attribute.quantity }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button
                        class="btn btn-secondary mt-3"
                        @click="$router.back()"
                    >
                        Back
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { format } from "date-fns";

export default {
    data() {
        return {
            product: null,
        };
    },

    methods: {
        async getProduct() {
            const id = this.$route.params.id;

            const response = await axios.get(`products/${id}`);

            this.product = response.data.product;
        },

        getImage(path) {
            if (!path) return "";

            if (path.startsWith("http")) {
                return path;
            }

            return "http://127.0.0.1:8000/" + path;
        },

        formatDate(date) {
            if (!date) return "";

            return format(new Date(date), "dd-MM-yyyy");
        },
    },

    mounted() {
        this.getProduct();
    },
};
</script>
