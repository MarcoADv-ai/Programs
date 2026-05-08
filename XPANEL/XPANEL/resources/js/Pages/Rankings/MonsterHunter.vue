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

import { get_job_name, isOnline, getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";

const monster_hunter = computed(() => usePage().props.monster_hunter);
const rank_attributes = ref([
    "Player",
    "Guild",
    "Level",
    "Online",
    "Country",
    "Points",
]);
const rank_types = ref([
    {
        id: "JL",
        name_US: "Job Level",
        name_PT: "Nível de Classe",
        name_FR: "Niveau de Classe",
        name_ES: "Nivel de Clase",
    },
    {
        id: "BL",
        name_US: "Base Level",
        name_PT: "Nível Base",
        name_FR: "Niveau de Base",
        name_ES: "Nivel Base",
    },
    {
        id: "A",
        name_US: "Points",
        name_PT: "Pontos",
        name_FR: "Points",
        name_ES: "Puntos",
    },
]);
</script>

<template>
    <ranking-panel
        title="Monster Hunter"
        :rank_filter="rank_types"
        :rank="'monster-hunter'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(monster_hunter, index) in monster_hunter.data"
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
                                :src="`/rochar/character-head/${monster_hunter.name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ monster_hunter.name }}
                                </span>
                                ({{ get_job_name(monster_hunter.job) }})
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="monster_hunter.emblem != 'X'"
                                    class="mr-2"
                                    :src="`/${monster_hunter.emblem} `"
                                    alt=""
                                />
                                {{ monster_hunter.GName ?? "No guild ❌" }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ monster_hunter.lvl }} / {{ monster_hunter.blvl }}
                    </td>
                    <td class="px-5 py-3">
                        <span
                            v-if="monster_hunter.online"
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
                            :country="monster_hunter.country"
                            size="normal"
                        />
                    </td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(monster_hunter.points) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="monster_hunter.links" />
    </ranking-panel>
</template>
