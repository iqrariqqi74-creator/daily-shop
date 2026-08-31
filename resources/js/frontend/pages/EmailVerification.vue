<template>
    <section id="aa-myaccount">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aa-myaccount-area">
                        <div class="aa-myaccount-login">
                            <!-- LOADING -->
                            <div v-if="loading" class="text-center">
                                <span class="fa fa-spinner fa-spin"></span>
                                <h4>Verifying your email...</h4>
                            </div>
                            <!-- SUCCESS -->
                            <div v-else-if="success" class="text-center">
                                <span
                                    class="fa fa-check-circle"
                                    style="font-size: 60px; color: #28a745"
                                ></span>
                                <h3>Email Verified Successfully</h3>
                                <p>
                                    Your email has been verified. You can now
                                    login to your account.
                                </p>
                                <button
                                    type="button"
                                    class="aa-browse-btn"
                                    @click="goLogin"
                                >
                                    Login Now
                                </button>
                            </div>
                            <!-- ERROR -->
                            <div v-else class="text-center">
                                <span
                                    class="fa fa-times-circle"
                                    style="font-size: 60px; color: #dc3545"
                                ></span>
                                <h3>Verification Failed</h3>
                                <p>{{ message }}</p>
                                <router-link to="/" class="aa-browse-btn">
                                    Go Home
                                </router-link>
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
    name: "EmailVerification",
    data() {
        return { loading: true, success: false, message: "" };
    },
    mounted() {
        this.verifyEmail();
    },
    methods: {
        async verifyEmail() {
            const randId = this.$route.params.rand_id;
            if (!randId) {
                this.loading = false;
                this.message = "Invalid verification link.";
                return;
            }
            try {
                const response = await axios.get(
                    `/api/email-verification/${randId}`,
                );
                console.log("EMAIL VERIFICATION:", response.data);
                if (
                    response.data?.status === "success" ||
                    response.data?.status === true
                ) {
                    this.success = true;
                } else {
                    this.message =
                        response.data?.message || "Unable to verify email.";
                }
            } catch (error) {
                console.error(
                    "EMAIL VERIFICATION ERROR:",
                    error.response?.data || error.message,
                );
                this.message =
                    error.response?.data?.message ||
                    "Invalid or expired verification link.";
            } finally {
                this.loading = false;
            }
        },
        goLogin() {
            this.$router.push({ path: "/", query: { login: "true" } });
        },
    },
};
</script>
