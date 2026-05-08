<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";

export default {
    layout: RankingLayout,
};
</script>

<script setup>
import { ref } from "vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import {
    get_job_name,
    mapname2bgname,
    get_skill_name,
    getFormatNumber,
} from "@/helpers";
import JetButton from "@/Jetstream/Button.vue";
import { computed } from "@vue/reactivity";

const skill_count = usePage().props.skill_count;
const basic_info = usePage().props.basic_info;
const death_log = usePage().props.death_log;
const kill_log = usePage().props.kill_log;
const char_id = usePage().props.char_id;
const modal = ref(false);
// Reactive variables
const compare_death_log = computed(() => usePage().props.compare_death_log);
const compare_kill_log = computed(() => usePage().props.compare_kill_log);
const show_death_log = ref(usePage().props.show_death_table);
const show_kill_log = ref(usePage().props.show_kill_table);
const modal_name = ref("");
const rank_type = ref([]);

const skill_rank = computed(() => usePage().props.skill_rank);

async function openModal(comparative_name, target) {
    modal_name.value = comparative_name;

    const modalActions = {
        Kill: async () => {
            await router.get(
                `/rankings/bg-profile/${char_id}`,
                {
                    victim: target,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        modal.value = true;
                    },
                }
            );
        },
        Death: async () => {
            await router.get(
                `/rankings/bg-profile/${char_id}`,
                {
                    assasin: target,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        modal.value = true;
                    },
                }
            );
        },
        Skill: async () => {
            await router.get(
                `/rankings/bg-profile/${char_id}`,
                {
                    sid: target,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        modal.value = true;
                    },
                }
            );
        },
    };

    const action = modalActions[modal_name.value];

    if (action) {
        await action();
    }
}

function closeModal() {
    modal.value = false;
}
</script>

