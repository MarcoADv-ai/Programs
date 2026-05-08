<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { cartShop } from "@/store.js";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/20/solid";
import { usePage } from "@inertiajs/vue3";
import { notify } from "notiwind";
import { getFormatNumber } from "@/helpers";
import { computed, onMounted, ref, watch } from "vue";
const shop_type = usePage().props.xpanel.donations.shop_type;
const config = usePage().props.xpanel.donations;
const currency = usePage().props.xpanel.donations.currency;
const donations = usePage().props.donations;
const langtype = ref(localStorage.getItem("lang"));
const paypal_logs = computed(() => usePage().props.paypal_donation_logs);
const mp_logs = computed(() => usePage().props.mp_donation_logs);

const enabled_payment_methods = Object.entries(config.methods)
    .filter(([key, method]) => method.enabled)
    .map(([key, method]) => ({ key, ...method }));

const selected = ref(enabled_payment_methods[0]);

const points = ref(0);
const calculate_points = () => {
    return getFormatNumber(points.value * config.points_per_currency);
};

const extraDonation = () => {
    switch (langtype.value) {
        case "US":
            return "Extra Donation";
        case "ES":
            return "Donación Extra";
        case "PT":
            return "Doação Extra";
        case "FR":
            return "Donation Extra";
    }
};
const donaPoints = () => {
    switch (langtype.value) {
        case "US":
            return "Donation Points";
        case "ES":
            return "Puntos de donación";
        case "PT":
            return "Pontos de doação";
        case "FR":
            return "Points de don";
    }
};

const pricing = {
    frequencies: [{ value: "price", priceSuffix: "/ USD" }],
    tiers: [
        {
            id: 0,
            name: donations.packages[0].name,
            quantity: 1,
            price: { price: donations.packages[0].price },
            features: [
                `+ ${donations.packages[0].points} ${donaPoints(
                    langtype.value
                )}`,
            ],
            mostPopular: false,
        },
        {
            name: donations.packages[1].name,
            id: 1,
            quantity: 1,
            price: { price: donations.packages[1].price },
            features: [
                `${donations.packages[1].points} ${donaPoints(langtype.value)}`,
                `+ ${donations.packages[1].extra_points} ${extraDonation(
                    langtype.value
                )}`,
            ],
            mostPopular: true,
        },
        {
            id: 2,
            name: `${donations.packages[2].name}`,
            quantity: 1,
            price: { price: donations.packages[2].price },
            features: [
                `${donations.packages[2].points} ${donaPoints(langtype.value)}`,
                `+ ${donations.packages[2].extra_points} ${extraDonation(
                    langtype.value
                )}`,
            ],
            gif_url: `https://cdn.discordapp.com/attachments/902586549978423347/1113952385170157648/cronus.gif`,
            mostPopular: false,
        },
        {
            id: 3,
            name: `${donations.packages[3].name}`,
            quantity: 1,
            price: { price: donations.packages[3].price },
            features: [
                `${donations.packages[3].points} ${donaPoints(langtype.value)}`,
                `+ ${donations.packages[3].extra_points} ${extraDonation(
                    langtype.value
                )}`,
            ],
            gif_url: `https://cdn.discordapp.com/attachments/902586549978423347/1113952385547653180/thor.gif`,
            mostPopular: false,
        },
        {
            id: 4,
            name: `${donations.packages[4].name}`,
            quantity: 1,
            price: { price: donations.packages[4].price },
            features: [
                `${donations.packages[4].points} ${donaPoints(langtype.value)}`,
                `+ ${donations.packages[4].extra_points} ${extraDonation(
                    langtype.value
                )}`,
            ],
            gif_url: `https://cdn.discordapp.com/attachments/902586549978423347/1113952384780091422/athena.gif`,
            mostPopular: true,
        },
        {
            id: 5,
            name: `${donations.packages[5].name}`,
            quantity: 1,
            price: { price: donations.packages[5].price },
            features: [
                `${donations.packages[5].points} ${donaPoints(langtype.value)}`,
                `+ ${donations.packages[5].extra_points} ${extraDonation(
                    langtype.value
                )}`,
            ],
            gif_url: `https://cdn.discordapp.com/attachments/902586549978423347/1113952389842616422/hercules.gif`,
            mostPopular: false,
        },
    ],
};
const frequency = ref(pricing.frequencies[0]);

