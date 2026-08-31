import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/js/frontend/app.js",
                "resources/js/admin/app.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
});
