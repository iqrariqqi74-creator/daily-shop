import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./vuex";
import "../axios";

import { VueDatePicker } from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

import CKEditor from "@ckeditor/ckeditor5-vue";

const app = createApp(App);

app.use(store);
app.use(router);
app.use(VueSweetalert2);
app.use(VueToast);
app.use(VueDatePicker);
app.use(CKEditor);
app.mount("#app");

// ✅ Re-initialize AdminLTE sidebar after route change
router.afterEach(() => {
    setTimeout(() => {
        try {
            // Re-initialize Treeview
            window.$('[data-widget="treeview"]').Treeview("init");

            // Re-apply 'active' and 'menu-open' classes
            let currentPath = window.location.pathname;

            document.querySelectorAll(".nav-link").forEach((link) => {
                const linkPath = link.getAttribute("href") || link.dataset?.to;

                if (linkPath && currentPath.includes(linkPath)) {
                    link.classList.add("active");

                    const parent = link.closest(
                        ".nav-item.has-treeview, .nav-item.menu-open",
                    );
                    if (parent) {
                        parent.classList.add("menu-open");
                        const treeviewLink = parent.querySelector(".nav-link");
                        if (treeviewLink) treeviewLink.classList.add("active");
                    }
                } else {
                    link.classList.remove("active");
                }
            });
        } catch (e) {
            console.warn("Sidebar Treeview re-init failed:", e);
        }
    }, 100);
});
