<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import { Bar, Line } from "vue-chartjs";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";
import { notify } from "notiwind";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import Pagination from "@/Components/Pagination.vue";
import { onMounted, reactive, computed, ref, watch } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
} from "chart.js";
import route from "ziggy-js";
ChartJS.register(
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);
const donation = ref({});
const donaPreformance = usePage().props.dona_preformance;
const donaSummary = computed(() => usePage().props.dona_summary);
const gepard_banned = usePage().props.gepard_banned;
const loginsPerDays = usePage().props.logins_per_day;

const payment_method = ref([
    {
        id: 1,
        name: "Paypal",
        icon: "/img/paypal_method.png",
    },
    {
        id: 2,
        name: "MercadoPago",
        icon: "/img/mercado_method.jpg",
    },
]);
const selected = ref(payment_method.value[0]);

const donaPreformanceValue = reactive({
    months: [],
    total: 0,
});

const modal = reactive({
    showDonation: false,
});
const loginsData = reactive({
    days: [],
    counts: [],
});

const chartData = computed(() => {
    return {
        labels: donaPreformanceValue.months,
        datasets: [
            {
                label: "Total",
                data: donaPreformanceValue.total,
                fill: false,
                borderColor: "rgb(2,132,199, 0.5)",
                backgroundColor: "rgb(56,189,248,0.5)",
                tension: 0.1,
            },
        ],
    };
});

const chartLogin = computed(() => {
    return {
        labels: loginsData.days,
        datasets: [
            {
                label: "Logins per day",
                data: loginsData.counts,
                fill: false,
                pointStyle: "circle",
                borderColor: "rgb(2,132,199, 0.5)",
                backgroundColor: "rgb(56,189,248,0.5)",
                pointRadius: 10,
                pointHoverRadius: 15,
            },
        ],
    };
});

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

function showDonation(id) {
    axios
        .get(route("admin.donations.show", id))
        .then((response) => {
            donation.value = response.data.donation;
            modal.showDonation = true;
        })
        .catch((error) => {
            console.log(error);
        });
}

function closeDonation() {
    modal.showDonation = false;
}

function donationUpdate(id) {
    axios
        .put(route("admin.donations.update", id), donation.value)
        .then((response) => {
            state.title = "Success";
            state.message = response.data.message;
            state.group = "success";
            notifyMe();
            router.reload();
        });
}

function donationDestroy(id) {
    axios
        .delete(route("admin.donations.destroy", id))
        .then((response) => {
            state.title = "Success";
            state.message = response.data.message;
            state.group = "success";
            notifyMe();
            router.reload();
        })
        .catch((error) => {
            console.log(error);
        });
}

function createCharts() {
    donaPreformanceValue.months = donaPreformance.map((item) => item.month);
    donaPreformanceValue.total = donaPreformance.map((item) => item.total);
    loginsData.days = loginsPerDays.map((item) => item.day);
    loginsData.counts = loginsPerDays.map((item) => item.count);
}

