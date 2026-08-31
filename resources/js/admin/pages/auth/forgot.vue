<template>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <router-link to="/admin"><b>Admin</b>LTE</router-link>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">
                        You forgot your password? Enter your email to get a reset link.
                    </p>

                    <form @submit.prevent="submit">
                        <div class="input-group mb-3">
                            <input @input="clearErrorMessage('email')" type="email" class="form-control"
                                placeholder="Email" v-model="email" :class="{
                                    'is-invalid': validationErrors.email,
                                    'is-valid': !validationErrors.email && email
                                }" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <small v-if="validationErrors.email" class="text-danger">
                            {{ validationErrors.email[0] }}
                        </small>

                        <small class="text-danger d-block" v-if="error">{{ error }}</small>
                        <small class="text-success d-block" v-if="success">{{ success }}</small>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                    <span v-if="loading">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Sending...
                                    </span>
                                    <span v-else>Request new password</span>
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
    name: "AdminForgot",

    data() {
        return {
            email: "",
            validationErrors: {},
            loading: false,
            error: null,
            success: null,
        };
    },

    methods: {
        async submit() {
            this.loading = true;
            this.validationErrors = {};
            this.error = null;
            this.success = null;

            try {
                const response = await axios.post("/admin/forgot", {
                    email: this.email,
                });

                this.success = response.data.message || "Reset link sent to your email.";
                this.$toast.success(this.success, {
                    position: 'top-right',
                    duration: 3000,
                });

                this.email = "";
            } catch (err) {
                if (err.response?.status === 422) {
                    this.validationErrors = err.response.data.errors;
                } else {
                    this.error = err.response?.data?.message || "Something went wrong.";
                    this.$toast.error(this.error, {
                        position: 'top-right',
                        duration: 4000,
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
/* Optional custom styles */
</style>
