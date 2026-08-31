<template>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <router-link to="/admin"><b>Admin</b>LTE</router-link>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">You're only one step away from your new password.</p>

                    <form @submit.prevent="submit">
                        <div class="input-group mb-3">
                            <input @input="clearErrorMessage('password')" type="password" class="form-control"
                                placeholder="New Password" v-model="form.password" :class="{
                                    'is-invalid': validationErrors.password,
                                    'is-valid': !validationErrors.password && form.password
                                }" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>
                        <small v-if="validationErrors.password" class="text-danger">
                            {{ validationErrors.password[0] }}
                        </small>

                        <div class="input-group mb-3">
                            <input @input="clearErrorMessage('password_confirmation')" type="password"
                                class="form-control" placeholder="Confirm Password" v-model="form.password_confirmation"
                                :class="{
                                    'is-invalid': validationErrors.password_confirmation,
                                    'is-valid': !validationErrors.password_confirmation && form.password_confirmation
                                }" />
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-lock"></span></div>
                            </div>
                        </div>
                        <small v-if="validationErrors.password_confirmation" class="text-danger">
                            {{ validationErrors.password_confirmation[0] }}
                        </small>

                        <small class="text-danger d-block" v-if="error">{{ error }}</small>
                        <small class="text-success d-block" v-if="success">{{ success }}</small>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                    <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                                    <span v-else>Change Password</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <p class="mt-3 mb-1">
                        <router-link to="/admin/login">Login</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "AdminReset",

    data() {
        return {
            form: {
                email: "",
                token: "",
                password: "",
                password_confirmation: "",
            },
            validationErrors: {},
            loading: false,
            error: null,
            success: null,
        };
    },

    mounted() {
        this.form.email = this.$route.query.email || "";
        this.form.token = this.$route.query.token || "";

        if (!this.form.email || !this.form.token) {
            this.error = "Invalid reset link.";
        }
    },

    methods: {
        async submit() {
            this.loading = true;
            this.error = null;
            this.success = null;
            this.validationErrors = {};

            try {
                const response = await axios.post("/admin/reset", this.form);

                this.success = response.data.message || "Password has been reset successfully.";
                this.$toast.success(this.success, {
                    position: 'top-right',
                    duration: 3000
                });

                this.form.password = "";
                this.form.password_confirmation = "";
            } catch (err) {
                if (err.response?.status === 422) {
                    this.validationErrors = err.response.data.errors || {};
                } else {
                    this.error = err.response?.data?.message || "Something went wrong.";
                    this.$toast.error(this.error, {
                        position: 'top-right',
                        duration: 4000
                    });
                }
            } finally {
                this.loading = false;
            }
        },
        clearErrorMessage(key) {
            this.validationErrors[key] = null;
        },
    },
};
</script>

<style scoped>
/* Optional */
</style>
