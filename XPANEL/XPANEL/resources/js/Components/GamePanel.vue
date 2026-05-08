<script setup>
import { reactive, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import AccountProfile from "@/Components/AccountProfile.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import JetLabel from "@/Jetstream/Label.vue";

const lang = localStorage.getItem("lang");
const modal = reactive({
    confirmGameAccount: false,
});
const game_accounts = computed(() => usePage().props.game_accounts);

const form = useForm({
    userid: "",
    password: "",
    password_confirmation: "",
    masterAccountPassword: "",
});

function deleteProfile(account_id) {
    const parent = document.getElementById("game-panel");
    const child = document.getElementById("profile_id-" + account_id);
    parent.removeChild(child);
}

function store() {
    form.post("/game-accounts/create", {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => $refs.password.focus(),
        onFinish: () => form.reset(),
    });
}

function confirmCreateGameAccount() {
    modal.confirmGameAccount = true;
}

function closeModal() {
    modal.confirmGameAccount = false;
    form.userid = null;
    form.password = null;
    form.password_confirmation = null;
    form.masterAccountPassword = null;
}
</script>

<template>
    <div>
        <div class="p-4 bg-white dark:bg-gray-800">
            <h1
                class="text-4xl font-bold animate-text bg-gradient-to-r from-teal-500 via-purple-500 to-orange-500 bg-clip-text text-transparent mb-3"
            >
                <template v-if="lang == 'US'">Game Accounts Panel</template>
                <template v-else-if="lang == 'FR'">
                    Panneau des comptes de jeu
                </template>
                <template v-else-if="lang == 'ES'">
                    Panel de cuentas de juego
                </template>
                <template v-else-if="lang == 'PT'">
                    Painel de contas de jogos
                </template>
            </h1>

            <div class="mt-1 text-black text-xl dark:text-white">
                <template v-if="lang == 'US'">
                    In this section you can manage all your gaming accounts. All
                    in one place!
                </template>
                <template v-else-if="lang == 'FR'">
                    Dans cette section, vous pouvez gérer tous vos comptes de
                    jeu. Tout en un seul endroit!
                </template>
                <template v-else-if="lang == 'ES'">
                    En esta sección puedes administrar todas tus cuentas de
                    juego. ¡Todo en un solo lugar!
                </template>
                <template v-else-if="lang == 'PT'">
                    Nesta seção, você pode gerenciar todas as suas contas de
                    jogos. Tudo em um só lugar!
                </template>
            </div>

            <flash-message class="mb-3" />
            <jet-button
                @click="confirmCreateGameAccount"
                class="rounded-sm bg-sky-400"
            >
                <template v-if="lang == 'US'">Create Game Account</template>
                <template v-else-if="lang == 'FR'">
                    Créer un compte de jeu
                </template>
                <template v-else-if="lang == 'ES'">
                    Crear cuenta de juego
                </template>
                <template v-else-if="lang == 'PT'">
                    Criar conta de jogo
                </template>
            </jet-button>
            <!-- Create Account Confirmation Modal -->
            <jet-modal-dialog
                :show="modal.confirmGameAccount"
                @close="closeModal"
                class="text-black"
            >
                <template #title>
                    <template v-if="lang == 'US'">
                        Create Game Account
                    </template>
                    <template v-else-if="lang == 'FR'">
                        Créer un compte de jeu
                    </template>
                    <template v-else-if="lang == 'ES'">
                        Crear cuenta de juego
                    </template>
                    <template v-else-if="lang == 'PT'">
                        Criar conta de jogo
                    </template>
                </template>
                <template #content>
                    <div class="container flex flex-col dark:text-white">
                        <jet-label
                            for="userid"
                            value="Username"
                            class="mt-2 dark:text-white"
                        />
                        <jet-input id="userid" v-model="form.userid" />
                        <jet-label
                            for="password"
                            value="Password"
                            class="mt-2 dark:text-white"
                        />
                        <jet-input
                            id="password"
                            v-model="form.password"
                            type="password"
                        />
                        <jet-label
                            for="password_confirmation"
                            value="Confirm password"
                            class="mt-2 dark:text-white"
                        />
                        <jet-input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                        />
                        <jet-label
                            for="masterAccountPassword"
                            value="Master account password"
                            class="mt-2 dark:text-white"
                        />
                        <jet-input
                            id="masterAccountPassword"
                            v-model="form.masterAccountPassword"
                            type="password"
                        />
                    </div>
                </template>
                <template #footer>
                    <jet-secondary-button @click="closeModal">
                        <template v-if="lang == 'US'">Close</template>
                        <template v-else-if="lang == 'FR'">Fermer</template>
                        <template v-else-if="lang == 'ES'">Cerrar</template>
                        <template v-else-if="lang == 'PT'">Fechar</template>
                    </jet-secondary-button>
                    <jet-button class="ml-3" @click="store">
                        <template v-if="lang == 'US'">Create Account</template>
                        <template v-else-if="lang == 'FR'">
                            Créer un compte
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Crear cuenta
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Criar conta
                        </template>
                    </jet-button>
                </template>
            </jet-modal-dialog>
            <!-- End of Create Account Confirmation Modal -->
            <div id="game-panel">
                <account-profile
                    v-for="(game_account, index) in game_accounts.data"
                    :key="index"
                    :game_account="game_account"
                    :type="'main'"
                    class="mt-5"
                    @deleteGameAccountProfile="deleteProfile($event)"
                />
            </div>
            <pagination
                :links="game_accounts.links"
                class="mt-4 text-gray-600"
            />
        </div>
    </div>
</template>
