<script>
import { defineComponent } from "vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default defineComponent({
    layout: RankingLayout,
});
</script>

<script setup>
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import { get_job_name, getFormatNumber } from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";

const currentItem = ref(null);
const mvp_counter = computed(() => usePage().props.mvp_kills ?? []);
const mvps_count = computed(() => usePage().props.mvps_count);
const rank_attributes = ref([
    "Name",
    "Guild",
    "Level",
    "Online",
    "Country",
    "MvP's",
]);

const rank_types = ref([
    {
        name_US: "Mvp Kills",
        name_PT: "Mvp Mortes",
        name_FR: "Mvp Décès",
        name_ES: "Mvp Muertos",
        id: "MK",
    },
    {
        name_US: "Status",
        name_PT: "Status",
        name_FR: "Statut",
        name_ES: "Estado",
        id: "S",
    },
]);

const toggleItem = (index) => {
    const item = document.getElementById("show_info_id_" + index);

    if (currentItem.value && currentItem.value !== item) {
        currentItem.value.classList.remove("visible");
        currentItem.value.classList.add("hidden");
    }

    if (item.classList.contains("hidden")) {
        item.classList.remove("hidden");
        item.classList.add("visible");
        currentItem.value = item;
    } else {
        item.classList.remove("visible");
        item.classList.add("hidden");
        currentItem.value = null;
    }
};
async function show_mvp_kills(index, char_id) {
    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q');
    const classParam = urlParams.get('class');

    await router.get(
        route("rank.mvps"),
        {
            char_id: char_id,
            q: q,
            class: classParam,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                toggleItem(index);
            },
        }
    );
}
</script>

<template>
    <ranking-panel
        title="MvP's"
        :rank_filter="rank_types"
        :rank="'mvps'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <template v-for="(mvp, index) in mvps_count.data" :key="index">
                    <tr
                        class="spaceUnder cursor-pointer"
                        @click="show_mvp_kills(index, mvp.char_id)"
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
                                    :src="`/rochar/character-head/${mvp.name}`"
                                    alt=""
                                />
                                <a href="">
                                    <span class="text-[#3b87f6]">
                                        {{ mvp.name }}
                                    </span>
                                    ({{ get_job_name(mvp.class) }})
                                </a>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center">
                                <div class="flex items-center">
                                    <img
                                        v-if="mvp.emblem != 'X'"
                                        class="mr-2"
                                        :src="`/${mvp.emblem} `"
                                        alt=""
                                    />
                                    {{ mvp.GName ?? "No Guild ❌" }}
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            {{ mvp.base_level }} / {{ mvp.job_level }}
                        </td>
                        <td class="px-5 py-3">
                            <span
                                v-if="mvp.online"
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
                                :country="mvp.country"
                                size="normal"
                            />
                        </td>
                        <td class="px-5 py-3">
                            {{ getFormatNumber(mvp.mvp_kills) }}
                        </td>
                    </tr>
                    <tr
                        :id="'show_info_id_' + index"
                        class="hidden spaceUnder mx-auto"
                    >
                        <td colspan="7">
                            <div class="sm:px-6 lg:px-8">
                                <div class="mt-5 flow-root">
                                    <div
                                        class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                                    >
                                        <div
                                            class="min-w-full py-2 align-middle sm:px-6 lg:px-8"
                                        >
                                            <table
                                                class="min-w-full divide-y divide-gray-300"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th
                                                            scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                        >
                                                            Favorite MvP's
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                        >
                                                            MvP Name
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                        >
                                                            Last Kill
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                        >
                                                            Last Map
                                                        </th>
                                                        <th
                                                            scope="col"
                                                            class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                                                        >
                                                            Count
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="divide-y divide-gray-200 bg-white"
                                                >
                                                    <tr
                                                        v-for="(
                                                            mvp, index
                                                        ) in mvp_counter"
                                                        :class="
                                                            index % 2
                                                                ? 'bg-gray-50'
                                                                : 'bg-white'
                                                        "
                                                    >
                                                        <td
                                                            class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <div
                                                                    class="ml-4"
                                                                >
                                                                    <div
                                                                        class="font-medium text-gray-900"
                                                                    >
                                                                        {{
                                                                            index +
                                                                            1
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <div
                                                                    class="h-20 w-20 flex-shrink-0 flex items-center justify-center"
                                                                >
                                                                    <img
                                                                        class="rounded-full overflow-hidden w-15 h-20"
                                                                        alt=""
                                                                        :src="`https://static.divine-pride.net/images/mobs/png/${mvp.monster_id}.png`"
                                                                    />
                                                                </div>
                                                                <div
                                                                    class="ml-4"
                                                                >
                                                                    <div
                                                                        class="font-medium text-gray-900"
                                                                    >
                                                                        {{
                                                                            mvp.name_english
                                                                        }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-5 text-sm text-gray-500 text-left sm:pl-0"
                                                        >
                                                            <div
                                                                class="mt-1 text-gray-500"
                                                            >
                                                                {{
                                                                    mvp.latest_mvp_date
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-5 text-sm text-gray-500"
                                                        >
                                                            <div
                                                                class="mt-1 text-gray-500 text-left"
                                                            >
                                                                {{
                                                                    mvp.latest_map
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="whitespace-nowrap px-3 py-5 text-sm text-gray-500"
                                                        >
                                                            <div
                                                                class="mt-1 text-gray-500"
                                                            >
                                                                {{ mvp.kills }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </template>
        </ranking-table>
        <pagination class="p-3" :links="mvps_count.links" />
    </ranking-panel>
</template>
