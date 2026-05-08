<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default {
    layout: RankingLayout,
};
</script>

<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import CountryFlag from "vue-country-flag-next";

import { get_job_name, getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";

const cash_points = computed(() => usePage().props.cash_points);
const rank_attributes = ref([
    "Player",
    "Guild",
    "Level",
    "Online",
    "Country",
    "Points",
]);
const rank_type = ref([
    {
        name_US: "Job Level",
        name_PT: "Nível de Classe",
        name_FR: "Niveau de Classe",
        name_ES: "Nivel de Clase",
        id: "JL",
    },
    {
        name_US: "Base Level",
        name_PT: "Nível Base",
        name_FR: "Niveau de Base",
        name_ES: "Nivel Base",
        id: "BL",
    },
    {
        name: "Amount",
        name_PT: "Quantidade",
        name_FR: "Quantité",
        name_ES: "Cantidad",
        id: "A",
    },
    {
        name_US: "Status",
        name_PT: "Status",
        name_FR: "Statut",
        name_ES: "Estado",
        id: "S",
    },
]);
</script>

<template>
    <ranking-panel
        title="Cash Points"
        :rank_filter="rank_type"
        :rank="'cashpoints'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(cash_point, index) in cash_points.data"
                    :key="index"
                >
                    <td class="px-5 py-3" v-if="index == 0">
                        <font-awesome-icon
                            class="text-2xl p-1 w-9 text-yellow-500"
                            icon="fa-solid fa-trophy"
                        />
                    </td>
                    <td class="px-5 py-3" v-if="index == 1">
                        <font-awesome-icon
                            class="text-2xl p-1 w-9 text-silver-600"
                            icon="fa-solid fa-trophy"
                        />
                    </td>
                    <td class="px-5 py-3" v-if="index == 2">
                        <font-awesome-icon
                            class="text-2xl p-1 w-9 text-yellow-700"
                            icon="fa-solid fa-trophy"
                        />
                    </td>

                    <td class="px-5 py-3" v-else-if="index > 2">
                        {{ index + 1 }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <img
                                :src="`/rochar/character-head/${cash_point.name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ cash_point.name }}
                                </span>
                                ({{ get_job_name(cash_point.job) }})
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="cash_point.emblem != 'X'"
                                    class="mr-2"
                                    :src="`/${cash_point.emblem} `"
                                    alt=""
                                />
                                {{ cash_point.GName ?? "No guild ❌" }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ cash_point.lvl }} / {{ cash_point.blvl }}
                    </td>
                    <td class="px-5 py-3">
                        <span
                            v-if="cash_point.online"
                            class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
                            >Online</span
                        >
                        <span
                            v-else
                            class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"
                            >Offline</span
                        >
                    </td>
                    <td class="px-5 py-3">
                        <country-flag
                            :country="cash_point.country"
                            size="normal"
                        />
                    </td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(cash_point.points) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="cash_points.links" />
    </ranking-panel>
</template>
