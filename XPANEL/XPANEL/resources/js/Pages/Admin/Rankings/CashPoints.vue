<template>
    <ranking-panel title="Cash Points">
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <tr
                    class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-white/20"
                    v-for="(cash_point, index) in cash_points.data"
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
                                :src="`/rochar/character-head/${cash_point.name}`"
                                alt=""
                            />
                            <a href="">
                                <span class="text-[#04aaff]">
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
                                    class="mr-2"
                                    :src="`/${url[cash_point.guild_id]} `"
                                    alt=""
                                />
                                {{ cash_point.GName }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        {{ cash_point.lvl }} / {{ cash_point.blvl }}
                    </td>
                    <td class="px-5 py-3">{{ isOnline(cash_point.online) }}</td>
                    <td class="px-5 py-3">
                        {{ getFormatNumber(cash_point.points) }}
                    </td>
                </tr>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="cash_points.links" />
    </ranking-panel>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import { get_job_name, isOnline, getFormatNumber } from "@/helpers";
import Pagination from "@/Components/Pagination.vue";
export default defineComponent({
    layout: RankingLayout,
    components: {
        RankingTable,
        RankingPanel,
        Pagination,
    },
    props: {
        cash_points: Object,
    },
    setup(props) {
        const cash_points = props.cash_points;
        const url = ref({});
        const rank_attributes = ref([
            "Player",
            "Guild",
            "Level",
            "Online",
            "Points",
        ]);
        onMounted(() => {
            cash_points.data.forEach((cash_points) => {
                axios.get(`/emblem/${cash_points.guild_id}`).then((res) => {
                    if (res.data.url === "X") return;
                    url.value[cash_points.guild_id] = res.data.url;
                });
            });
        });
        return {
            cash_points,
            rank_attributes,
            getFormatNumber,
            get_job_name,
            isOnline,
            url,
        };
    },
});
</script>