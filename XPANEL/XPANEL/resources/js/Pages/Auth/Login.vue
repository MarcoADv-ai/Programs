<template>
    <Head title="Log in" />

    <jet-authentication-card class="bg-login relative z-10">
        <template #logo>
            <div class="flex flex-col items-center">
                <!-- <jet-authentication-card-logo /> -->
                <h1 class="text-center text-4xl font-bold text-black mt-10 dark:text-white">
                    <template v-if="lang == 'US'"> Sign In</template>
                    <template v-else-if="lang == 'PT'"> Entrar</template>
                    <template v-else-if="lang == 'ES'">
                        Iniciar sesión</template
                    >
                    <template v-else-if="lang == 'FR'"> Se connecter</template>
                </h1>
            </div>
        </template>

        <jet-validation-errors class="mb-4" />

        <div
            v-if="status"
            class="bg-white mb-4 font-medium text-sm text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="p-5 rounded-md">
            <div>
                <jet-input
                    id="email"
                    type="email"
                    placeholder="Email"
                    class="mt-1 block w-full text-sm text-black"
                    v-model="form.email"
                    required
                    autofocus
                />
            </div>

            <div class="mt-4">
                <jet-input
                    id="password"
                    type="password"
                    class="mt-1 block w-full text-sm text-black"
                    placeholder="Password"
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
                    <span class="ml-2 text-sm text-black dark:text-white">
                        <template v-if="lang == 'US'"> Remember me</template>
                        <template v-else-if="lang == 'PT'"> Lembrar-me</template>
                        <template v-else-if="lang == 'ES'">
                            Recuérdame</template
                        >
                        <template v-else-if="lang == 'FR'">
                            Souviens-toi de moi</template
                        >
                    </span>
                </label>
            </div>

            <div class="flex items-center flex-col justify-center mt-4">
                <jet-button
                    class="ml-4 bg-gradient-to-r from-sky-400 to-blue-500 border-0 font-semibold hover:bg-gray-500"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <template v-if="lang == 'US'"> Login</template>
                    <template v-else-if="lang == 'PT'"> Entrar</template>
                    <template v-else-if="lang == 'ES'"> Iniciar sesión</template>
                    <template v-else-if="lang == 'FR'"> Se connecter</template>
                </jet-button>
                <Link
                    :href="route('password.request')"
                    class="text-sm text-black hover:text-blue-500 mt-7 dark:text-white"
                >
                    <template v-if="lang == 'US'">
                        Forgot your password?
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Esqueceu sua senha?
                    </template>
                    <template v-else-if="lang == 'ES'">
                        ¿Olvidaste tu contraseña?
                    </template>
                    <template v-else-if="lang == 'FR'">
                        Vous avez oublié votre mot de passe?
                    </template>
                </Link>
                <Link
                    :href="route('register')"
                    class="text-sm text-black hover:text-blue-500 mt-2 dark:text-white"
                >
                    <template v-if="lang == 'US'"> Create Account!</template>
                    <template v-else-if="lang == 'PT'"> Criar conta!</template>
                    <template v-else-if="lang == 'ES'"> Crear cuenta!</template>
                    <template v-else-if="lang == 'FR'">
                        Créer un compte!
                    </template>
                </Link>
            </div>
        </form>
    </jet-authentication-card>
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

    props: {
        canResetPassword: Boolean,
        status: String,
    },
    layout: GuestLayoutVue,
    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
            lang: localStorage.getItem("lang"),
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                });
        },
    },
});
</script>
