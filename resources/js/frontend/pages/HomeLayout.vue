<template>
    <div>
        <Header />
        <Nav />
        <main class="main">
            <router-view></router-view>
        </main>
        <Footer />
    </div>
</template>

<script>
import Nav from "./Nav.vue";
import Footer from "./Footer.vue";
import Header from "./Header.vue";

export default {
    name: "HomeLayout",
    components: {
        Nav,
        Footer,
        Header,
    },
    props: {},
    data() {
        return {};
    },
    methods: {},
    mounted() {
        function toggleScrolled() {
            const selectBody = document.querySelector("body");
            const selectHeader = document.querySelector("#header");

            if (!selectHeader) return;

            if (
                !selectHeader.classList.contains("scroll-up-sticky") &&
                !selectHeader.classList.contains("sticky-top") &&
                !selectHeader.classList.contains("fixed-top")
            )
                return;

            window.scrollY > 100
                ? selectBody.classList.add("scrolled")
                : selectBody.classList.remove("scrolled");
        }

        document.addEventListener("scroll", toggleScrolled);
        window.addEventListener("load", toggleScrolled);
    },
};
</script>
