import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        // kadang di Windows/Laragon perlu host 127.0.0.1 agar HMR nyambung
        host: "127.0.0.1",
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/schedule.css",
                "resources/js/app.js",
                "resources/js/schedule/index.js"
            ],
            refresh: true,
        }),
    ],
});