<template>
    <ranking-panel
        title="BG Profile"
        :filter_type="'ranking'"
        :rank="'zeny'"
        :rank_filter="rank_type"
    >
        <div class="flex text-black dark:text-white dark:text-white">
            <div
                class="w-1/3 flex flex-col items-center justify-center h-[13rem]"
            >
                <div>
                    <span class="font-bold"> Job:</span>
                    {{ get_job_name(basic_info[0].class) }} <br />
                </div>
                <div>
                    <span class="font-bold">Mas HP: </span
                    >{{ getFormatNumber(basic_info[0].max_hp) }} <br />
                </div>
                <div>
                    <span class="font-bold">Max SP: </span
                    >{{ getFormatNumber(basic_info[0].max_sp) }}
                </div>
            </div>

            <div class="w-1/3 flex items-center justify-center h-[13rem] flex-col">
                <div class="flex justify-center">
                    <img
                        class=""
                        :src="`/rochar/character/${basic_info[0].name}`"
                        alt=""
                    />
                </div>
                <div>
                    <span class="font-bold">Name: </span>
                    {{ basic_info[0].name }}
                </div>
            </div>

            <div
                class="w-1/3 flex flex-col items-center justify-center h-[13rem]"
            >
                <img class="mr-2" :src="`/${basic_info[0].emblem} `" alt="" />
                <div>
                    <span class="font-bold">Guild: </span>
                    {{ basic_info[0].gName }} <br />
                </div>
                <div>
                    <span class="font-bold">Rank Elo: </span>
                    {{ basic_info[0].gName }} <br />
                </div>
            </div>
        </div>

        <div class="w-full flex">
            <div class="flex w-1/2 p-5">
                <div class="w-full">
                    <table class="w-full">
                        <thead
                            class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-b"
                        >
                            <th colspan="3" class="p-4">General Standings</th>
                        </thead>
                        <thead
                            class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                        >
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/7427.gif"
                                        alt=""
                                    />
                                    Wins
                                </div>
                            </th>
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/7428.gif"
                                        alt=""
                                    />
                                    Tie
                                </div>
                            </th>
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/7426.gif"
                                        alt=""
                                    />
                                    <p>Lost</p>
                                </div>
                            </th>
                        </thead>
                        <tbody>
                            <tr class="text-black dark:text-white dark:text-white">
                                <td class="p-3 text-center">0</td>
                                <td class="p-3 text-center">0</td>
                                <td class="p-3 text-center">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex w-1/2 p-5">
                <div class="w-full">
                    <table class="w-full">
                        <thead
                            class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-b"
                        >
                            <th colspan="3" class="p-4">Damage</th>
                        </thead>
                        <thead
                            class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                        >
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/1228.png"
                                        alt=""
                                    />
                                    Top
                                </div>
                            </th>
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/1281.png"
                                        alt=""
                                    />
                                    Done
                                </div>
                            </th>
                            <th class="p-2">
                                <div class="flex items-center justify-center">
                                    <img
                                        class="mr-2"
                                        src="/img/item/7024.png"
                                        alt=""
                                    />
                                    Recv
                                </div>
                            </th>
                        </thead>
                        <tbody>
                            <tr class="text-black dark:text-white">
                                <td class="p-3 text-center">
                                    {{
                                        getFormatNumber(
                                            basic_info[0].top_damage
                                        )
                                    }}
                                </td>
                                <td class="p-3 text-center">
                                    {{
                                        getFormatNumber(
                                            basic_info[0].damage_done
                                        )
                                    }}
                                </td>
                                <td class="p-3 text-center">
                                    {{
                                        getFormatNumber(
                                            basic_info[0].damage_received
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="p-5">
            <table class="w-full">
                <thead
                    class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                >
                    <th colspan="3" class="p-4 border-b">Usable items</th>
                </thead>
                <tbody>
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150 text-black dark:text-white"
                    >
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/522.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold">HP: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].hp_heal_potions
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/505.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> SP: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].sp_heal_potions
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/676.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Zeny: </span>
                                    {{
                                        getFormatNumber(basic_info[0].zeny_used)
                                    }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150 text-black dark:text-white"
                    >
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/716.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Gems: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].red_gemstones
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/717.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold">Gems: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].blue_gemstones
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/715.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Gems: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].yellow_gemstones
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150 text-black dark:text-white"
                    >
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/1752.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Arrows: </span>
                                    {{
                                        getFormatNumber(basic_info[0].ammo_used)
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/7136.png"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Bottles: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].acid_demostration
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/678.png"
                                    alt=""
                                    class="mr-2"
                                />

                                <div>
                                    <span class="font-bold"> Bottles: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].poison_bottles
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-5 flex">
            <table class="w-1/2 mt-5">
                <thead
                    class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-b"
                >
                    <th colspan="4" class="p-4">Destroyed Structures</th>
                </thead>
                <tbody class="text-black dark:text-white">
                    <tr class="spaceUnder">
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/714.png"
                                    alt=""
                                    class="mr-2"
                                />

                                <div>
                                    <span class="font-bold"> Emperium: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_emperium_kill +
                                                basic_info[0].cs_emperium_kill +
                                                basic_info[0].dte_emperium_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/14287.gif"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Barricade: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_barricade_kill +
                                                basic_info[0].cs_barricade_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/7839.gif"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> G. Stone: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_gstone_kill +
                                                basic_info[0].cs_gstone_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="w-1/2 ml-9 mt-5">
                <thead
                    class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-b"
                >
                    <th colspan="4" class="p-4">Battleground Stadistics</th>
                </thead>
                <tbody class="text-black dark:text-white">
                    <tr class="spaceUnder">
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/7253.gif"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> G. win: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_gstone_kill +
                                                basic_info[0].cs_gstone_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/7428.gif"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Tie </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_gstone_kill +
                                                basic_info[0].cs_gstone_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                        <td class="w-1/3 p-3">
                            <div class="flex justify-center">
                                <img
                                    src="/img/item/7426.gif"
                                    alt=""
                                    class="mr-2"
                                />
                                <div>
                                    <span class="font-bold"> Lost: </span>
                                    {{
                                        getFormatNumber(
                                            basic_info[0].cq_gstone_kill +
                                                basic_info[0].cs_gstone_kill
                                        )
                                    }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-5">
            <div class="text-black dark:text-white">
                <h1 class="text-xl font-bold mb-5">Kill Log - BG</h1>
                <JetButton
                    class="bg-sky-500 hover:bg-sky-700 mb-5"
                    @click="show_kill_log = !show_kill_log"
                >
                    Show More
                </JetButton>
            </div>
            <Transition name="fade" @enter="enter">
                <table class="w-full" v-if="show_kill_log">
                    <thead>
                        <tr
                            class="spaceUnder font-bold bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-1"
                        >
                            <td class="p-3">
                                <div class="flex justify-center">Date/Time</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">P</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">C</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">Victim</div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">Map</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">Attack</div>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="text-black dark:text-white">
                        <tr
                            class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150"
                            v-for="kill in kill_log"
                        >
                            <td class="p-3">
                                <div class="flex justify-center">
                                    {{ kill.time }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">
                                    <Link
                                        :href="
                                            route('bg.profile', kill.killed_id)
                                        "
                                    >
                                        <img src="/img/viewprofile.png" />
                                    </Link>
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center cursor-pointer">
                                    <img
                                        src="/img/compare.png"
                                        alt=""
                                        @click="
                                            openModal('Kill', kill.killed_id)
                                        "
                                    />
                                </div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">
                                    <img
                                        :src="`/rochar/character-head/${kill.name}`"
                                        alt=""
                                    />
                                    <div class="flex items-center">
                                        <span class="text-[#04aaff]">{{
                                            kill.killed
                                        }}</span>
                                        ({{ get_job_name(kill.class) }})
                                    </div>
                                </div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">
                                    {{ mapname2bgname(kill.map) }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">
                                    <img
                                        :src="`https://static.divine-pride.net/images/skill/${kill.skill}.png`"
                                        alt=""
                                        srcset=""
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </Transition>
        </div>

        <div class="p-5">
            <div class="text-black dark:text-white">
                <h1 class="text-xl font-bold mb-5">Death Log - BG</h1>
                <JetButton
                    class="bg-sky-500 hover:bg-sky-700 mb-5"
                    @click="show_death_log = !show_death_log"
                >
                    Show More
                </JetButton>
            </div>
            <Transition name="fade" v-on:enter="enter">
                <table class="w-full" v-if="show_death_log">
                    <thead>
                        <tr
                            class="spaceUnder font-bold bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-1"
                        >
                            <td class="p-3">
                                <div class="flex justify-center">Date/Time</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">P</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">C</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">Killer</div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">Map</div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">Attack</div>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="text-black dark:text-white">
                        <tr
                            class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150"
                            v-for="death in death_log"
                        >
                            <td class="p-3">
                                <div class="flex justify-center">
                                    {{ death.time }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">
                                    <Link
                                        :href="
                                            route('bg.profile', death.killer_id)
                                        "
                                    >
                                        <img src="/img/viewprofile.png" />
                                    </Link>
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center cursor-pointer">
                                    <img
                                        src="/img/compare.png"
                                        alt=""
                                        @click="
                                            openModal('Death', death.killer_id)
                                        "
                                    />
                                </div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">
                                    <img
                                        :src="`/rochar/character-head/${death.name}`"
                                        alt=""
                                    />
                                    <div>
                                        <span class="text-[#04aaff]">{{
                                            death.killer
                                        }}</span>
                                        ({{ get_job_name(death.class) }})
                                    </div>
                                </div>
                            </td>

                            <td class="p-3">
                                <div class="flex justify-center">
                                    {{ mapname2bgname(death.map) }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="flex justify-center">
                                    <img
                                        :src="`https://static.divine-pride.net/images/skill/${death.skill}.png`"
                                        alt=""
                                        srcset=""
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </Transition>
        </div>

        <div class="p-5">
            <table class="w-full">
                <thead
                    class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-b"
                >
                    <th colspan="3" class="p-4">Top 10 Casted Skills</th>
                </thead>
                <thead>
                    <tr
                        class="spaceUnder font-bold bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-1"
                    >
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">Skill Name</div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">T</div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">Count</div>
                        </td>
                    </tr>
                </thead>

                <tbody class="text-black dark:text-white">
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 hover:transition hover:duration-150"
                        v-for="skill in skill_count"
                    >
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">
                                <div>
                                    <img
                                        :src="`https://static.divine-pride.net/images/skill/${skill.id}.png`"
                                        alt=""
                                        class="mr-5"
                                    />
                                </div>
                                {{ get_skill_name(skill.id) }}
                            </div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center cursor-pointer">
                                <img
                                    :src="`/img/compare.png`"
                                    alt=""
                                    @click="openModal('Skill', skill.id)"
                                />
                            </div>
                        </td>
                        <td class="w-1/4 p-3">
                            <div class="flex justify-center">
                                {{ getFormatNumber(skill.count) }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </ranking-panel>

    <!-- modal -->
    <JetModalDialog
        :show="modal"
        class="text-black dark:text-white"
        @close="closeModal"
        :class="'w-[35rem]'"
    >
        <template #title>{{ modal_name }} Log Comparative </template>
        <template #content>
            <table class="w-full">
                <thead>
                    <tr
                        v-if="modal_name === 'Death' || modal_name === 'Kill'"
                        class="spaceUnder font-bold bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-1 text-white"
                    >
                        <td class="p-3">
                            <div class="flex justify-center">Date/Time</div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">P</div>
                        </td>
                        <td class="p-3" v-if="modal_name === 'Death'">
                            <div class="flex justify-center">Killer</div>
                        </td>
                        <td class="p-3" v-else>
                            <div class="flex justify-center">Victim</div>
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">Map</div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">Attack</div>
                        </td>
                    </tr>
                    <tr
                        v-else
                        class="spaceUnder font-bold bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md border-1 text-white"
                    >
                        <td class="p-3">
                            <div class="flex justify-center">Pos</div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">P</div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">Caster</div>
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">Count</div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">Skill</div>
                        </td>
                    </tr>
                </thead>
                <tbody class="">
                    <tr
                        class="spaceUnder"
                        v-for="death in compare_death_log"
                        v-if="modal_name == 'Death'"
                    >
                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ death.time }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <Link
                                    :href="route('bg.profile', death.killer_id)"
                                >
                                    <img src="/img/viewprofile.png" />
                                </Link>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <img
                                    :src="`/rochar/character-head/${death.name}`"
                                    alt=""
                                />
                                <div>
                                    <span class="text-[#04aaff]">{{
                                        death.killer
                                    }}</span>
                                    ({{ get_job_name(death.class) }})
                                </div>
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ mapname2bgname(death.map) }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <img
                                    :src="`/img/skill_db/${death.skill}.png`"
                                    alt=""
                                    srcset=""
                                />
                            </div>
                        </td>
                    </tr>
                    <tr
                        class="spaceUnder text-sm"
                        v-for="kill in compare_kill_log"
                        v-else-if="modal_name == 'Kill'"
                    >
                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ kill.time }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <Link
                                    :href="route('bg.profile', kill.killer_id)"
                                >
                                    <img src="/img/viewprofile.png" />
                                </Link>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <img
                                    :src="`/rochar/character-head/${kill.name}`"
                                    alt=""
                                />
                                <div>
                                    <span class="text-[#04aaff]">{{
                                        kill.killed
                                    }}</span>
                                    ({{ get_job_name(kill.class) }})
                                </div>
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ mapname2bgname(kill.map) }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <img
                                    :src="`/img/skill_db/${kill.skill}.png`"
                                    alt=""
                                    srcset=""
                                />
                            </div>
                        </td>
                    </tr>
                    <tr
                        v-else
                        class="spaceUnder"
                        v-for="(skill, index) in skill_rank"
                    >
                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ index + 1 }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <Link
                                    :href="route('bg.profile', skill.char_id)"
                                >
                                    <img src="/img/viewprofile.png" />
                                </Link>
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <div>
                                    <span class="text-[#04aaff]">{{
                                        skill.name
                                    }}</span>
                                    ({{ get_job_name(skill.class) }})
                                </div>
                            </div>
                        </td>

                        <td class="p-3">
                            <div class="flex justify-center">
                                {{ getFormatNumber(skill.count) }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center">
                                <img
                                    :src="`https://static.divine-pride.net/images/skill/${skill.id}.png`"
                                    alt=""
                                    srcset=""
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template #footer>
            <jet-button class="ml-3" @click="closeModal">Close</jet-button>
        </template>
    </JetModalDialog>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 1s;
}

.fade-enter,
.fade-leave-to
/* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
