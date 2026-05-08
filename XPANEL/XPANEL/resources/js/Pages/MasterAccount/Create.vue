<template>
    <section class="h-screen">
        <div
            class="container mx-auto flex justify-center items-center h-screen"
        >
            <div class="container h-2/3 w-11/12 my-auto p-4">
                <article
                    class="h-full flex items-center flex-col justify-between"
                >
                    <form action="">
                        <form-wizard v-model="active">
                            <!-- Step 1 -->
                            <tab title="Step 1" class="text-center">
                                <h1 class="text-5xl uppercase font-bold">
                                    Aquí comienza tu aventura en xRO
                                </h1>
                                <span class="mt-3"
                                    >Ingresa tu correo electrónico</span
                                >
                                <div class="flex flex-col items-center mt-16">
                                    <jet-input
                                        id="email"
                                        type="text"
                                        class="mt-1 block w-1/3"
                                        required
                                        placeholder="Email"
                                        autofocus
                                        autocomplete="name"
                                    />
                                </div>

                                <jet-button class="mt-10"> Iniciar </jet-button>
                            </tab>
                            <!-- Step 2 -->
                            <tab title="Step 2" class="text-center">
                                <h1 class="text-5xl uppercase font-bold">
                                    Demuestrale a todos el poder de tu nación
                                </h1>
                                <span>Escoge tu país</span>
                                <div class="flex flex-col items-center mt-16">
                                    <jet-input
                                        id="email"
                                        type="text"
                                        class="mt-1 block w-1/3"
                                        required
                                        placeholder="Email"
                                        autofocus
                                        autocomplete="name"
                                    />
                                </div>

                                <jet-button class="mt-10">
                                    Continuar
                                </jet-button>
                            </tab>
                            <!-- step 3  -->
                            <tab title="Step 3" class="text-center">
                                <h1 class="text-5xl uppercase font-bold">
                                    Escoge una contraseña para tu cuenta maestra
                                </h1>
                                <span>Escribir contraseña</span>
                                <div class="flex flex-col items-center mt-16">
                                    <jet-input
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-1/3"
                                        required
                                        placeholder="Password"
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <jet-input
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-1/3"
                                        required
                                        placeholder="Repeat Password"
                                        autofocus
                                        autocomplete="name"
                                    />
                                </div>

                                <jet-button class="mt-10">
                                    Register
                                </jet-button>
                            </tab>
                        </form-wizard>
                    </form>
                </article>
            </div>
        </div>
    </section>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import FormWizard from "@/Components/FormWizard.vue";
import Tab from "@/Components/Tab.vue";
import { ref } from "vue";
export default {
    setup() {
        const active = ref(0);

        return { active };
    },

    components: {
        Head,
        TextInput,
        SelectInput,
        LoadingButton,
        FormWizard,
        Tab,
        JetInput,
        JetLabel,
        JetButton,
    },
    layout: GuestLayout,
    remember: "form",
};
</script>
