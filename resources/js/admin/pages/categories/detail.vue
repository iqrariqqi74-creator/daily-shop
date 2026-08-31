<template>
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow m-4">
                <div class="card-header">
                    <h4>Category Details</h4>
                </div>

                <div class="card-body" v-if="category">
                    <div class="text-center mb-4">
                        <img
                            :src="getImage(category.image)"
                            width="150"
                            class="rounded"
                            alt="Category Image"
                        >
                    </div>

                    <div class="row">
                        <!-- Category Details -->
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="200">ID</th>
                                        <td>{{ category.id }}</td>
                                    </tr>

                                    <tr>
                                        <th>Name</th>
                                        <td>{{ category.name }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span
                                                class="badge bg-success"
                                                v-if="category.status == 'active'"
                                            >
                                                Active
                                            </span>

                                            <span
                                                class="badge bg-danger"
                                                v-else
                                            >
                                                Inactive
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created By</th>
                                        <td>
                                            {{ category.created_by ? category.created_by.name : 'N/A' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Updated By</th>
                                        <td>
                                            {{ category.updated_by ? category.updated_by.name : 'N/A' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Description</th>
                                        <td v-html="category.description || '-'"></td>
                                    </tr>

                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ formatDate(category.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sub Categories -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Sub Categories</h5>

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody v-if="category.children && category.children.length">
                                    <tr
                                        v-for="(child, index) in category.children"
                                        :key="child.id"
                                    >
                                        <td>{{ index + 1 }}</td>

                                        <td>{{ child.name }}</td>

                                        <td>
                                            <span
                                                class="badge bg-success"
                                                v-if="child.status == 'active'"
                                            >
                                                Active
                                            </span>

                                            <span
                                                class="badge bg-danger"
                                                v-else
                                            >
                                                Inactive
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>

                                <tbody v-else>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No Sub Categories Found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <button
                        class="btn btn-secondary my-3"
                        @click="$router.back()"
                    >
                        Back
                    </button>
                </div>

                <div class="card-body text-center" v-else>
                    Loading...
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
            category: null,
        };
    },

    methods: {
        getImage(path) {
            if (!path) return "";

            if (path.startsWith("http")) {
                return path;
            }

            return "http://127.0.0.1:8000/" + path;
        },

        async getCategory() {
            try {
                const id = this.$route.params.id;

                const response = await axios.get(`/categories/${id}`);

                this.category = response.data.category;
            } catch (error) {
                console.error(error);
            }
        },

        formatDate(date) {
            if (!date) return "";
            return format(new Date(date), "dd-MM-yyyy");
        },
    },

    mounted() {
        this.getCategory();
    },
};
</script>