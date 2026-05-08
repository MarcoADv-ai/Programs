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
import { getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";
import GuildVs from "@/Components/Guildvs.vue";

const props = defineProps({
    woe_damage: Object,
    all_guilds: Object,
    vs_data: Object,
    index: String,
});
const woe_damage =  computed(() => props.woe_damage); 
const all_guilds = computed(()=> props.all_guilds);
const vs_data = computed(()=> props.vs_data);
const dates_woe = computed(()=> usePage().props.dates_woe)

const rank_attributes = ref([
    "Guild",
    "Damage Done",
    "Damage Received",
    "Comparative",
    "Total Kills",
    "Total Deaths",
    "K/D",
    "Elo",
]);

function show_info(index) {
    const item = document.getElementById("show_info_id_" + index);
    if (item.classList.contains("hidden")) {
        item.classList.remove("hidden");
        item.setAttribute("class", "visible");
    } else {
        item.classList.remove("visible");
        item.setAttribute("class", "hidden");
    }
}
</script>

<template>
    <ranking-panel
        title="Guilds Rank"
        :dates_woe="dates_woe"
        :rank="'woe-damage'"
        :filter_type="'woe-damage'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <template v-for="(wd, index) in woe_damage.data" :key="index">
                    <tr
                        @click="show_info(index)"
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 cursor-pointer"
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
                                <div class="flex items-center justify-center">
                                    <img
                                        v-if="wd.emblem != 'X'"
                                        class="mr-2"
                                        :src="`/${wd.emblem}`"
                                        alt=""
                                    />
                                    {{ wd.gname }}
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(wd.total_dmg) }}
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(wd.total_dmg_rcvd) }}
                        </td>
                        <td class="px-5 py-3">
                            {{
                                getFormatNumber(
                                    wd.total_dmg - wd.total_dmg_rcvd
                                )
                            }}
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(wd.total_kill) }}
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(wd.total_dead) }}
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(wd.total_kill - wd.total_dead) }}
                        </td>
                        <td class="px-5 py-3">{{ getFormatNumber(wd.elo) }}</td>
                    </tr>

                    <GuildVs
                        :id="'show_info_id_' + index"
                        :guild="wd"
                        :all_guilds="all_guilds"
                        :vs_data="vs_data"
                    />
                </template>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="woe_damage.links" />
    </ranking-panel>
</template>
