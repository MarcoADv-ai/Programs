<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default {
    layout: RankingLayout,
};
</script>

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import { get_job_name, getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";
import CountryFlag from "vue-country-flag-next";

const deaths = computed(() => usePage().props.deaths_player);
const rank_attributes = ref([
    "Player",
    "Guild",
    "Level",
    "Online",
    "Country",
    "Deaths",
]);

const rank_type = ref([
    { name: "Job Level", id: "JL" },
    { name: "Base Level", id: "BL" },
    { name: "Points", id: "A" },
]);
</script>

<template>
    <ranking-panel
        title="Deaths Counter"
        :rank_filter="rank_type"
        :rank="'deaths'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(death, index) in deaths.data"
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
                                :src="`/rochar/character-head/${death.name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ death.char_name }}
                                </span>
                                ({{ get_job_name(death.class) }})
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="death.emblem != 'X'"
                                    class="mr-2"
                                    :src="`/${death.emblem} `"
                                    alt=""
                                />
                                {{ death.guild_name ?? "No guild ❌" }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ death.base_level }} / {{ death.job_level }}
                    </td>
                    <td class="px-5 py-3">
                        <span
                            v-if="death.online"
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
                        <country-flag :country="death.country" size="normal" />
                    </td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(death.death_count) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="deaths.links" />
    </ranking-panel>
</template>
