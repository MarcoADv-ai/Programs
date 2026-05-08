<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref, watch, computed, reactive, onMounted } from "vue";
import axios from "axios";
import xSelectInput from "@/Components/SelectInput.vue";
import { notify } from "notiwind";

const config = usePage().props.xpanel;
const game_accounts = usePage().props.game_accounts;
const char_account = computed(() => usePage().props.char_account);
const disabled = ref(false);
const account_id = ref("");
const lang = localStorage.getItem("lang");
const selection_account = computed(() => {
    return game_accounts.map((account) => {
        return {
            id: account.account_id,
            value: account.account_id,
            select: account.userid,
        };
    });
});

const selection_char = computed(() => {
    if (char_account.value != null) {
        return char_account.value.map((char) => {
            return {
                id: char.char_id,
                value: char.char_id,
                select: char.name,
            };
        });
    }
});

const points_type = ref([
    {
        id: "DONA",
        value: "DONA",
        select: "Donation Points",
    },
    {
        id: "VOTE",
        value: "VOTE",
        select: "Vote Points",
    },
]);

const form = useForm({
    account_id: "",
    char_id: "",
    points_type: "DONA",
    amount: 0,
});

const state = reactive({
    title: "",
    message: "",
    group: "",
    duration: 5000,
});

const handelSelectedAccount = (event) => {
    account_id.value = event;
    form.account_id = event;
};

const handleSelectedPoints = (event) => {
    form.points_type = event;
    return event != "Vote Points"
        ? (disabled.value = false)
        : (disabled.value = true);
};

const handleSelectedChar = (event) => {
    form.char_id = event;
};

function notifyMe() {
    notify(
        {
            group: state.group,
            title: state.title.toUpperCase(),
            text: state.message + " ",
        },
        state.duration
    );
}

function transfer_points(account_id) {
    if (
        form.amount <= 0 ||
        form.account_id == "" ||
        (form.char_id == "" && config.donations.transfer_type == 'item') ||
        form.points_type == ""
    ) {
        state.title = "Error";
        state.message = "Please fill all fields";
        state.group = "error";
        notifyMe();
        return;
    }

    axios
        .post(`transfer/${account_id}`, form)
        .then((response) => {
            if (response.status == 200) {
                router.reload();
            }
            state.title = response.data.status;
            state.message = response.data.message;
            state.group = response.data.status;
            notifyMe();
        })
        .catch((error) => {
            form.reset("amount");
        });
}

onMounted(() => {
    notifyMe();
    if (usePage().props.donation) {
        state.title = usePage().props.donation.status;
        state.message = usePage().props.donation.message;
        state.group = usePage().props.donation.status;
        notifyMe();
    }
});

watch(account_id, (userid) => {
    router.get(
        route("transfer.index"),
        { user: userid },
        { preserveScroll: true, preserveState: true },
        150
    );
});
</script>

