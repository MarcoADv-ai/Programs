<template>
    <div class="md:grid md:grid-cols-3 md:gap-6 mt-3">
        <jet-section-title>
            <template #title>
                <template v-if="lang == 'US'"> Master Account</template>
                <template v-else-if="lang == 'FR'"> Compte maître</template>
                <template v-else-if="lang == 'ES'"> Cuenta maestra</template>
                <template v-else-if="lang == 'PT'"> Conta mestre</template>
            </template>
            <template #description>
                <template v-if="lang == 'US'">
                    Read your master account information
                </template>
                <template v-else-if="lang == 'FR'">
                    Lisez les informations de votre compte maître</template
                >
                <template v-else-if="lang == 'ES'">
                    Lea la información de su cuenta maestra</template
                >
                <template v-else-if="lang == 'PT'">
                    Leia as informações da sua conta mestre</template
                >
            </template>
        </jet-section-title>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <!-- Table -->
            <div
                class="w-full max-w-2xl mx-auto text-gray-600 bg-white shadow-sm rounded-sm border border-gray-200"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-600">
                        <template v-if="lang == 'US'">Master Account Information</template>
                        <template v-else-if="lang == 'FR'">
                            Informations sur le compte maître
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Información de la cuenta maestra
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Informações da conta mestre
                        </template>
                    </h2>
                </div>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <tbody class="text-sm divide-y divide-gray-100">
                                <tr class="hover:bg-gray-100">
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Email</template>
                                        <template v-else-if="lang == 'FR'">
                                            Email
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Correo electrónico
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Email
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-center">
                                            {{ user.email }}
                                        </div>
                                    </td>
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Account State</template>
                                        <template v-else-if="lang == 'FR'">
                                            État du compte
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Estado de la cuenta
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Estado da conta
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div
                                            class="text-center font-medium text-green-500"
                                        >
                                            {{
                                                user.status
                                                    ? "Active"
                                                    : "Banned"
                                            }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Donations Points</template>
                                        <template v-else-if="lang == 'FR'">
                                            Points de dons
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Puntos de donaciones
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Pontos de doação
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div
                                            class="text-center font-medium text-green-500"
                                        >
                                            {{ user.donation_points }}
                                        </div>
                                    </td>
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Last IP</template>
                                        <template v-else-if="lang == 'FR'">
                                            Dernière IP
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Última IP
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Último IP
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div
                                            class="text-center font-medium text-green-500"
                                        >
                                            {{ form.ip  }}
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-100">
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Vote Points</template>
                                        <template v-else-if="lang == 'FR'">
                                            Points de vote
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Puntos de voto
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Pontos de voto
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div
                                            class="text-center font-medium text-green-500"
                                        >
                                            {{ user.vote_points }}
                                        </div>
                                    </td>
                                    <th class="font-semibold">
                                        <template v-if="lang == 'US'">Country</template>
                                        <template v-else-if="lang == 'FR'">
                                            Pays
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            País
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            País
                                        </template>
                                    </th>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-lg text-center">
                                            <country-flag
                                                :country="user.country"
                                                size="normal"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetSectionTitle from "@/Jetstream/SectionTitle.vue";
import CountryFlag from "vue-country-flag-next";

export default defineComponent({
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetSectionTitle,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        CountryFlag,
    },

    props: ["user"],

    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                email: this.user.email,
                country: this.user.country,
                photo: null,
                ip: "",
            }),
            lang: localStorage.getItem("lang"),
            photoPreview: null,
        };
    },

    methods: {
        updateProfileInformation() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0];
            }

            this.form.post(route("user-profile-information.update"), {
                errorBag: "updateProfileInformation",
                preserveScroll: true,
                onSuccess: () => this.clearPhotoFileInput(),
            });
        },

        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const photo = this.$refs.photo.files[0];

            if (!photo) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(photo);
        },
        getIp() {
            fetch("https://api.ipify.org/?format=json")
                .then((res) => res.json())
                .then((res) => {
                    this.form.ip = res.ip;
                });
        },
        deletePhoto() {
            this.$inertia.delete(route("current-user-photo.destroy"), {
                preserveScroll: true,
                onSuccess: () => {
                    this.photoPreview = null;
                    this.clearPhotoFileInput();
                },
            });
        },

        clearPhotoFileInput() {
            if (this.$refs.photo?.value) {
                this.$refs.photo.value = null;
            }
        },
    },
});
</script>
