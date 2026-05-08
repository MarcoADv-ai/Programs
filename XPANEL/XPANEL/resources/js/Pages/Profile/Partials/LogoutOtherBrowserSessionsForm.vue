<template>
    <jet-action-section>
        <template #title>
            <template v-if="lang == 'US'">Browser Sessions</template>
            <template v-else-if="lang == 'FR'">Sessions du navigateur</template>
            <template v-else-if="lang == 'ES'">Sesiones del navegador</template>
            <template v-else-if="lang == 'PT'">Sessões do navegador</template>
        </template>

        <template #description>
            <template v-if="lang == 'US'">
                Manage and log out your active sessions on other browsers and
                devices.
            </template>
            <template v-else-if="lang == 'FR'">
                Gérez et déconnectez vos sessions actives sur d'autres
                navigateurs et appareils.
            </template>
            <template v-else-if="lang == 'ES'">
                Administre y cierre la sesión de sus sesiones activas en otros
                navegadores y dispositivos.
            </template>
            <template v-else-if="lang == 'PT'">
                Gerencie e faça logout de suas sessões ativas em outros
                navegadores e dispositivos.
            </template>
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                <template v-if="lang == 'US'">
                    If necessary, you may log out of all of your other browser
                    sessions across all of your devices. Some of your recent
                    sessions are listed below; however, this list may not be
                    exhaustive. If you feel your account has been compromised,
                    you should also update your password.
                </template>
                <template v-else-if="lang == 'FR'">
                    Si nécessaire, vous pouvez vous déconnecter de toutes vos
                    autres sessions de navigateur sur tous vos appareils.
                    Certaines de vos sessions récentes sont répertoriées ci-
                    dessous; cependant, cette liste n'est peut-être pas
                    exhaustive. Si vous pensez que votre compte a été compromis,
                    vous devez également mettre à jour votre mot de passe.
                </template>
                <template v-else-if="lang == 'ES'">
                    Si es necesario, puede cerrar la sesión de todas sus otras
                    sesiones de navegador en todos sus dispositivos. Algunas de
                    sus sesiones recientes se enumeran a continuación; sin
                    embargo, esta lista puede no ser exhaustiva. Si cree que su
                    cuenta ha sido comprometida, también debe actualizar su
                    contraseña.
                </template>
                <template v-else-if="lang == 'PT'">
                    Se necessário, você pode fazer logout de todas as suas
                    outras sessões de navegador em todos os seus dispositivos.
                    Algumas de suas sessões recentes estão listadas abaixo; no
                    entanto, esta lista pode não ser exaustiva. Se você sentir
                    que sua conta foi comprometida, você também deve atualizar
                    sua senha.
                </template>
            </div>

            <!-- Other Browser Sessions -->
            <div class="mt-5 space-y-6" v-if="sessions.length > 0">
                <div
                    class="flex items-center"
                    v-for="(session, i) in sessions"
                    :key="i"
                >
                    <div>
                        <svg
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-8 h-8 text-gray-500"
                            v-if="session.agent.is_desktop"
                        >
                            <path
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            ></path>
                        </svg>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="w-8 h-8 text-gray-500"
                            v-else
                        >
                            <path d="M0 0h24v24H0z" stroke="none"></path>
                            <rect
                                x="7"
                                y="4"
                                width="10"
                                height="16"
                                rx="1"
                            ></rect>
                            <path d="M11 5h2M12 17v.01"></path>
                        </svg>
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{
                                session.agent.platform
                                    ? session.agent.platform
                                    : "Unknown"
                            }}
                            -
                            {{
                                session.agent.browser
                                    ? session.agent.browser
                                    : "Unknown"
                            }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span
                                    class="text-green-500 font-semibold"
                                    v-if="session.is_current_device"
                                >
                                    <template v-if="lang == 'US'">
                                        This device
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Cet appareil
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        Este dispositivo
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Este dispositivo
                                    </template>
                                </span>
                                <span v-else
                                    >Last active {{ session.last_active }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-5">
                <jet-button @click="confirmLogout">
                    <template v-if="lang == 'US'">
                        Log Out Other Browser Sessions
                    </template>
                    <template v-else-if="lang == 'FR'">
                        Déconnectez-vous des autres sessions de navigateur
                    </template>
                    <template v-else-if="lang == 'ES'">
                        Cerrar sesión en otras sesiones del navegador
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Faça logout de outras sessões do navegador
                    </template>
                </jet-button>

                <jet-action-message :on="form.recentlySuccessful" class="ml-3">
                    <template v-if="lang == 'US'"> Done. </template>
                    <template v-else-if="lang == 'FR'"> Terminé. </template>
                    <template v-else-if="lang == 'ES'"> Hecho. </template>
                    <template v-else-if="lang == 'PT'"> Feito. </template>
                </jet-action-message>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <jet-dialog-modal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    <template v-if="lang == 'US'">
                        Log Out Other Browser Sessions
                    </template>
                    <template v-else-if="lang == 'FR'">
                        Déconnectez-vous des autres sessions de navigateur
                    </template>
                    <template v-else-if="lang == 'ES'">
                        Cerrar sesión en otras sesiones del navegador
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Faça logout de outras sessões do navegador
                    </template>
                </template>

                <template #content>
                    <template v-if="lang == 'US'">
                        Please enter your password to confirm you would like to
                        log out of your other browser sessions across all of
                        your devices.
                    </template>
                    <template v-else-if="lang == 'FR'">
                        Veuillez saisir votre mot de passe pour confirmer que
                        vous souhaitez vous déconnecter de vos autres sessions
                        de navigateur sur tous vos appareils.
                    </template>
                    <template v-else-if="lang == 'ES'">
                        Por favor ingrese su contraseña para confirmar que desea
                        cerrar la sesión de sus otras sesiones de navegador en
                        todos sus dispositivos.
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Por favor, insira sua senha para confirmar que você
                        deseja fazer logout de suas outras sessões de navegador
                        em todos os seus dispositivos.
                    </template>

                    <div class="mt-4">
                        <jet-input
                            type="password"
                            class="mt-1 block w-3/4"
                            :placeholder="(lang == 'US') ? 'Password' : (lang == 'FR') ? 'Mot de passe' : (lang == 'ES') ? 'Contraseña' : 'Senha'"
                            ref="password"
                            v-model="form.password"
                            @keyup.enter="logoutOtherBrowserSessions"
                        />

                        <jet-input-error
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click="closeModal">
                        <template v-if="lang == 'US'">Cancel</template>
                        <template v-else-if="lang == 'FR'">Annuler</template>
                        <template v-else-if="lang == 'ES'">Cancelar</template>
                        <template v-else-if="lang == 'PT'">Cancelar</template>
                       
                    </jet-secondary-button>

                    <jet-button
                        class="ml-3"
                        @click="logoutOtherBrowserSessions"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <template v-if="lang == 'US'">Log Out Other Browser Sessions</template>
                        <template v-else-if="lang == 'FR'">Déconnectez-vous des autres sessions de navigateur</template>
                        <template v-else-if="lang == 'ES'">Cerrar sesión en otras sesiones del navegador</template>
                        <template v-else-if="lang == 'PT'">Faça logout de outras sessões do navegador</template>
                        
                    </jet-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
import { defineComponent } from "vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetActionSection from "@/Jetstream/ActionSection.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

export default defineComponent({
    props: ["sessions"],

    components: {
        JetActionMessage,
        JetActionSection,
        JetButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },

    data() {
        return {
            confirmingLogout: false,

            form: this.$inertia.form({
                password: "",
            }),
            lang: localStorage.getItem("lang"),
        };
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true;

            setTimeout(() => this.$refs.password.focus(), 250);
        },

        logoutOtherBrowserSessions() {
            this.form.delete(route("other-browser-sessions.destroy"), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.password.focus(),
                onFinish: () => this.form.reset(),
            });
        },

        closeModal() {
            this.confirmingLogout = false;

            this.form.reset();
        },
    },
});
</script>
