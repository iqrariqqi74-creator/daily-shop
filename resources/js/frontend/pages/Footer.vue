<template>
    <footer id="aa-footer">
        <div class="aa-footer-top">
            <div class="container">
                <div class="aa-footer-top-area">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="aa-footer-widget">
                                <h3>Main Menu</h3>

                                <ul class="aa-footer-nav">
                                    <li>
                                        <router-link to="/"> Home </router-link>
                                    </li>

                                    <li>
                                        <router-link to="/products">
                                            Products
                                        </router-link>
                                    </li>

                                    <li>
                                        <router-link to="/cart">
                                            Cart
                                        </router-link>
                                    </li>

                                    <li>
                                        <router-link to="/orders">
                                            Orders
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="aa-footer-widget">
                                <h3>Customer Service</h3>

                                <ul class="aa-footer-nav">
                                    <li>
                                        <a href="#"> Delivery </a>
                                    </li>

                                    <li>
                                        <a href="#"> Returns </a>
                                    </li>

                                    <li>
                                        <a href="#"> Discount </a>
                                    </li>

                                    <li>
                                        <a href="#"> FAQ </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="aa-footer-widget">
                                <h3>Useful Links</h3>

                                <ul class="aa-footer-nav">
                                    <li>
                                        <router-link to="/products">
                                            Search
                                        </router-link>
                                    </li>

                                    <li>
                                        <a href="#"> Sitemap </a>
                                    </li>

                                    <li>
                                        <a href="#"> Terms & Conditions </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="aa-footer-widget">
                                <h3>Contact Us</h3>

                                <address>
                                    <p>Pakistan</p>

                                    <p>
                                        <span class="fa fa-phone"></span>
                                        +92 300 1234567
                                    </p>

                                    <p>
                                        <span class="fa fa-envelope"></span>
                                        support@example.com
                                    </p>
                                </address>

                                <div class="aa-footer-social">
                                    <a href="#">
                                        <span class="fa fa-facebook"></span>
                                    </a>

                                    <a href="#">
                                        <span class="fa fa-twitter"></span>
                                    </a>

                                    <a href="#">
                                        <span class="fa fa-instagram"></span>
                                    </a>

                                    <a href="#">
                                        <span class="fa fa-youtube"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="aa-footer-bottom">
            <div class="container">
                <div class="aa-footer-bottom-area">
                    <p>
                        © {{ new Date().getFullYear() }}
                        Your E-Commerce Store
                    </p>

                    <div class="aa-footer-payment">
                        <span class="fa fa-cc-mastercard"></span>
                        <span class="fa fa-cc-visa"></span>
                        <span class="fa fa-paypal"></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: "Footer",
};
</script>
