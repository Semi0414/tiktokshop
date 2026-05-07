/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const csrfMeta = document.head?.querySelector('meta[name="csrf-token"]');
if (csrfMeta?.content) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfMeta.content;
}

export default {
    install(app) {
        app.config.globalProperties.$axios = axios;
    },
};
