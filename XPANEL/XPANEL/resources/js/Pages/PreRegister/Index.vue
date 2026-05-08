<template>
    <Head title="Register" />

    <div
        class="min-w-screen min-h-screen bg-register flex items-center justify-center px-5 py-5"
    >
        <div class="bg-grey-lighter flex flex-col">
            <img src="/img/revenge-logo.png" alt="" />
            <div
                class="mx-auto max-w-lg flex-1 flex flex-col items-center justify-center px-2"
            >
                <div class="relative">
                    <img src="/img/login_form.png" alt="" />
                </div>
                <div class="absolute h-[33rem] w-[21%] mr-[38px] flex flex-col justify-between">
                    <tabs :tabList="tabList">
                        <template v-slot:tabPanel-1>
                            <h2 class="title_pre-register font-bold uppercase">Create Your Account</h2>
                            <div class="w-full px-6">
                                <div>
                                    <div class="flex -mx-3">
                                        <div class="w-1/2 px-3 mb-2">
                                            <label
                                                for=""
                                                class="text-xs font-semibold px-1"
                                                >Email</label
                                            >
                                            <div class="flex">
                                                <div
                                                    class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"
                                                >
                                                    <i
                                                        class="mdi mdi-account-outline text-gray-300 text-lg"
                                                    ></i>
                                                </div>
                                                <JetInput
                                                    type="text"
                                                    v-model="form.email"
                                                    class="w-full -ml-10 pr-3 py-2 border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                                />
                                            </div>
                                        </div>
                                        <div class="w-1/2 px-3 mb-2">
                                            <label
                                                for=""
                                                class="text-xs font-semibold px-1"
                                                >Country</label
                                            >
                                            <div class="flex">
                                                <select
                                                    v-model="form.country"
                                                    name="countries"
                                                    id=""
                                                    class="w-full border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                                >
                                                    <template
                                                        v-for="country in country_data"
                                                    >
                                                        <option
                                                            :value="
                                                                country.code
                                                            "
                                                        >
                                                            {{ country.name }}
                                                        </option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex -mx-3">
                                        <div class="w-full px-3 mb-2">
                                            <label
                                                for=""
                                                class="text-xs font-semibold px-1"
                                                >Master Account Password</label
                                            >
                                            <div class="flex">
                                                <div
                                                    class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"
                                                >
                                                    <i
                                                        class="mdi mdi-email-outline text-gray-300 text-lg"
                                                    ></i>
                                                </div>
                                                <JetInput
                                                    type="password"
                                                    v-model="form.password"
                                                    class="w-full -ml-10 pr-3 py-2 border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex -mx-3">
                                        <div class="w-full px-3 mb-5">
                                            <label
                                                for=""
                                                class="text-xs font-semibold px-1"
                                                >Repeat Master Account
                                                Password</label
                                            >
                                            <div class="flex">
                                                <div
                                                    class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"
                                                ></div>
                                                <JetInput
                                                    type="password"
                                                    v-model="
                                                        form.password_confirmation
                                                    "
                                                    class="w-full -ml-10 pr-3 py-2 border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex -mx-3">
                                        <div class="w-full px-3">
                                            <button
                                                @click="submit"
                                                class="bg-sky-500 border border-transparent rounded-md p-3 font-semibold text-md text-white uppercase tracking-widest hover:bg-sky-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition block w-full"
                                            >
                                                REGISTER NOW
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-slot:tabPanel-2>
                            <h2 class="title_pre-register font-bold uppercase">Login</h2>
                            <form
                            @submit.prevent="submit_logo"
                                class="p-5 rounded-md"
                            >
                                <div>
                                    <jet-label
                                        for="email"
                                        value="Email"
                                        class="text-xs font-semibold px-1 text-white"
                                    />
                                    <jet-input
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full text-sm text-black"
                                        v-model="form.email"
                                        required
                                        autofocus
                                    />
                                </div>

                                <div class="mt-4">
                                    <jet-label
                                        for="password"
                                        value="Password"
                                        class="text-xs font-semibold px-1 text-white"
                                    />
                                    <jet-input
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full text-sm text-black"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />
                                </div>

                                <div class="block mt-4">
                                    <label class="flex items-center">
                                        <jet-checkbox
                                            name="remember"
                                            class="bg-gray-200"
                                            v-model:checked="form.remember"
                                        />
                                        <span class="ml-2 text-sm text-white"
                                            >Remember me</span
                                        >
                                    </label>
                                </div>

                                <div
                                    class="flex items-center flex-col justify-center mt-4"
                                >
                                    <jet-button
                                        class="ml-4 bg-sky-500 hover:bg-sky-700"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Log in
                                    </jet-button>
                                </div>
                            </form>
                        </template>
                    </tabs>
                    <div class="text-center">
                        <jet-validation-errors />
                    </div>
                    <div class="text-center">
                        <span>{{total_accounts}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import Tabs from "@/Components/Tabs.vue";
import PreLayout from "@/Layouts/PreLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { country_data } from "@/helpers";

export default defineComponent({
    components: {
        Head,
        Tabs,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Link,
    },
    layout: PreLayout,

    data() {
        const total_accounts = usePage().props.total_accounts;
        const tabList = ref(["Register", "Login"]);
        return {
            tabList,
            total_accounts,
            country_data,
            form: this.$inertia.form({
                // name: "",
                email: "",
                password: "",
                country: "",
                password_confirmation: "",
                terms: false,
            }),
        };
    },

    methods: {
        submit_logo() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => {
                        this.form.reset("password")
                    },
                });
        },
        submit() {
            this.form.post(this.route("register"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
});
</script>

<style scoped>

.title_pre-register{
    font-family: 'Beba Neue', sans-serif;
    color: #fff;
    font-size: 1.5rem;
    text-align: center;
    margin-bottom: 1rem;
}
.bg-register {
    background-image: url("/img/bg2.jpg");
    height: 100%;
    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
