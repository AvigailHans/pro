<template>
    <div class="background">
        <div class="container">
            <div class="screen">
                <div class="screen-header">
                    <div class="screen-header-left">
                        <div class="screen-header-button close"></div>
                        <div class="screen-header-button maximize"></div>
                        <div class="screen-header-button minimize"></div>
                    </div>
                    <div class="screen-header-right">
                        <div class="screen-header-ellipsis"></div>
                        <div class="screen-header-ellipsis"></div>
                        <div class="screen-header-ellipsis"></div>
                    </div>
                </div>
                <div class="screen-body">
                    <div class="screen-body-item left">
                        <div class="app-title">
                            <span>INSERT YOUR</span>
                            <span>CONTACT INFORMATION</span>
                        </div>
                        <div class="app-errors" v-if="errorMessages">
                            <br />
                            <span
                                v-for="errorMessage in errorMessages"
                                :key="errorMessage.id"
                            >
                                {{ errorMessage[0] }}
                            </span>
                        </div>
                        <div class="app-errors" style="color: #74c54f">
                            <br />
                            {{ successMessage }}
                        </div>
                        <div class="loader" v-if="loading"></div>
                    </div>
                    <div class="screen-body-item">
                        <div class="app-form">
                            <div class="app-form-group">
                                <input
                                    class="app-form-control"
                                    placeholder="FIRST NAME"
                                    v-model="form.first_name"
                                    :class="[
                                        v$.form.first_name.$invalid
                                            ? 'is-invalid'
                                            : 'is-valid',
                                    ]"
                                />
                            </div>

                            <div class="app-form-group">
                                <input
                                    class="app-form-control"
                                    placeholder="LAST NAME"
                                    v-model="form.last_name"
                                    :class="[
                                        v$.form.last_name.$invalid
                                            ? 'is-invalid'
                                            : 'is-valid',
                                    ]"
                                />
                            </div>
                            <div class="app-form-group">
                                <input
                                    class="app-form-control"
                                    placeholder="PHONE"
                                    v-model="form.phone"
                                />
                            </div>
                            <div class="app-form-group">
                                <input
                                    class="app-form-control"
                                    placeholder="EMAIL"
                                    v-model="form.email"
                                    :class="[
                                        v$.form.email.$invalid
                                            ? 'is-invalid'
                                            : 'is-valid',
                                    ]"
                                />
                            </div>

                            <div class="app-form-group">
                                <country-select
                                    class="app-form-control"
                                    v-model="form.country"
                                    :country="form.country"
                                    topCountry="IL"
                                />
                            </div>
                            <div class="app-form-group buttons">
                                <button
                                    :disabled="v$.form.$invalid"
                                    @click="recaptcha"
                                    v-bind:class="[
                                        v$.form.$invalid
                                            ? 'app-form-button'
                                            : 'app-form-button-vaild',
                                    ]"
                                >
                                    SEND
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="credits">
                inspired by
                <a
                    class="credits-link"
                    href="https://dribbble.com/shots/2666271-Contact"
                    target="_blank"
                >
                    <svg class="dribbble" viewBox="0 0 200 200">
                        <g stroke="#ffffff" fill="none">
                            <circle
                                cx="100"
                                cy="100"
                                r="90"
                                stroke-width="20"
                            ></circle>
                            <path
                                d="M62.737004,13.7923523 C105.08055,51.0454853 135.018754,126.906957 141.768278,182.963345"
                                stroke-width="20"
                            ></path>
                            <path
                                d="M10.3787186,87.7261455 C41.7092324,90.9577894 125.850356,86.5317271 163.474536,38.7920951"
                                stroke-width="20"
                            ></path>
                            <path
                                d="M41.3611549,163.928627 C62.9207607,117.659048 137.020642,86.7137169 189.041451,107.858103"
                                stroke-width="20"
                            ></path>
                        </g>
                    </svg>
                    Gururaj
                </a>
            </div>
        </div>
    </div>
</template>
<script lan="ts" setup>
import { required, email } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useReCaptcha } from "vue-recaptcha-v3";
import { useForm } from "@casthub/form";
import { reactive, computed, ref } from "vue";

const form = reactive({
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    country: "",
    captcha_token: null,
});
const errorMessages = ref(null);
const successMessage = ref("");
const loading = ref(false);

const rules = computed(() => {
    return {
        form: {
            first_name: { required },
            last_name: { required },
            email: { required, email },
        },
    };
});
const v$ = useVuelidate(rules, { form });
const { executeRecaptcha, recaptchaLoaded } = useReCaptcha();

const recaptcha = async () => {
    loading.value = true;
    successMessage.value = "";
    errorMessages.value = null;
    await recaptchaLoaded();
    form.captcha_token = await executeRecaptcha("login");
    submit();
};
async function submit() {
    if (!v$.$invalid) {
        axios
            .post("api/send_contact", form)
            .then((res) => {
                successMessage.value = res.data.message;
            })
            .catch((error) => {
                errorMessages.value = error.response.data.errors;
            })
            .finally(() => {
                loading.value = false;
            });
    }
}
</script>
