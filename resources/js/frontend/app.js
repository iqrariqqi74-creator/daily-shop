import { createApp } from "vue";
import store from "./vuex.js";
import App from "./App.vue";
import router from "./router";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

const app = createApp(App);
app.use(store);
app.use(router);
app.use(VueToast);
app.mount("#app");
