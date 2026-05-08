<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            <template v-if="lang == 'US'">Update Password</template>
            <template v-else-if="lang == 'FR'"
                >Mettre à jour le mot de passe</template
            >
            <template v-else-if="lang == 'ES'">Actualizar contraseña</template>
            <template v-else-if="lang == 'PT'">Atualizar senha</template>
        </template>

        <template #description>
            <template v-if="lang == 'US'">
                Ensure your account is using a long, random password to stay
                secure.
            </template>
            <template v-else-if="lang == 'FR'">
                Assurez-vous que votre compte utilise un mot de passe long et
                aléatoire pour rester en sécurité.
            </template>
            <template v-else-if="lang == 'ES'">
                Asegúrese de que su cuenta esté usando una contraseña larga y
                aleatoria para mantenerse seguro.
            </template>
            <template v-else-if="lang == 'PT'">
                Certifique-se de que sua conta esteja usando uma senha longa e
                aleatória para se manter segura.
            </template>
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="current_password" :value="(lang == 'US') ? 'Current Password' : (lang == 'FR') ? 'Mot de passe actuel' : (lang == 'ES') ? 'Contraseña actual' : 'Senha atual'" />
                <jet-input
                    id="current_password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.current_password"
                    ref="current_password"
                    autocomplete="current-password"
                />
                <jet-input-error
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password" :value="(lang == 'US') ? 'New Password' : (lang == 'FR') ? 'Nouveau mot de passe' : (lang == 'ES') ? 'Nueva contraseña' : 'Nova senha'" />
                <jet-input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    ref="password"
                    autocomplete="new-password"
                />
                <jet-input-error :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label
                    for="password_confirmation"
                    :value="(lang == 'US') ? 'Confirm Password' : (lang == 'FR') ? 'Confirmer le mot de passe' : (lang == 'ES') ? 'Confirmar contraseña' : 'Confirme a senha'"
                />
                <jet-input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                />
                <jet-input-error
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                <template v-if="lang == 'US'">Save</template>
                <template v-else-if="lang == 'FR'">Sauvegarder</template>
                <template v-else-if="lang == 'ES'">Salvar</template>
                <template v-else-if="lang == 'PT'">Salvar</template>
            </jet-action-message>

            <jet-button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
            <template v-if="lang == 'US'">Save</template>
                <template v-else-if="lang == 'FR'">Sauvegarder</template>
                <template v-else-if="lang == 'ES'">Salvar</template>
                <template v-else-if="lang == 'PT'">Salvar</template>
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import { defineComponent } from "vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";

export default defineComponent({
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
    },

    data() {
        return {
            form: this.$inertia.form({
                current_password: "",
                password: "",
                password_confirmation: "",
            }),
            lang: localStorage.getItem("lang"),
        };
    },

    methods: {
        updatePassword() {
            this.form.put(route("user-password.update"), {
                errorBag: "updatePassword",
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset("password", "password_confirmation");
                        this.$refs.password.focus();
                    }

                    if (this.form.errors.current_password) {
                        this.form.reset("current_password");
                        this.$refs.current_password.focus();
                    }
                },
            });
        },
    },
});
</script>
