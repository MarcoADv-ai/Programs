<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default {
    layout: RankingLayout,
};
</script>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import { get_job_name, getFormatNumber } from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";


const zeny_account = usePage().props.zeny;
const rank_attributes = ref([
    "Player",
    "Guild",
    "Level",
    "Online",
    "Country",
    "Zeny",
]);
const rank_type = ref([
    { name: "Job Level", id: "JL" },
    { name: "Base Level", id: "BL" },
]);
</script>

<template>
    <ranking-panel title="Zeny Amount" :filter_type="'ranking'" :rank="'zeny'" :rank_filter="rank_type">
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(zeny, index) in zeny_account.data"
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
                                :src="`/rochar/character-head/${zeny.char_name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ zeny.char_name }}
                                </span>
                                ({{ get_job_name(zeny.class) }})
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="zeny.emblem"
                                    class="mr-2"
                                    :src="`/${zeny.emblem} `"
                                    alt=""
                                />
                                {{ (zeny.guild_name ?? 'No guild ❌') }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ zeny.base_level }} / {{ zeny.job_level }}
                    </td>
                    <td class="px-5 py-3">
                        <span
                            v-if="zeny.online"
                            class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
                            >Online</span
                        >
                        <span
                            v-else
                            class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10"
                            >Offline</span
                        >
                    </td>                    <td class="px-5 py-3"> <country-flag
                                    :country="zeny.country"
                                    size="normal"
                                /></td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(zeny.zeny) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="zeny_account.links" />
    </ranking-panel>
</template>


