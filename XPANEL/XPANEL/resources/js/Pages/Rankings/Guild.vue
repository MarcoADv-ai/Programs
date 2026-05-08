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
import { getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";


const guilds = usePage().props.guilds;
const rank_attributes = ref([
    "Guild Name",
    "Emblem",
    "Guild LvL",
    "Exp",
    "Average Level",
]);

</script>

<template>
    <ranking-panel title="Cash Points">
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(guild, index) in guilds.data"
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
                            <a href="">
                                <span class="text-[#3b87f6]">
                                    {{ guild.name }}
                                </span>
                            </a>
                        </div>
                    </td>

                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center">
                                <img
                                    v-if="guild.emblem != 'X'"
                                    class="mr-2"
                                    :src="`/${guild.emblem} `"
                                    alt=""
                                />
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ guild.guild_lv }}
                    </td>
                    <td class="px-5 py-3">{{ getFormatNumber(guild.exp) }}</td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(guild.average_lv) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="guilds.links" />
    </ranking-panel>
</template>