<template>
    <app-layout title="Transfer Points">
        <div class="py-12 h-screen relative">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-36">
                <div class="bg-white shadow-xl sm:rounded-md p-6 text-black dark:bg-gray-800">
                    <h1
                        class="text-4xl font-bold animate-text bg-gradient-to-r from-teal-500 via-purple-500 to-orange-500 bg-clip-text text-transparent mb-3"
                    >
                        <template v-if="lang == 'US'">Transfer Points</template>
                        <template v-else-if="lang == 'FR'">
                            Transférer des points
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Transferir puntos
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Transferir pontos
                        </template>
                    </h1>
                    <span
                        class="text-red-500 text-sm italic"
                        v-if="config.donations.transfer_type === 'item'"
                    >
                        <template v-if="lang == 'US'">
                            The Donations points will be automatically converted
                            into Donation Coins and you will receive them
                            through RODEX
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Les points de dons seront automatiquement convertis
                            en Donation Coins et vous les recevrez via RODEX
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Los puntos de donación se convertirán
                            automáticamente en Donation Coins y los recibirá a
                            través de RODEX
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Os pontos de doação serão convertidos
                            automaticamente em Donation Coins e você os receberá
                            através do RODEX
                        </template>
                    </span>
                    <span
                        class="text-red-500 text-sm italic"
                        v-if="config.donations.transfer_type === 'variable'"
                    >
                        <template v-if="lang == 'US'">
                            The Donations points will be automatically converted
                            to cash points and you will receive in the selected
                            account.
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Les points de dons seront automatiquement convertis
                            en points en espèces et vous les recevrez dans le
                            compte sélectionné.
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Los puntos de donación se convertirán
                            automáticamente en puntos en efectivo y los recibirá
                            en la cuenta seleccionada.
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Os pontos de doação serão convertidos
                            automaticamente em pontos em dinheiro e você os
                            receberá na conta selecionada.
                        </template>
                    </span>
                    <br />
                    <span class="text-red-500 text-sm italic">
                        <template v-if="lang == 'US'">
                            To transfer Vote Points it is not necessary to
                            select a character, they will be added to your
                            selected account.
                        </template>
                        <template v-else-if="lang == 'FR'">
                            Pour transférer des points de vote, il n'est pas
                            nécessaire de sélectionner un personnage, ils seront
                            ajoutés à votre compte sélectionné.
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Para transferir puntos de voto no es necesario
                            seleccionar un personaje, se agregarán a su cuenta
                            seleccionada.
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Para transferir pontos de voto, não é necessário
                            selecionar um personagem, eles serão adicionados à
                            sua conta selecionada.
                        </template>
                    </span>
                    <div>
                        <h3
                            class="my-2 p-2 text-2xl font-semibold text-black mb-2 dark:text-white"
                        >
                            <template v-if="lang == 'US'">
                                Your Points
                            </template>
                            <template v-else-if="lang == 'FR'">
                                Vos points
                            </template>
                            <template v-else-if="lang == 'ES'">
                                Tus puntos
                            </template>
                            <template v-else-if="lang == 'PT'">
                                Seus pontos
                            </template>
                        </h3>
                        <div
                            class="flex justify-around bg-black mb-6 p-6 rounded-md shadow-md text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%"
                        >
                            <div class="flex flex-col items-center">
                                <p class="text-lg font-semibold">
                                    <template v-if="lang == 'US'">
                                        Donation Points:
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Points de dons:
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        Puntos de donación:
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Pontos de doação:
                                    </template>
                                </p>
                                <p class="text-5xl font-semibold">
                                    {{ $page.props.user.donation_points }}
                                </p>
                            </div>
                            <div class="flex flex-col items-center">
                                <p class="text-lg font-semibold">
                                    <template v-if="lang == 'US'">
                                        Vote Points:
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Points de vote:
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        Puntos de voto:
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Pontos de voto:
                                    </template>
                                </p>
                                <p class="text-5xl font-semibold">
                                    {{ $page.props.user.vote_points }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="my-6 px-5 py-2 dark:bg-gray-900 dark:rounded-lg">
                                <h2
                                    class="text-2xl font-semibold text-black mb-2 dark:text-white"
                                >
                                    <template v-if="lang == 'US'">
                                        Transfer Points
                                    </template>
                                    <template v-else-if="lang == 'FR'">
                                        Transférer des points
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        Transferir puntos
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Transferir pontos
                                    </template>
                                </h2>
                                <div class="flex justify-between items-center">
                                    <div class="md:p-10 py-2 px-4 w-full">
                                        <x-select-input
                                            :options="selection_account"
                                            :label="
                                                lang == 'US'
                                                    ? 'Select Account'
                                                    : lang == 'FR'
                                                    ? 'Sélectionnez le compte'
                                                    : lang == 'ES'
                                                    ? 'Seleccionar cuenta'
                                                    : 'Selecionar conta'
                                            "
                                            :required="true"
                                            :placeholder="'Select Account'"
                                            @getSelected="handelSelectedAccount"
                                        />
                                    </div>

                                    <div class="md:p-10 py-2 px-4 w-full">
                                        <x-select-input
                                            :options="points_type"
                                            :label="
                                                lang == 'US'
                                                    ? 'Points Type'
                                                    : lang == 'FR'
                                                    ? 'Type de points'
                                                    : lang == 'ES'
                                                    ? 'Tipo de puntos'
                                                    : 'Tipo de pontos'
                                            "
                                            :required="true"
                                            :placeholder="'Points Type'"
                                            @getSelected="handleSelectedPoints"
                                        />
                                    </div>

                                    <div
                                        class="md:p-10 py-2 px-4 w-full"
                                        v-if="
                                            config.donations.transfer_type ===
                                            'item'
                                        "
                                    >
                                        <x-select-input
                                            :options="selection_char"
                                            :label="
                                                lang == 'US'
                                                    ? 'Select Character'
                                                    : lang == 'FR'
                                                    ? 'Sélectionnez le personnage'
                                                    : lang == 'ES'
                                                    ? 'Seleccionar personaje'
                                                    : 'Selecionar personagem'
                                            "
                                            :placeholder="'Select Character'"
                                            :required="true"
                                            :disabled="disabled"
                                            @getSelected="handleSelectedChar"
                                        />
                                    </div>

                                    <div class="md:p-10 py-2 px-4 w-full">
                                        <div>
                                            <label
                                                for="account-number"
                                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                            >
                                                <template v-if="lang == 'US'">
                                                    Amount
                                                </template>
                                                <template
                                                    v-else-if="lang == 'FR'"
                                                >
                                                    Montant
                                                </template>
                                                <template
                                                    v-else-if="lang == 'ES'"
                                                >
                                                    Cantidad
                                                </template>
                                                <template
                                                    v-else-if="lang == 'PT'"
                                                >
                                                    Quantidade
                                                </template>
                                                <span class="text-red-500"
                                                    >*</span
                                                ></label
                                            >
                                            <div
                                                class="relative mt-2 rounded-md shadow-sm"
                                            >
                                                <input
                                                    type="text"
                                                    name="account-number"
                                                    id="account-number"
                                                    v-model="form.amount"
                                                    class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                    placeholder="0"
                                                />
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                                >
                                                    <QuestionMarkCircleIcon
                                                        class="h-5 w-5 text-gray-400"
                                                        aria-hidden="true"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 text-center space-x-2">
                                    <jet-button
                                        class="text-md px-6 bg-sky-500 font-bold py-3 rounded-md text-white"
                                        @click="
                                            transfer_points(form.account_id)
                                        "
                                    >
                                        <template v-if="lang == 'US'">
                                            Transfer
                                        </template>
                                        <template v-else-if="lang == 'FR'">
                                            Transférer
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Transferir
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Transferir
                                        </template>
                                    </jet-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
