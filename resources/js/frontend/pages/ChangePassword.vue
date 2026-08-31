<template>
    <section id="aa-myaccount">
        <div class="container">
            <div class="forgot-password-page">
                <div class="forgot-password-box">
                    <h2>Change Password</h2>

                    <p>Please enter your new password below.</p>

                    <!-- New Password -->
                    <div class="form-group">
                        <label>New Password</label>

                        <input
                            type="password"
                            v-model="newPassword"
                            placeholder="Enter new password"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label>Confirm Password</label>

                        <input
                            type="password"
                            v-model="confirmPassword"
                            placeholder="Confirm new password"
                        />
                    </div>

                    <!-- Message -->
                    <p v-if="message" class="message">
                        {{ message }}
                    </p>

                    <!-- Button -->
                    <button @click="resetPassword" :disabled="loading">
                        {{ loading ? "Updating..." : "Change Password" }}
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    name: "ForgotPasswordChange",

    data() {
        return {
            newPassword: "",

            confirmPassword: "",

            message: "",

            loading: false,
        };
    },

    methods: {
        async resetPassword() {
            this.message = "";

            // Check password
            if (!this.newPassword) {
                this.message = "Please enter new password.";

                return;
            }

            // Minimum password
            if (this.newPassword.length < 6) {
                this.message = "Password must be at least 6 characters.";

                return;
            }

            // Check confirm password
            if (this.newPassword !== this.confirmPassword) {
                this.message = "Passwords do not match.";

                return;
            }

            this.loading = true;

            try {
                const response = await axios.post("/api/reset_password", {
                    rand_id: this.$route.params.rand_id,
                    password: this.newPassword,
                    password_confirmation: this.confirmPassword,
                });

                this.message =
                    response.data?.message || "Password changed successfully.";

                if (response.data?.status === "success") {
                    this.newPassword = "";

                    this.confirmPassword = "";

                    // Login page par bhejna
                    setTimeout(() => {
                        this.$router.push("/");
                    }, 1500);
                }
            } catch (error) {
                this.message =
                    error.response?.data?.message ||
                    "Unable to change password.";
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.forgot-password-page {
    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;
}

.forgot-password-box {
    width: 400px;

    padding: 30px;

    border: 1px solid #ddd;

    border-radius: 10px;
}

.forgot-password-box h2 {
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 12px;
    cursor: pointer;
    background: #ff6666;
    color: white;
}

.message {
    margin: 15px 0;
}
</style>
