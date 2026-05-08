<template>
    <Head title="Forgot Password" />

    <jet-authentication-card class="bg-login relative z-10">
        <template #logo>
            <div class="flex flex-col items-center">


                <h1 class="text-center text-4xl font-bold text-black m-5">Reset Password</h1>
            </div>
        </template>

        <div class="mb-4 text-sm text-black">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" class="text-black"/>
                <jet-input id="email" type="email" class="mt-1 block w-full text-black" v-model="form.email" required autofocus />
            </div>

            <div class="flex items-center justify-center mt-4">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="bg-sky-500 hover:bg-sky-700">
                    Email Password Reset Link
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Head } from '@inertiajs/vue3';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import GuestLayout from "@/Layouts/GuestLayout.vue";

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors
        },
        props: {
            status: String
        },
        layout: GuestLayout,
        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    })
</script>
