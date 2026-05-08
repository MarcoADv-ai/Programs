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
import { get_job_name, getFormatNumber } from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";

const pvp = computed(() => usePage().props.pvp);
const rank_attributes = ref([
    "Player",
    "Guild ",
    "Battle Kills",
    "Deaths",
    "Country",
    "Points",
]);

const rank_type = ref([
    {
        name_US: "Deaths",
        name_PT: "Mortes",
        name_FR: "Morts",
        name_ES: "Muertes",
        id: "D",
    },
    {
        name_US: "Kills",
        name_PT: "Mortes",
        name_FR: "Morts",
        name_ES: "Muertes",
        id: "K",
    },
    { name: "Points", id: "E" },
]);
</script>

<template>
    <ranking-panel
        title="PvP"
        :rank_filter="rank_type"
        :rank="'pvp'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(pvp, index) in pvp.data"
                    :key="index"
                >
                    <td class="px-5 py-3" v-if="index == 0">
                        <font-awesome-icon
                            class="text-2xl p-1 w-9 hover:text-yellow-600"
                            icon="fa-solid fa-trophy"
                        />
                    </td>
                    <td class="px-5 py-3" v-else>
                        {{ index + 1 }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-center items-center">
                            <img
                                :src="`/rochar/character-head/${pvp.name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#04aaff]">
                                    {{ pvp.name }}
                                </span>
                                ({{ get_job_name(pvp.class) }})
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex justify-center items-center">
                            <img
                                v-if="pvp.emblem != 'X'"
                                class="mr-3"
                                :src="`/${pvp.emblem} `"
                                alt=""
                            />
                            {{ pvp.GName ?? "No guild ❌" }}
                        </div>
                    </td>

                    <td class="px-5 py-3">{{ getFormatNumber(pvp.kill) }}</td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(pvp.dead) }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-center items-center">
                            <div class="flex items-center justify-center">
                                <country-flag
                                    :country="pvp.country"
                                    size="normal"
                                />
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(pvp.kill - pvp.dead) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="pvp.links" />
    </ranking-panel>
</template>
