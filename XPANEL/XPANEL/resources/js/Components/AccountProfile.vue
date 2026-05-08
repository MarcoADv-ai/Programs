<template>
    <div
        class="block w-full rounded-md mt-4"
        :id="`profile_id-${game_account.account_id}`"
    >
        <div
            class="block p-4 text-left relative bg-[#171717] white_text mt-4 rounded-xl rounded-b-none relative w-full px-4 py-4 rounded-b-none dark:bg-gray-900"
        >
            <span class="px-2 py-1 text-xl">
                <template v-if="lang == 'US'">Game Account: </template>
                <template v-else-if="lang == 'FR'">Compte de jeu: </template>
                <template v-else-if="lang == 'ES'">Cuenta de juego: </template>
                <template v-else-if="lang == 'PT'">Conta de jogo: </template>
                <strong>{{ game_account.userid }}</strong>
            </span>

            <div
                class="grid md:grid-cols-4 grid-cols-1 mx-2 my-2 top-0 right-0"
            >
                <span class="text-xl rounded-sm col-span-2">
                    <div v-if="game_account.state != 0">
                        <template v-if="lang == 'US'">
                            Account Status: <strong>Banned</strong>
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Statut du compte: <strong>Banni</strong>
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Estado de la cuenta: <strong>Baneado</strong>
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Estado da conta: <strong>Banido</strong>
                        </template>
                    </div>
                    <div v-else>
                        <template v-if="lang == 'US'">
                            Account Status: <strong>Active</strong>
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Statut du compte: <strong>Actif</strong>
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Estado de la cuenta: <strong>Activo</strong>
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Estado da conta: <strong>Ativo</strong>
                        </template>
                    </div>
                </span>
                <jet-button
                    class="py-2 justify-center font-bold px-2 mx-2 my-2 text-md rounded-sm bg-sky-500"
                    @click="confirmAccountEdit"
                >
                    <template v-if="lang == 'US'">Change Password</template>
                    <template v-else-if="lang == 'FR'">
                        Changer le mot de passe
                    </template>
                    <template v-else-if="lang == 'ES'">
                        Cambiar contraseña
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Alterar senha
                    </template>
                </jet-button>

                <!-- Edit Account Confirmation Modal -->
                <jet-dialog-modal
                    :show="confirmingEditAccount"
                    @close="closeModalEditAccount"
                >
                    <template #title>
                        <template v-if="lang == 'US'">
                            Change Password
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Changer le mot de passe
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Cambiar contraseña
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Alterar senha
                        </template>
                    </template>

                    <template #content>
                        <template v-if="lang == 'US'">
                            Type your current password and your new password to
                            change the password for account
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Tapez votre mot de passe actuel et votre nouveau mot
                            de passe pour changer le mot de passe du compte
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Escriba su contraseña actual y su nueva contraseña
                            para cambiar la contraseña de la cuenta
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Digite sua senha atual e sua nova senha para alterar
                            a senha da conta
                        </template>
                        <b>{{ game_account.userid }}</b>
                        <div class="mt-4">
                            <jet-input
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="New Account Password"
                                v-model="form.password"
                            />
                            <jet-input
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="Confirm Account Password"
                                v-model="form.password_confirmation"
                            />
                            <jet-input
                                type="password"
                                class="mt-1 block w-3/4"
                                placeholder="Master Account Password"
                                v-model="form.masterAccountPassword"
                                @keyup.enter="
                                    update(`${game_account.account_id}`)
                                "
                            />

                            <jet-input-error
                                :message="form.errors.password"
                                class="mt-2"
                            />
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button @click="closeModalEditAccount">
                           <template v-if="lang == 'US'">Close</template>
                            <template v-else-if="lang == 'FR'">Fermer</template>
                            <template v-else-if="lang == 'ES'">Cerrar</template>
                            <template v-else-if="lang == 'PT'">Fechar</template>
                        </jet-secondary-button>

                        <jet-button
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="update(`${game_account.account_id}`)"
                        >
                            <template v-if="lang == 'US'">
                                Change Password
                            </template>
                            <template v-else-if="lang == 'FR'">
                                Changer le mot de passe
                            </template>
                            <template v-else-if="lang == 'ES'">
                                Cambiar contraseña
                            </template>
                            <template v-else-if="lang == 'PT'">
                                Alterar senha
                            </template>
                        </jet-button>
                    </template>
                </jet-dialog-modal>
                <!-- End of Edit Account Confirmation Modal -->
            </div>
        </div>
        <div
            class="bg-gray-100 grid md:grid-cols-3 grid-cols-1 gap-4 p-4 rounded-b dark:bg-gray-600"
        >
            <template
                v-for="(char_account, index) in char_accounts"
                :key="index"
            >
                <div v-if="char_account.account_id === game_account.account_id">
                    <char-card :char_account="char_account" />
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import { ref, reactive, toRefs } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import CharCard from "@/Components/CharCard.vue";
export default {
    components: {
        JetButton,
        JetInput,
        JetLabel,
        JetInputError,
        JetSecondaryButton,
        JetDialogModal,
        JetDangerButton,
        CharCard,
    },
    props: ["game_account", "type"],
    emits: ["deleteGameAccountProfile"],
    setup(props, context) {
        const char_accounts = usePage().props.char_accounts;
        const modal = reactive({
            confirmingAccountDeletion: false,
            confirmingEditAccount: false,
        });

        const lang = localStorage.getItem("lang");

        const type = ref(props.type);

        const form = useForm({
            masterAccountPassword: "",
            password_confirmation: "",
            password: "",
            errors: {},
        });

        function deleteGameAccountProfile() {
            context.emit(
                "deleteGameAccountProfile",
                props.game_account.account_id
            );
        }

        function confirmAccountDeletion() {
            modal.confirmingAccountDeletion = true;
        }

        function confirmAccountEdit() {
            modal.confirmingEditAccount = true;
        }

        function update(account_id) {
            form.post(`game-accounts/update/${account_id}`, {
                preserveScroll: true,
                onSuccess: () => closeModalEditAccount(),
                onError: () => $refs.password.focus(),
                onFinish: () => form.reset(),
            });
        }

        function deleteGameAccount(account_id) {
            if (
                confirm(
                    "Are you sure you want to delete " +
                        account_id +
                        " account?"
                )
            ) {
                form.delete(`game-accounts/destroy/${account_id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        closeModal(), deleteGameAccountProfile(account_id);
                    },
                    onError: () => $refs.password.focus(),
                    onFinish: () => form.reset(),
                });
            }
        }

        function closeModalEditAccount() {
            modal.confirmingEditAccount = false;
        }

        function closeModal() {
            modal.confirmingAccountDeletion = false;
        }

        return {
            ...toRefs(modal),
            closeModal,
            deleteGameAccount,
            closeModalEditAccount,
            confirmAccountDeletion,
            confirmAccountEdit,
            update,
            char_accounts,
            form,
            type,
            lang,
        };
    },
};
</script>

<style scoped>

.bg_gray {
    background-color: #444444;
}

.gray_text {
    color: #444444;
}

.white_text {
    color: #ededed;
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
