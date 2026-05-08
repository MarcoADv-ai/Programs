<script setup>
import Pagination from "@/Components/Pagination.vue";
import RankingPanel from "@/Components/RankingPanel.vue";
import { getFormatNumber } from "@/helpers";
import JetButton from "@/Jetstream/Button.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { usePage } from "@inertiajs/vue3";
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from "chart.js";
import { computed, reactive, ref } from "vue";
import { Bar } from "vue-chartjs";
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

const vendings = computed(() => usePage().props.vendings || []);
const modalVending = ref(false);
const shop_items = ref({});
const lang = localStorage.getItem("lang");
const vendingHistory = reactive({
    dates: [],
    counts: [],
    totalCount: 0,
});

const chartData = computed(() => {
    return {
        labels: vendingHistory.dates,
        datasets: [
            {
                label: "Times of item was sold",
                backgroundColor: "#3b87f6",
                data: vendingHistory.counts,
            },
        ],
    };
});

function showVendingHistory(vendingItems) {
    vendingHistory.dates = vendingItems[0].history.dates
    vendingHistory.counts = vendingItems[0].history.amounts
    vendingHistory.totalCount = vendingItems[0].history.totalCount

    console.log(vendingHistory)
}
function closeVendingModal() {
    modalVending.value = false;
}
</script>
<template>
    <ranking-layout>
        <RankingPanel
            title="Whosell"
            :rank="'whosell'"
            :filter_type="'whosell'"
        >
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full py-2 align-middle">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="text-black py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-3 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Item</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Article</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Artículo</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Item</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Amount</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Montant</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Cantidad</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Quantidade</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Price</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Prix</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Precio</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Preço</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Shop</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Boutique</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Tienda</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Loja</template
                                    >
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(vending, index) in vendings.data">
                                <td
                                    class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0"
                                >
                                    <div class="flex items-center">
                                        <div class="">
                                            <img
                                                class="h-full w-full"
                                                :src="`/rochar/character-head/${vending.shop.char_name}`"
                                                alt=""
                                            />
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="font-medium text-black flex space-x-2 dark:text-gray-300"
                                            >
                                                <div>
                                                    <img
                                                        :src="`https://static.divine-pride.net/images/items/item/${vending.item_id}.png`"
                                                        alt=""
                                                    />
                                                </div>
                                                <div>
                                                    {{ vending.item_name }} -
                                                    {{ vending.shop.title }}
                                                </div>
                                            </div>
                                            <div class="mt-1 text-[#3b87f6]">
                                                {{ vending.shop.char_name }}
                                                <span
                                                    class="text-xs text-gray-400"
                                                    >({{ vending.shop.map }},{{
                                                        vending.shop.x
                                                    }},{{
                                                        vending.shop.y
                                                    }})</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-5 text-sm text-black"
                                >
                                    <div class="flex space-x-1">
                                        <span>
                                            {{ vending.amount }}
                                        </span>
                                        <Popover>
                                            <PopoverButton
                                                @click="
                                                    showVendingHistory(
                                                        vending.vendingItems
                                                    )
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-6 h-6"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"
                                                    />
                                                </svg>
                                            </PopoverButton>

                                            <PopoverPanel
                                                class="absolute z-10 w-96 mt-3 transform -translate-x-1/4 left-1/2 sm:px-0"
                                            >
                                                <!-- {{
                                                    vending.vendingItems[0]
                                                        .history
                                                }} -->
                                                <div class="w-full h-[20rem] shadow-md p-5 bg-white rounded-md">
                                                    <div class="">
                                                        <p
                                                            class="font-semibold text-md text-black flex items-center justify-center dark:text-gray-300"
                                                        >
                                                            <img
                                                                :src="`https://static.divine-pride.net/images/items/item/${vending.item_id}.png`"
                                                                class="mr-2"
                                                                alt=""
                                                            />
                                                            {{
                                                                vending.item_name
                                                            }}
                                                            was sold
                                                            {{
                                                                vendingHistory.totalCount
                                                            }}
                                                            times
                                                        </p>
                                                    </div>
                                                    <Bar
                                                        :data="chartData"
                                                        :height="'100%'"
                                                        :width="'100%'"
                                                    />
                                                </div>
                                            </PopoverPanel>
                                        </Popover>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-5 text-sm text-black"
                                >
                                    <div class="mt-1 flex space-x-2">
                                        <div>
                                            <img
                                                :src="`https://static.divine-pride.net/images/items/item/${vending.shop.vend_type}.png`"
                                                alt=""
                                            />
                                        </div>
                                        <div>
                                            {{ getFormatNumber(vending.price) }}
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-5 text-sm text-gray-500"
                                >
                                    <button
                                        @click="showVendingModal(vending)"
                                        type="button"
                                        class="rounded-sm hover:text-orange-200 p-1 text-black shadow-sm dark:text-gray-300"
                                    >
                                        <svg
                                            xmlns="https://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                                            />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination
                        class="p-3 text-black"
                        :links="vendings.links"
                    />
                </div>
            </div>
        </RankingPanel>
    </ranking-layout>

    <jet-dialog-modal :show="modalVending === true" @close="closeVendingModal">
        <template #title> {{ shop_items.shop.title }} </template>

        <template #content>
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr class>
                        <th
                            scope="col"
                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-3 text-"
                        >
                            Item
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold"
                        >
                            Amount
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold"
                        >
                            Price
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="item_shop in shop_items.vendingItems">
                        <td
                            class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0"
                        >
                            <div class="flex items-center">
                                <div class="h-11 w-11 flex-shrink-0"></div>
                                <div class="ml-4">
                                    <div
                                        class="font-medium text-gray-900 flex space-x-2"
                                    >
                                        <div>
                                            <img
                                                :src="`https://static.divine-pride.net/images/items/item/${item_shop.item_id}.png`"
                                                alt=""
                                            />
                                        </div>
                                        <div>
                                            {{ item_shop.item_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-sm">
                            <div class="text-gray-900 dark:text-gray-300">
                                {{ item_shop.amount }}
                            </div>
                        </td>
                        <td
                            class="whitespace-nowrap px-3 py-5 text-sm text-gray-900"
                        >
                            <div class="mt-1">
                                {{ getFormatNumber(item_shop.price) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>

        <template #footer>
            <jet-button class="ml-3" @click="closeVendingModal">
                Close
            </jet-button>
        </template>
    </jet-dialog-modal>
</template>