watch(selected, (value) => {
    router.get(
        route("admin.dashboard", { payment_method: value.name }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
});

onMounted(() => {
    createCharts();
});
</script>
<template>
    <AdminLayout :title="'Dashboard'">
        <div class="grid grid-rows-2">
            <div class="flex">
                <div class="w-full xl:w-8/12 mb-12 xl:mb-0 h-full">
                    <div
                        class="flex flex-col min-w-0 break-words w-full shadow-md rounded bg-blueGray-700 h-full"
                    >
                        <div class="rounded mb-0 px-4 py-3 bg-white h-full">
                            <div class="flex flex-wrap items-center">
                                <div
                                    class="relative w-full max-w-full flex-grow flex-1"
                                >
                                    <h6
                                        class="uppercase text-blueGray-100 text-xs font-semibold"
                                    >
                                        Overview
                                    </h6>
                                    <div
                                        class="flex w-full justify-between items-center mb-3"
                                    >
                                        <h2
                                            class="text-black text-xl font-semibold"
                                        >
                                            Current Donations
                                        </h2>
                                        <div class="relative w-[20%]">
                                            <Listbox
                                                as="div"
                                                v-model="selected"
                                            >
                                                <div class="relative">
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
                                                                v-for="person in payment_method"
                                                                :key="person.id"
                                                                :value="person"
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
                                                                                person.icon
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
                                                                                person.name
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
                                    <div>
                                        <div class="block w-full">
                                            <table
                                                class="items-center w-full bg-transparent border-collapse"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Master Email
                                                        </th>
                                                        <th
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Gross
                                                        </th>
                                                        <th
                                                            v-if="
                                                                selected.name ===
                                                                'Paypal'
                                                            "
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Transaction ID
                                                        </th>
                                                        <th
                                                            v-else-if="
                                                                selected.name ===
                                                                'MercadoPago'
                                                            "
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Preference ID
                                                        </th>
                                                        <th
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Payment Status
                                                        </th>
                                                        <th
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        >
                                                            Payment Date
                                                        </th>
                                                        <th
                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                        ></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="(
                                                            dona, index
                                                        ) in donaSummary.data"
                                                    >
                                                        <th
                                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                                        >
                                                            {{
                                                                dona.user.email
                                                            }}
                                                        </th>
                                                        <td
                                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                        >
                                                            {{ dona.mc_gross }}
                                                        </td>
                                                        <td
                                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                        >
                                                            {{ dona.txn_id }}
                                                        </td>
                                                        <td
                                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                        >
                                                            <span
                                                                v-if="
                                                                    dona.payment_status ===
                                                                        'COMPLETED' ||
                                                                    dona.payment_status ===
                                                                        'approved'
                                                                "
                                                                class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-1.5 py-0.5 text-xs font-medium text-green-700"
                                                            >
                                                                <svg
                                                                    class="h-1.5 w-1.5 fill-green-500"
                                                                    viewBox="0 0 6 6"
                                                                    aria-hidden="true"
                                                                >
                                                                    <circle
                                                                        cx="3"
                                                                        cy="3"
                                                                        r="3"
                                                                    />
                                                                </svg>
                                                                Completed
                                                            </span>
                                                            <span
                                                                v-else-if="
                                                                    dona.payment_status ===
                                                                    'PENDING'
                                                                "
                                                                class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-1.5 py-0.5 text-xs font-medium text-yellow-800"
                                                            >
                                                                <svg
                                                                    class="h-1.5 w-1.5 fill-yellow-500"
                                                                    viewBox="0 0 6 6"
                                                                    aria-hidden="true"
                                                                >
                                                                    <circle
                                                                        cx="3"
                                                                        cy="3"
                                                                        r="3"
                                                                    />
                                                                </svg>
                                                                Badge
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                        >
                                                            {{
                                                                dona.payment_date
                                                            }}
                                                        </td>
                                                        <td
                                                            class="px-5 py-5 bg-white text-sm text-right"
                                                        >
                                                            <jet-dropdown>
                                                                <template
                                                                    #trigger
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        class="inline-block text-gray-500 hover:text-gray-700 z-10"
                                                                    >
                                                                        <svg
                                                                            class="inline-block h-6 w-6 fill-current"
                                                                            viewBox="0 0 24 24"
                                                                        >
                                                                            <path
                                                                                d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                                            />
                                                                        </svg>
                                                                    </button>
                                                                </template>

                                                                <template
                                                                    #content
                                                                >
                                                                    <!-- Dropdown items -->
                                                                    <div
                                                                        class="py-1"
                                                                        role="menu"
                                                                        aria-orientation="vertical"
                                                                        aria-labelledby="options-menu"
                                                                    >
                                                                        <JetDropdownLink
                                                                            :as="'button'"
                                                                            @click="
                                                                                showDonation(
                                                                                    dona.id
                                                                                )
                                                                            "
                                                                            class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                                        >
                                                                            <font-awesome-icon
                                                                                icon="edit"
                                                                                class="mr-2"
                                                                            ></font-awesome-icon>
                                                                            Edit
                                                                        </JetDropdownLink>
                                                                        <JetDropdownLink
                                                                            :as="'button'"
                                                                            @click="
                                                                                destroyDonation(
                                                                                    dona.id
                                                                                )
                                                                            "
                                                                            class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                                        >
                                                                            <font-awesome-icon
                                                                                icon="trash"
                                                                                class="mr-2"
                                                                            ></font-awesome-icon>
                                                                            Destroy
                                                                        </JetDropdownLink>
                                                                    </div>
                                                                </template>
                                                            </jet-dropdown>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <pagination
                                                :links="donaSummary.links"
                                                :pageName="'dona_summary'"
                                            ></pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full xl:w-4/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-md rounded h-full"
                    >
                        <div
                            class="rounded-t mb-0 px-4 py-3 bg-transparent "
                        >
                            <div class="flex items-center border-b border-b-1">
                                <div
                                    class="relative w-full max-w-full flex-grow flex-1"
                                >
                                    <h6
                                        class="uppercase text-blueGray-400 mb-1 text-xs font-semibold"
                                    >
                                        Performance
                                    </h6>
                                    <h2
                                        class="text-blueGray-700 text-xl font-semibold mb-3"
                                    >
                                        Total orders
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 flex-auto">
                            <Bar :data="chartData" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mt-4">
                <div class="w-full xl:w-8/12 xl:mb-0">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white w-full px-4 py-3 shadow-lg rounded"
                    >
                        <div class="rounded-t mb-3 border-0">
                            <h6
                                        class="uppercase text-blueGray-100 text-xs font-semibold"
                                    >
                                        Overview
                                    </h6>
                            <div class="flex flex-wrap items-center">
                                <div
                                    class="relative w-full max-w-full flex-grow flex-1"
                                >
                                    <h3
                                    class="text-black text-xl font-semibold"
                                    >
                                        Gepard Banned
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="block w-full">
                            <table
                                class="items-center w-full bg-transparent border-collapse"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        >
                                            User
                                        </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        >
                                            Reason
                                        </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        >
                                            Unique Id
                                        </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        >
                                            Ban Finish
                                        </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                        >
                                            Banned By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gepard in gepard_banned.data">
                                        <th
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                        >
                                            {{ gepard.violator }}
                                        </th>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ gepard.reason }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ gepard.unique_id }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            <i
                                                class="fas fa-arrow-up text-emerald-500 mr-4"
                                            ></i>
                                            {{ gepard.unban_time }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ gepard.initiator }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination
                                :links="gepard_banned.links"
                                :pageName="'gepard_banned'"
                            />
                        </div>
                    </div>
                </div>
                <div class="w-full xl:w-4/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-lg rounded h-full"
                    >
                        <div
                            class="rounded-t mb-0 px-4 py-3 bg-transparent"
                        >
                            <div class="flex flex-wrap items-center">
                                <div
                                    class="relative w-full max-w-full flex-grow flex-1  border-b border-b-1"
                                >
                                    <h6
                                        class="uppercase text-blueGray-400 mb-1 text-xs font-semibold"
                                    >
                                        Stadistics
                                    </h6>
                                    <h3
                                        class="text-blueGray-700 text-xl font-semibold mb-3"
                                    >
                                        Top Logins per day
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 flex-auto max-h-[22rem]">
                            <div class="h-[20rem]">
                                <Line :data="chartLogin" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <jet-modal-dialog
            :maxWidth="'4xl'"
            :show="modal.showDonation"
            @close="closeDonation"
            class="text-black"
        >
            <template #title>Transaction ID: {{ donation.txn_id }}</template>
            <template #content>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Email (Master Account)</jet-label>
                        <jet-input v-model="donation.email" class="w-full" />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Transaction ID</jet-label>
                        <jet-input v-model="donation.txn_id" class="w-full" />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-full">
                        <jet-label>Item</jet-label>
                        <jet-input
                            v-model="donation.item_name"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Quantity</jet-label>
                        <jet-input v-model="donation.quantity" class="w-full" />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Payment Status</jet-label>
                        <select
                            v-model="donation.payment_status"
                            class="w-full"
                        >
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Payment Date</jet-label>
                        <jet-input
                            v-model="donation.payment_date"
                            class="w-full"
                        />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Mc gross</jet-label>
                        <jet-input v-model="donation.mc_gross" class="w-full" />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-full">
                        <jet-label>Payer Email</jet-label>
                        <jet-input
                            v-model="donation.payer_email"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>First Name</jet-label>
                        <jet-input
                            v-model="donation.first_name"
                            class="w-full"
                        />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Last Name</jet-label>
                        <jet-input
                            v-model="donation.last_name"
                            class="w-full"
                        />
                    </div>
                </div>
            </template>
            <template #footer>
                <jet-button class="mr-2" @click="donationUpdate(donation.id)"
                    >Edit</jet-button
                >
                <jet-secondary-button @click="closeDonation">
                    Close
                </jet-secondary-button>
            </template>
        </jet-modal-dialog>
    </AdminLayout>
</template>
