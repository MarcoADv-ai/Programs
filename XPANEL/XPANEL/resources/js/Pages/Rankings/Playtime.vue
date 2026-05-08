<script setup>
import { ref, computed } from "vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import {
    get_job_name,
    secondsToDh,
} from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    playtime: {
        type: Object,
        required: true,
    },
});

const playtime = computed(() => props.playtime);
const rank_attributes = ref([
    "Player",
    "Guild",
    "Level",
    "Online",
    "Country",
    "Time",
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
        name_US: "Time",
        name_PT: "Tempo",
        name_FR: "Temps",
        name_ES: "Tiempo",
        id: "PT",
    },
]);
</script>

<template>
    <RankingLayout>
        <ranking-panel
            title="Playtime"
            :rank_filter="rank_type"
            :rank="'playtime'"
            :filter_type="'ranking'"
        >
            <ranking-table :rank_attributes="rank_attributes">
                <template #row>
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                        v-for="(play, index) in playtime.data"
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
                            <div class="flex items-center justify-center">
                                <img
                                    :src="`/rochar/character-head/${play.name}`"
                                    alt=""
                                />
                                <a href="">
                                    <span class="text-[#04aaff]">
                                        {{ play.name }}
                                    </span>
                                    ({{ get_job_name(play.class) }})
                                </a>
                            </div>
                        </td>

                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center">
                                <div class="flex items-center">
                                    <img
                                        v-if="play.emblem != 'X'"
                                        class="mr-2"
                                        :src="`/${play.emblem} `"
                                        alt=""
                                    />
                                    {{ play.GName ?? "No guild ❌" }}
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3">
                            {{ play.base_level }} / {{ play.job_level }}
                        </td>
                        <td class="px-5 py-3">
                            <span
                                v-if="play.online"
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
                                :country="play.country"
                                size="normal"
                            />
                        </td>
                        <td class="px-5 py-3">
                            {{ secondsToDh(play.playtime) }}
                        </td>
                    </tr>
                </template>
            </ranking-table>
            <pagination class="p-3" :links="playtime.links" />
        </ranking-panel>
    </RankingLayout>
</template>
