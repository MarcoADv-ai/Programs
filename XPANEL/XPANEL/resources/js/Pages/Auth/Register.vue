<template>
    <Head title="Register" />

    <div
        class="min-w-screen min-h-screen bg-register flex items-center justify-center px-5 py-5 relative z-10"
    >
        <div
            class="bg-white text-gray-500 rounded-md shadow-xl w-full overflow-hidden dark:bg-gray-800"
            style="max-width: 1000px"
        >
            <div class="md:flex w-full h-full">
                <div
                    class="hidden bg-register2 md:block w-1/2 bg-black py-10 px-10"
                ></div>
                <div class="w-full md:w-1/2 py-10 px-5 md:px-10">
                    <div class="text-center mb-10">
                        <h1 class="font-bold text-3xl text-gray-900 dark:text-white">
                            <template v-if="lang == 'US'">
                                Create a Master Account</template
                            >
                            <template v-else-if="lang == 'PT'">
                                Criar uma conta mestre</template
                            >
                            <template v-else-if="lang == 'ES'">
                                Crear una cuenta maestra</template
                            >
                            <template v-else-if="lang == 'FR'">
                                Créer un compte maître</template
                            >
                        </h1>

                        <p class="text-sm dark:text-gray-300">
                            <template v-if="lang == 'US'">
                                After creating your master account don't forget
                                to create your game account.
                            </template>
                            <template v-else-if="lang == 'PT'">
                                Depois de criar sua conta mestre, não se esqueça
                                de criar sua conta de jogo.
                            </template>
                            <template v-else-if="lang == 'ES'">
                                Después de crear su cuenta maestra, no olvide
                                crear su cuenta de juego.
                            </template>
                            <template v-else-if="lang == 'FR'">
                                Après avoir créé votre compte maître, n'oubliez
                                pas de créer votre compte de jeu.
                            </template>
                        </p>
                        <jet-validation-errors />
                    </div>
                    <div>
                        <div class="flex -mx-3">
                            <div class="w-1/2 px-3 mb-5">
                                <label
                                    for=""
                                    class="text-xs font-semibold px-1 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'">
                                        Email</template
                                    >
                                    <template v-else-if="lang == 'PT'">
                                        Email</template
                                    >
                                    <template v-else-if="lang == 'ES'">
                                        Correo electrónico</template
                                    >
                                    <template v-else-if="lang == 'FR'">
                                        Email</template
                                    >
                                </label>
                                <div class="flex">
                                    <div
                                        class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-account-outline text-gray-300 text-lg dark:text-gray-300"
                                        ></i>
                                    </div>
                                    <JetInput
                                        type="text"
                                        v-model="form.email"
                                        class="w-full -ml-10 pr-3 py-2 border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                    />
                                </div>
                            </div>
                            <div class="w-1/2 px-3 mb-5">
                                <label
                                    for=""
                                    class="text-xs font-semibold px-1 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'">
                                        Country</template
                                    >
                                    <template v-else-if="lang == 'PT'">
                                        País</template
                                    >
                                    <template v-else-if="lang == 'ES'">
                                        País</template
                                    >
                                    <template v-else-if="lang == 'FR'">
                                        Pays</template
                                    >
                                </label>
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
                                            <option :value="country.code">
                                                {{ country.name }}
                                            </option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex -mx-3">
                            <div class="w-full px-3 mb-5">
                                <label
                                    for=""
                                    class="text-xs font-semibold px-1 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'">
                                        Master Account Password</template
                                    >
                                    <template v-else-if="lang == 'PT'">
                                        Senha da conta mestre</template
                                    >
                                    <template v-else-if="lang == 'ES'">
                                        Contraseña de la cuenta
                                        maestra</template
                                    >
                                    <template v-else-if="lang == 'FR'">
                                        Mot de passe du compte maître</template
                                    >
                                </label>
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
                            <div class="w-full px-3 mb-12">
                                <label
                                    for=""
                                    class="text-xs font-semibold px-1 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'">
                                        Confirm Master Account
                                        Password</template
                                    >
                                    <template v-else-if="lang == 'PT'">
                                        Confirmar senha da conta
                                        mestre</template
                                    >
                                    <template v-else-if="lang == 'ES'">
                                        Confirmar contraseña de la cuenta
                                        maestra</template
                                    >
                                    <template v-else-if="lang == 'FR'">
                                        Confirmer le mot de passe du compte
                                        maître</template
                                    >
                                </label>
                                <div class="flex">
                                    <div
                                        class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-lock-outline text-gray-300 text-lg"
                                        ></i>
                                    </div>
                                    <JetInput
                                        type="password"
                                        v-model="form.password_confirmation"
                                        class="w-full -ml-10 pr-3 py-2 border-2 border-gray-200 outline-none focus:border-indigo-500 text-black"
                                    />
                                </div>
                                <p class="text-sm italic mt-3 text-red-500">
                                    <template v-if="lang == 'US'">
                                        Note: Your master account password is
                                        different from your game account
                                        password.
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Nota: sua senha da conta mestre é
                                        diferente da senha da conta de jogo.
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        Nota: su contraseña de cuenta maestra es
                                        diferente de su contraseña de cuenta de
                                        juego.
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Remarque: votre mot de passe de compte
                                        maître est différent de votre mot de
                                        passe de compte de jeu.
                                    </template>
                                </p>
                            </div>
                        </div>
                        <div class="flex -mx-3">
                            <div class="w-full px-3 mb-5">
                                <button
                                    @click="submit"
                                    class="bg-sky-500 border border-transparent rounded-md p-3 font-semibold text-md text-white uppercase tracking-widest hover:bg-sky-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition block w-full"
                                >
                                    <template v-if="lang == 'US'">
                                        Create Master Account</template
                                    >
                                    <template v-else-if="lang == 'PT'">
                                        Criar conta mestre</template
                                    >
                                    <template v-else-if="lang == 'ES'">
                                        Crear cuenta maestra</template
                                    >
                                    <template v-else-if="lang == 'FR'">
                                        Créer un compte maître</template
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import GuestLayoutVue from "@/Layouts/GuestLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { country_data } from "@/helpers";

export default defineComponent({
    components: {
        Head,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Link,
    },
    layout: GuestLayoutVue,

    data() {
        return {
            country_data,
            form: this.$inertia.form({
                // name: "",
                email: "",
                password: "",
                country: "",
                password_confirmation: "",
                terms: false,
            }),
            lang: localStorage.getItem("lang"),
        };
    },

    methods: {
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
.bg-register2 {
    background-image: url("/img/register.jpg");
    background-size: cover;
    background-position: center;
}
</style>
