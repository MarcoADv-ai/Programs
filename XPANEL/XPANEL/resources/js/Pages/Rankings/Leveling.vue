<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default {
    layout: RankingLayout,
};
</script>

<script setup>
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import {
    get_job_name,
    getFormatNumber,
} from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";

const charsLevel = usePage().props.base_level;
const rank_attributes = ref([
    "Player",
    "Level",
    "Exp.",
    "Guild",
    "Last Map",
    "Status",
    "Country",
]);
const url = ref({});
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
]);
</script>

<template>
    <ranking-panel
        title="Leveling Rankg"
        :filter_type="'ranking'"
        :rank="'level'"
        :rank_filter="rank_type"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(charInfo, index) in charsLevel.data"
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
                                :src="`/rochar/character-head/${charInfo.char_name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ charInfo.char_name }}
                                </span>
                                ({{ get_job_name(charInfo.class) }})
                            </a>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ charInfo.base_level }} / {{ charInfo.job_level }}
                    </td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(charInfo.base_exp) }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="charInfo.emblem != 'X'"
                                    class="mr-2"
                                    :src="`/${charInfo.emblem} `"
                                    alt=""
                                />
                                {{ charInfo.guild_name ?? "No guild ❌" }}
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-3">{{ charInfo.last_map }}</td>
                    <td class="px-5 py-3">
                        <span
                            v-if="charInfo.online"
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
                            :country="charInfo.country"
                            size="normal"
                        />
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="charsLevel.links" />
    </ranking-panel>
</template>
