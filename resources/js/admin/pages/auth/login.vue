<template>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>Admin</b>Login</a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <form @submit.prevent="login">
                        <!-- Email Field -->
                        <div class="input-group mb-3">
                            <input @input="clearErrorMessage('email')" type="email" class="form-control" :class="{
                                'is-invalid': validationErrors.email,
                                'is-valid': !validationErrors.email && form.email
                            }" placeholder="Email" v-model="form.email" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <small v-if="validationErrors.email" class="text-danger">
                            {{ validationErrors.email[0] }}
                        </small>

                        <!-- Password Field -->
                        <div class="input-group mb-3">
                            <input @input="clearErrorMessage('password')" :type="passwordFieldType" class="form-control"
                                :class="{
                                    'is-invalid': validationErrors.password,
                                    'is-valid': !validationErrors.password && form.password
                                }" placeholder="Password" v-model="form.password" />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i @click="togglePasswordVisibility" :class="[
                                        'fa',
                                        showPassword ? 'fa-eye' : 'fa-eye-slash',
                                    ]"></i>
                                </div>
                            </div>
                        </div>
                        <small v-if="validationErrors.password" class="text-danger">
                            {{ validationErrors.password[0] }}
                        </small>

                        <!-- Remember Me -->
                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember" v-model="form.remember" />
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                    <span v-if="loading">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                    <span v-else>Sign In</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Global Error -->
                    <small class="text-danger mt-2" v-if="error">{{ error }}</small>

                    <!-- Forgot Password Link -->
                    <p class="mb-1 mt-3">
                        <router-link to="/admin/forgot">I forgot my password</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapMutations } from 'vuex';

export default {
    name: "AdminLogin",

    data() {
        return {
            form: {
                email: "",
                password: "",
                remember: false,
            },
            error: null,
            validationErrors: {},
            loading: false,
            showPassword: false,
        };
    },

    computed: {
        passwordFieldType() {
            return this.showPassword ? "text" : "password";
        },
    },

    methods: {
        ...mapMutations(["setToken", "setUser"]),

        async login() {
            this.error = null;
            this.validationErrors = {};
            this.loading = true;

            try {
                const response = await axios.post("/admin/login", this.form);

                this.setToken(response.data.token);
                this.setUser(response.data.user);


                if (this.form.remember) {
                    localStorage.setItem("email", this.form.email);
                    localStorage.setItem("password", this.form.password);
                    localStorage.setItem("remember", "true");
                } else {
                    localStorage.removeItem("email");
                    localStorage.removeItem("password");
                    localStorage.setItem("remember", "false");
                }

                const d = new Date();
                let time = d.getTime();

                this.$toast.success('Login Successfully !', {
                    position: 'top-right',
                    duration: 3000
                });
                document.location.href = `${response.data.url}?t=${time}`;
            } catch (err) {
                if (err.response?.status === 422) {
                    this.validationErrors = err.response.data.errors;
                } else {
                    this.error = err.response?.data?.message || "Something went wrong. Please try again later.";

                    // ✅ Show error toast
                    this.$toast.error(this.error, {
                        position: 'top-right',
                        duration: 4000
                    });
                }
            } finally {
                this.loading = false;
            }
        },


        togglePasswordVisibility() {
            this.showPassword = !this.showPassword;
        },

        clearErrorMessage(key) {
            this.validationErrors[key] = null;
        },
    },

    mounted() {
        const remember = localStorage.getItem("remember");
        if (remember === "true") {
            this.form.email = localStorage.getItem("email") || "";
            this.form.password = localStorage.getItem("password") || "";
            this.form.remember = true;
        }
    },
};
</script>

<style scoped>
.is-valid {
    border-color: #28a745 !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}
</style>
