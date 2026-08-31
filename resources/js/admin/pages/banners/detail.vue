<template>
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow m-4">
                <div class="card-header">
                    <h4>banner Details</h4>
                </div>
                <div class="card-body" v-if="banner">
                    <div class="text-center mb-4">
                        <img
                            :src="getImage(banner.image)"
                            width="150"
                            class="rounded"
                        />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">ID</th>
                                    <td>{{ banner.id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ banner.name }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span
                                            class="badge badge-success"
                                            v-if="banner.status == 'active'"
                                        >
                                            Active
                                        </span>
                                        <span class="badge badge-danger" v-else>
                                            Inactive
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>
                                        {{
                                            banner.created_by
                                                ? banner.created_by.name
                                                : "N/A"
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Updated By</th>
                                    <td>
                                        {{
                                            banner.updated_by
                                                ? banner.updated_by.name
                                                : "N/A"
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ formatDate(banner.created_at) }}</td>
                                </tr>
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
            banner: null,
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

        async getBanner() {
            const id = this.$route.params.id;

            const response = await axios.get(`banners/${id}`);

            this.banner = response.data.banner;
        },

        formatDate(date) {
            if (!date) return "";
            return format(new Date(date), "dd-MM-yyyy"); // 30-11-2025 format
        },
    },

    mounted() {
        this.getBanner();
    },
};
</script>
