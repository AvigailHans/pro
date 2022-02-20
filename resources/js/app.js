require("./bootstrap");

import { createApp } from "vue";
import { VueReCaptcha } from "vue-recaptcha-v3";

import vueCountryRegionSelect from "vue3-country-region-select";

import "../css/app.css";
import Contact from "./components/Contact";

const app = createApp({});
app.component("contact", Contact);
app.use(vueCountryRegionSelect);
app.use(VueReCaptcha, {
    //  siteKey: process.env.VUE_APP_GOOGLE_RECAPTCHA_SITE_KEY,
    siteKey: "6LcQo4weAAAAALyQL9zdR4fhORcBNytDWNLuyaBv",
});
app.mount("#app");
