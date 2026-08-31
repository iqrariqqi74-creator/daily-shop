<template>
    <!-- =========================================================
         REGISTRATION SECTION
    ========================================================== -->
    <section id="aa-myaccount">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-myaccount-area">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="aa-myaccount-register">
                                    <h4>Register</h4>

                                    <!-- =================================================
                                         REGISTRATION FORM
                                    ================================================== -->
                                    <form
                                        class="aa-login-form"
                                        @submit.prevent="register"
                                    >
                                        <!-- =================================================
                                             NAME
                                        ================================================== -->
                                        <label>
                                            Name
                                            <span>*</span>
                                        </label>

                                        <input
                                            type="text"
                                            v-model="form.name"
                                            placeholder="Name"
                                            :disabled="loading"
                                        />

                                        <div
                                            v-if="errors.name"
                                            class="field_error"
                                        >
                                            {{ errors.name[0] }}
                                        </div>

                                        <!-- =================================================
                                             EMAIL
                                        ================================================== -->
                                        <label>
                                            Email
                                            <span>*</span>
                                        </label>

                                        <input
                                            type="email"
                                            v-model="form.email"
                                            placeholder="Email"
                                            :disabled="loading"
                                        />

                                        <div
                                            v-if="errors.email"
                                            class="field_error"
                                        >
                                            {{ errors.email[0] }}
                                        </div>

                                        <!-- =================================================
                                             PASSWORD
                                        ================================================== -->
                                        <label>
                                            Password
                                            <span>*</span>
                                        </label>

                                        <input
                                            type="password"
                                            v-model="form.password"
                                            placeholder="Password"
                                            :disabled="loading"
                                        />

                                        <div
                                            v-if="errors.password"
                                            class="field_error"
                                        >
                                            {{ errors.password[0] }}
                                        </div>

                                        <!-- =================================================
                                             phone_number
                                        ================================================== -->
                                        <label>
                                            phone_number
                                            <span>*</span>
                                        </label>

                                        <input
                                            type="text"
                                            v-model="form.phone_number"
                                            placeholder="phone_number"
                                            maxlength="10"
                                            :disabled="loading"
                                        />

                                        <div
                                            v-if="errors.phone_number"
                                            class="field_error"
                                        >
                                            {{ errors.phone_number[0] }}
                                        </div>

                                        <!-- =================================================
                                             API MESSAGE
                                        ================================================== -->
                                        <div
                                            v-if="message"
                                            :class="[
                                                'registration-message',
                                                success
                                                    ? 'success-message'
                                                    : 'error-message',
                                            ]"
                                        >
                                            {{ message }}
                                        </div>

                                        <!-- =================================================
                                             REGISTER BUTTON
                                        ================================================== -->
                                        <button
                                            type="submit"
                                            class="aa-browse-btn"
                                            :disabled="loading"
                                        >
                                            {{
                                                loading
                                                    ? "Registering..."
                                                    : "Register"
                                            }}
                                        </button>
                                    </form>
                                </div>

                                <!-- =================================================
                                     SUCCESS MESSAGE
                                ================================================== -->
                                <div v-if="success" class="thank-you-msg">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "Registration",

    data() {
        return {
            // =====================================================
            // FORM
            // =====================================================
            form: {
                name: "",
                email: "",
                password: "",
                phone_number: "",
            },

            // =====================================================
            // VALIDATION ERRORS
            // =====================================================
            errors: {},

            // =====================================================
            // LOADING
            // =====================================================
            loading: false,

            // =====================================================
            // MESSAGE
            // =====================================================
            message: "",

            // =====================================================
            // SUCCESS
            // =====================================================
            success: false,
        };
    },

    methods: {
        // =====================================================
        // REGISTER
        // =====================================================
        async register() {
            // Reset
            this.errors = {};
            this.message = "";
            this.success = false;

            // =================================================
            // BASIC FRONTEND VALIDATION
            // =================================================

            if (!this.form.name.trim()) {
                this.errors.name = ["Name is required."];
                return;
            }

            if (!this.form.email.trim()) {
                this.errors.email = ["Email is required."];
                return;
            }

            if (!this.form.password) {
                this.errors.password = ["Password is required."];
                return;
            }

            if (this.form.password.length < 6) {
                this.errors.password = [
                    "Password must be at least 6 characters.",
                ];
                return;
            }

            if (!this.form.phone_number.trim()) {
                this.errors.phone_number = ["phone_number is required."];
                return;
            }

            if (!/^\d{10}$/.test(this.form.phone_number)) {
                this.errors.phone_number = [
                    "phone_number must contain exactly 10 digits.",
                ];
                return;
            }

            // =================================================
            // LOADING
            // =================================================

            this.loading = true;

            try {
                console.log("REGISTRATION DATA:", this.form);

                // =================================================
                // API REQUEST
                // =================================================

                const response = await axios.post(
                    "/api/registration",
                    {
                        name: this.form.name.trim(),

                        email: this.form.email.trim(),

                        password: this.form.password,

                        phone_number: this.form.phone_number.trim(),
                    },
                    {
                        headers: {
                            Accept: "application/json",

                            "Content-Type": "application/json",
                        },
                    },
                );

                console.log("REGISTRATION RESPONSE:", response.data);

                // =================================================
                // SUCCESS
                // =================================================

                if (response.data?.status === "success") {
                    this.success = true;

                    this.message =
                        response.data.message || "Registration successfully.";

                    // =================================================
                    // CLEAR FORM
                    // =================================================

                    this.form = {
                        name: "",
                        email: "",
                        password: "",
                        phone_number: "",
                    };

                    // =================================================
                    // REDIRECT TO LOGIN AFTER 2 SECONDS
                    // =================================================

                    setTimeout(() => {
                        this.$router.push("/");
                    }, 2000);

                    return;
                }

                // =================================================
                // API ERROR
                // =================================================

                this.success = false;

                this.message = response.data?.message || "Registration failed.";
            } catch (error) {
                console.error(
                    "REGISTRATION ERROR:",
                    error.response?.data || error.message,
                );

                // =================================================
                // VALIDATION ERROR 422
                // =================================================

                if (error.response?.status === 422) {
                    this.errors = error.response?.data?.errors || {};

                    this.message =
                        error.response?.data?.message ||
                        "Please correct the highlighted fields.";

                    return;
                }

                // =================================================
                // OTHER ERROR
                // =================================================

                this.message =
                    error.response?.data?.message ||
                    "Something went wrong. Please try again.";

                this.success = false;
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style>
/* =========================================================
   FIELD ERROR
========================================================= */

.field_error {
    color: #dc3545;

    font-size: 13px;

    margin-top: -8px;

    margin-bottom: 10px;
}

/* =========================================================
   REGISTRATION MESSAGE
========================================================= */

.registration-message {
    margin-top: 15px;

    margin-bottom: 15px;

    padding: 10px;

    font-size: 14px;
}

/* =========================================================
   SUCCESS
========================================================= */

.success-message {
    color: #155724;

    background: #d4edda;

    border: 1px solid #c3e6cb;
}

/* =========================================================
   ERROR
========================================================= */

.error-message {
    color: #721c24;

    background: #f8d7da;

    border: 1px solid #f5c6cb;
}

/* =========================================================
   THANK YOU
========================================================= */

.thank-you-msg {
    margin-top: 15px;

    color: #28a745;

    font-size: 14px;

    font-weight: 600;
}

/* =========================================================
   DISABLED BUTTON
========================================================= */

.aa-browse-btn:disabled {
    opacity: 0.6;

    cursor: not-allowed;
}
</style>