function add2Cart(product) {
    cartShop().addItem(product);

    notify(
        {
            group: "success",
            title: "Success",
            text: `${product.name} added to cart`,
        },
        5000
    );
}

async function createDonation() {
    if (points.value <= 0) {
        alert("Please enter a valid amount.");
        return;
    }

    await axios
        .post("/donations/create", {
            items: {
                donation: points.value,
            },
            payment_method: selected.value.name,
        })
        .then((response) => {
            if (response.data.status === "success") {
                window.location.href = response.data.payment_url;
            } else {
                alert("Something went wrong, please try again later.");
            }
        });
}

watch(points, (newValue, oldValue) => {
    const numericValue = parseFloat(newValue);
    if (isNaN(numericValue)) {
        points.value = 0;
    } else {
        points.value = numericValue;
    }
});
onMounted(() => {
    const lang = localStorage.getItem("lang");
    if (lang) {
        langtype.value = lang;
        console.log(langtype.value);
    }
});
</script>

<template>
    <GuestLayout :title="'Donations'">
        <div class="py-20 relative z-10 min-h-screen">
            <!-- Pricing section -->
            <div
                class="mx-auto mt-16 max-w-7xl px-6 sm:mt-32 lg:px-8"
                v-if="shop_type === 'packages'"
            >
                <div
                    class="z- mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:max-w-5xl md:grid-cols-2 lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-3"
                >
                    <div
                        data-aos-duration="1800"
                        data-aos="zoom-out-up"
                        method="POST"
                        v-for="tier in pricing.tiers"
                        :key="tier.id"
                        :class="[
                            tier.mostPopular
                                ? 'ring-2 ring-sky-400'
                                : 'ring-1 ring-gray-200',
                            'rounded-3xl p-8',
                            'bg-white',
                            'shadow-md',
                            'w-4/5 mx-auto',
                            'dark:bg-gray-800',
                        ]"
                    >
                        <h2
                            :id="tier.id"
                            :class="[
                                tier.mostPopular
                                    ? 'text-sky-400'
                                    : 'text-gray-900 dark:text-white',
                                'text-lg font-bold leading-8',
                                'text-2xl',
                            ]"
                        >
                            {{ tier.name }}
                        </h2>
                        <p
                            class="mt-6 flex items-baseline gap-x-1 justify-center"
                        >
                            <span
                                class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white"
                                >${{ tier.price[frequency.value] }}</span
                            >
                            <span
                                class="text-sm font-semibold leading-6 text-gray-600 dark:text-gray-400"
                                >{{ frequency.priceSuffix }}</span
                            >
                        </p>
                        <button
                            @click="add2Cart(tier)"
                            :aria-describedby="tier.id"
                            :disabled="$page.props.user === null"
                            :class="[
                                tier.mostPopular
                                    ? 'bg-sky-400 text-white shadow-sm hover:bg-sky-300'
                                    : 'text-sky-400 ring-1 ring-inset ring-sky-200 hover:ring-sky-300',
                                $page.props.user === null
                                    ? 'cursor-not-allowed'
                                    : '',
                                'w-full relative cursor-pointer mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600',
                            ]"
                        >
                            <p
                                v-if="$page.props.user === null"
                                class="cursor-not-allowed"
                            >
                                <template v-if="langtype === 'US'">
                                    Login to shop
                                </template>
                                <template v-else-if="langtype === 'ES'">
                                    Iniciar sesión para comprar
                                </template>
                                <template v-else-if="langtype === 'PT'">
                                    Faça login para comprar
                                </template>
                                <template v-else-if="langtype === 'FR'">
                                    Connectez-vous pour acheter
                                </template>
                            </p>
                            <p v-else>
                                <template v-if="langtype === 'US'">
                                    Add to cart
                                </template>
                                <template v-else-if="langtype === 'ES'">
                                    Agregar al carrito
                                </template>
                                <template v-else-if="langtype === 'PT'">
                                    Adicionar ao carrinho
                                </template>
                                <template v-else-if="langtype === 'FR'">
                                    Ajouter au panier
                                </template>
                            </p>
                        </button>
                        <ul
                            role="list"
                            class="mt-8 space-y-3 text-sm leading-6 text-gray-600"
                        >
                            <li
                                v-for="(feature, index) in tier.features"
                                :key="feature"
                                class="flex gap-x-3 dark:text-white"
                            >
                                <CheckIcon
                                    class="h-6 w-5 flex-none text-sky-400"
                                    aria-hidden="true"
                                />
                                <template v-if="index !== 2">
                                    {{ feature }}
                                </template>
                                <template v-if="index === 2">
                                    <span
                                        class="inline-flex items-center rounded-md bg-purple-400/10 px-2 py-1 text-xs font-medium text-purple-400 ring-1 ring-inset ring-purple-400/30"
                                    >
                                        <template v-if="langtype == 'US'"
                                            >Exclusive</template
                                        >
                                        <template v-else-if="langtype == 'ES'"
                                            >Exclusivo</template
                                        >
                                        <template v-else-if="langtype == 'PT'"
                                            >Exclusivo</template
                                        >
                                        <template v-else-if="langtype == 'FR'"
                                            >Exclusif</template
                                        >
                                    </span>
                                    <p
                                        class="animate-text bg-gradient-to-r from-teal-500 via-purple-500 to-orange-500 bg-clip-text text-transparent font-black my-auto"
                                    >
                                        {{ feature }}
                                    </p>
                                </template>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div
                class="mx-auto mt-16 max-w-7xl px-6 sm:mt-32 lg:px-8"
                v-if="shop_type === 'points'"
            >
                <section
                    class="bg-white w-full h-full max-h-[800px] mt-6 mb-auto rounded-lg overflow-hidden dark:bg-gray-800"
                >
                    <main>
                        <div class="space-y-6 p-6">
                            <div
                                class="flex flex-col-reverse sm:flex-row gap-4 h-full"
                            >
                                <div
                                    class="space-y-4 overflow-y-auto flex-1 min-w-[200px] bg-gray-100 rounded-lg relative flex items-center justify-center"
                                >
                                    <img
                                        class="absolute object-cover w-full rounded-lg h-full min-[1338px]:block"
                                        src="/img/bg-info.png"
                                        alt=""
                                    />
                                    <h1
                                        class="absolute text-2xl font-bold z-10"
                                    >
                                        Help us to keep the server online!
                                    </h1>
                                </div>
                                <div class="flex justify-between flex-1 gap-4">
                                    <div
                                        class="w-full max-w-full p-4 space-y-4 text-black bg-gray-100 rounded-lg sm:space-y-7 dark:bg-gray-900"
                                    >
                                        <p
                                            class="text-lg font-bold italic text-center dark:text-white"
                                            v-if="langtype === 'US'"
                                        >
                                            How much would you like to donate?
                                        </p>
                                        <p
                                            class="text-lg font-bold italic text-center dark:text-white"
                                            v-else-if="langtype === 'ES'"
                                        >
                                            ¿Cuánto te gustaría donar?
                                        </p>
                                        <p
                                            class="text-lg font-bold italic text-center dark:text-white"
                                            v-else-if="langtype === 'PT'"
                                        >
                                            Quanto você gostaria de doar?
                                        </p>
                                        <p
                                            class="text-lg font-bold italic text-center dark:text-white"
                                            v-else-if="langtype === 'FR'"
                                        >
                                            Combien aimeriez-vous donner?
                                        </p>

                                        <div
                                            class="flex flex-col items-center justify-center w-full gap-3 lg:flex-row sm:gap-4 dark:text-white"
                                        >
                                            <div class="justify-self-end">
                                                <p
                                                    v-if="langtype === 'US'"
                                                    class="text-lg font-bold animate-text bg-gradient-to-r from-sky-500 via-purple-500 to-blue-500 bg-clip-text text-transparent text-2xl font-black uppercase"
                                                >
                                                    {{
                                                        calculate_points(points)
                                                    }}
                                                    Donation Points
                                                </p>
                                                <p
                                                    v-else-if="
                                                        langtype === 'ES'
                                                    "
                                                    class="text-lg font-bold animate-text bg-gradient-to-r from-sky-500 via-purple-500 to-blue-500 bg-clip-text text-transparent text-2xl font-black uppercase"
                                                >
                                                    {{
                                                        calculate_points(points)
                                                    }}
                                                    Puntos de donación
                                                </p>
                                                <p
                                                    v-else-if="
                                                        langtype === 'PT'
                                                    "
                                                    class="text-lg font-bold animate-text bg-gradient-to-r from-sky-500 via-purple-500 to-blue-500 bg-clip-text text-transparent text-2xl font-black uppercase"
                                                >
                                                    {{
                                                        calculate_points(points)
                                                    }}
                                                    Pontos de doação
                                                </p>
                                                <p
                                                    v-else-if="
                                                        langtype === 'FR'
                                                    "
                                                    class="text-lg font-bold animate-text bg-gradient-to-r from-sky-500 via-purple-500 to-blue-500 bg-clip-text text-transparent text-2xl font-black uppercase"
                                                >
                                                    {{
                                                        calculate_points(points)
                                                    }}
                                                    Points de don
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="lg:flex justify-between space-x-3"
                                        >
                                            <div class="flex w-1/2 items-end">
                                                <div class="relative">
                                                    <label
                                                        v-if="langtype === 'US'"
                                                        for="price"
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Price</label
                                                    >
                                                    <label
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                        for="price"
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Precio</label
                                                    >
                                                    <label
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                        for="price"
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Preço</label
                                                    >
                                                    <label
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                        for="price"
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Prix</label
                                                    >
                                                    <div
                                                        class="relative rounded-md shadow-sm mt-2"
                                                    >
                                                        <div
                                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                                        >
                                                            <span
                                                                class="text-gray-500 sm:text-sm"
                                                                >$</span
                                                            >
                                                        </div>
                                                        <input
                                                            type="text"
                                                            name="price"
                                                            id="price"
                                                            v-model="points"
                                                            class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
                                                            placeholder="0.00"
                                                        />
                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center"
                                                        >
                                                            <label
                                                                for="currency"
                                                                class="sr-only"
                                                                >Currency</label
                                                            >
                                                            <select
                                                                id="currency"
                                                                name="currency"
                                                                class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-sky-600 sm:text-sm"
                                                            >
                                                                <option>
                                                                    {{
                                                                        currency
                                                                    }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="relative z-100 w-1/2">
                                                <Listbox
                                                    as="div"
                                                    v-model="selected"
                                                >
                                                    <ListboxLabel
                                                        v-if="langtype === 'US'"
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Payment
                                                        Method</ListboxLabel
                                                    >
                                                    <ListboxLabel
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Método de
                                                        Pago</ListboxLabel
                                                    >
                                                    <ListboxLabel
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Método de
                                                        Pagamento</ListboxLabel
                                                    >
                                                    <ListboxLabel
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                        class="text-sm font-medium leading-6 text-gray-900 dark:text-white"
                                                        >Méthode de
                                                        Paiement</ListboxLabel
                                                    >
                                                    <div class="relative mt-2">
                                                        <ListboxButton
                                                            class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm sm:leading-6"
                                                        >
                                                            <span
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    :src="
                                                                        selected.icon
                                                                    "
                                                                    alt=""
                                                                    class="h-5 w-5 flex-shrink-0 rounded-full"
                                                                />
                                                                <span
                                                                    class="ml-3 block truncate"
                                                                    >{{
                                                                        selected.name
                                                                    }}</span
                                                                >
                                                            </span>
                                                            <span
                                                                class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                                                            >
                                                                <ChevronUpDownIcon
                                                                    class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true"
                                                                />
                                                            </span>
                                                        </ListboxButton>

                                                        <transition
                                                            leave-active-class="transition ease-in duration-100"
                                                            leave-from-class="opacity-100"
                                                            leave-to-class="opacity-0"
                                                        >
                                                            <ListboxOptions
                                                                class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                            >
                                                                <ListboxOption
                                                                    as="template"
                                                                    v-for="payment in enabled_payment_methods"
                                                                    :key="
                                                                        payment.id
                                                                    "
                                                                    :value="
                                                                        payment
                                                                    "
                                                                    v-slot="{
                                                                        active,
                                                                        selected,
                                                                    }"
                                                                >
                                                                    <li
                                                                        :class="[
                                                                            active
                                                                                ? 'bg-sky-600 text-white'
                                                                                : 'text-gray-900',
                                                                            'relative cursor-default select-none py-2 pl-3 pr-9',
                                                                        ]"
                                                                    >
                                                                        <div
                                                                            class="flex items-center"
                                                                        >
                                                                            <img
                                                                                :src="
                                                                                    payment.icon
                                                                                "
                                                                                alt=""
                                                                                class="h-5 w-5 flex-shrink-0 rounded-full"
                                                                            />
                                                                            <span
                                                                                :class="[
                                                                                    selected
                                                                                        ? 'font-semibold'
                                                                                        : 'font-normal',
                                                                                    'ml-3 block truncate',
                                                                                ]"
                                                                                >{{
                                                                                    payment.name
                                                                                }}</span
                                                                            >
                                                                        </div>

                                                                        <span
                                                                            v-if="
                                                                                selected
                                                                            "
                                                                            :class="[
                                                                                active
                                                                                    ? 'text-white'
                                                                                    : 'text-sky-600',
                                                                                'absolute inset-y-0 right-0 flex items-center pr-4',
                                                                            ]"
                                                                        >
                                                                            <CheckIcon
                                                                                class="h-5 w-5"
                                                                                aria-hidden="true"
                                                                            />
                                                                        </span>
                                                                    </li>
                                                                </ListboxOption>
                                                            </ListboxOptions>
                                                        </transition>
                                                    </div>
                                                </Listbox>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            @click="createDonation"
                                            class="max-w-[220px] lg:max-w-none m-auto text-white block w-full p-2 font-medium text-center transition-all duration-300 bg-sky-500 rounded-lg hover:bg-sky-600"
                                        >
                                            <template v-if="langtype === 'US'">
                                                Donate
                                            </template>
                                            <template
                                                v-else-if="langtype === 'ES'"
                                            >
                                                Donar
                                            </template>
                                            <template
                                                v-else-if="langtype === 'PT'"
                                            >
                                                Doar
                                            </template>
                                            <template
                                                v-else-if="langtype === 'FR'"
                                            >
                                                Donner
                                            </template>
                                            {{ currency }} ${{ points }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="overflow-hidden border rounded-lg border-slate-300 dark:border-opacity-30"
                            >
                                <div class="w-full overflow-auto">
                                    <table
                                        class="w-full caption-bottom text-sm"
                                    >
                                        <thead class="">
                                            <tr
                                                v-if="selected.key === 'paypal'"
                                                class="transition-colors bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100%"
                                            >
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Transaction
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Transacción
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Transação
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Transaction
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Gross
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Bruto
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Bruto
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Brut
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Points
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Puntos
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Pontos
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Points
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Bonus
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Bono
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Bônus
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Bonus
                                                    </template>
                                                </th>
                                            </tr>
                                            <tr
                                                v-if="
                                                    selected.key ===
                                                    'mercadopago'
                                                "
                                                class="transition-colors bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100%"
                                            >
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Preference ID
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        ID de Preferencia
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        ID de Preferência
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        ID de Préférence
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Gross
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Bruto
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Bruto
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Brut
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Points
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Puntos
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Pontos
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        Points
                                                    </template>
                                                </th>
                                                <th
                                                    class="h-12 px-4 align-middle [&amp;:has([role=checkbox])]:pr-0 text-center text-white dark:text-white font-bold"
                                                >
                                                    <template
                                                        v-if="langtype === 'US'"
                                                    >
                                                        Payment Status
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'ES'
                                                        "
                                                    >
                                                        Estado de Pago
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'PT'
                                                        "
                                                    >
                                                        Estado de Pagamento
                                                    </template>
                                                    <template
                                                        v-else-if="
                                                            langtype === 'FR'
                                                        "
                                                    >
                                                        État de Paiement
                                                    </template>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-slate-300 dark:divide-opacity-30"
                                        >
                                            <tr
                                                v-if="selected.key === 'paypal'"
                                                v-for="log in paypal_logs.data"
                                                class="transition-colors bg-white text-black dark:bg-gray-900 dark:hover:bg-gray-800 dark:text-white"
                                            >
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.txn_id }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.mc_gross }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.credits }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.bonus }}
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="
                                                    selected.key ===
                                                    'mercadopago'
                                                "
                                                v-for="log in mp_logs.data"
                                                class="transition-colors bg-white text-black"
                                            >
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.preference_id }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.mc_gross }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.credits }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 text-center"
                                                >
                                                    {{ log.payment_status }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <Pagination
                                :links="paypal_logs.links"
                                v-if="selected.key === 'paypal'"
                            />
                            <Pagination
                                :links="mp_logs.links"
                                v-if="selected.key === 'mercadopago'"
                            />
                        </div>
                    </main>
                </section>
            </div>
        </div>
    </GuestLayout>
</template>
