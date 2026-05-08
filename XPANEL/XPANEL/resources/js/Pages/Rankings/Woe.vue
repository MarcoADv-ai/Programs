<script setup>
import { ref, computed } from "vue";
import RankingPanel from "@/Components/RankingPanel.vue";
import { usePage } from "@inertiajs/vue3";
import RankingTable from "@/Components/RankingTable.vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import {
    get_job_name,
    getFormatNumber,
    secondsToDh,
} from "@/helpers";
import Pagination from "@/Components/Pagination.vue";
import CountryFlag from "vue-country-flag-next";
const woe = computed(() => usePage().props.woe);
const woe_dates = computed(() => usePage().props.dates_woe);
const rank_types = ref([
    {
        id: "KC",
        name_US: "Kill Counter",
        name_ES: "Contador de Muertes",
        name_PT: "Contador de Mortes",
        name_FR: "Compteur de Morts",
    },
    {
        id: "DC",
        name_US: "Die Counter",
        name_ES: "Contador de Muertes",
        name_PT: "Contador de Mortes",
        name_FR: "Compteur de Morts",
    },
    {
        id: "DD",
        name_US: "Damage Done",
        name_ES: "Daño Causado",
        name_PT: "Dano Causado",
        name_FR: "Dommages Infligés",
    },
    {
        id: "DR",
        name_US: "Damage Received",
        name_ES: "Daño Recibido",
        name_PT: "Dano Recebido",
        name_FR: "Dommages Reçus",
    },
    {
        id: "ACC",
        name_US: "Acid Demonstration Casted",
        name_ES: "Lanzamiento de Acid Demostration",
        name_PT: "Acid Demonstration Lançado",
        name_FR: "Lancement d'Acid Demonstration",
    },
    {
        id: "TGH",
        name_US: "Good Healing",
        name_ES: "Curación Efectiva",
        name_PT: "Cura Efetiva",
        name_FR: "Bon Rétablissement",
    },
    {
        id: "TWH",
        name_US: "Wrong Healing",
        name_ES: "Curación Incorrecta",
        name_PT: "Cura Incorreta",
        name_FR: "Mauvais Rétablissement",
    },
    {
        id: "GSS",
        name_US: "Good Support",
        name_ES: "Soporte Efectivo",
        name_PT: "Suporte Efetivo",
        name_FR: "Bon Soutien",
    },
    {
        id: "WSS",
        name_US: "Wrong Support",
        name_ES: "Soporte Incorrecto",
        name_PT: "Suporte Incorreto",
        name_FR: "Mauvais Soutien",
    },
    {
        id: "HPP",
        name_US: "HP Potions",
        name_ES: "Pociones de HP",
        name_PT: "Poções de HP",
        name_FR: "Potions de HP",
    },
    {
        id: "SPP",
        name_US: "SP Potions",
        name_ES: "Pociones de SP",
        name_PT: "Poções de SP",
        name_FR: "Potions de SP",
    },
    {
        id: "YGU",
        name_US: "Yellow Gemstone",
        name_ES: "Gema Amarilla",
        name_PT: "Gema Amarela",
        name_FR: "Gemme Jaune",
    },
    {
        id: "RGU",
        name_US: "Red Gemstone",
        name_ES: "Gema Roja",
        name_PT: "Gema Vermelha",
        name_FR: "Gemme Rouge",
    },
    {
        id: "BGU",
        name_US: "Blue Gemstone",
        name_ES: "Gema Azul",
        name_PT: "Gema Azul",
        name_FR: "Gemme Bleue",
    },
    {
        id: "AU",
        name_US: "Arrows Used",
        name_ES: "Flechas Utilizadas",
        name_PT: "Setas Utilizadas",
        name_FR: "Flèches Utilisées",
    },
]);

const rank_attributes = ref([
    "Player",
    "Guild",
    "Kill",
    "Death",
    "Country",
    "Score",
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
    <RankingLayout>
        <ranking-panel
            title="WoE"
            :rank_filter="rank_types"
            :rank="'woe'"
            :filter_type="'ranking'"
            :dates_woe = "woe_dates"
        >
            <ranking-table :rank_attributes="rank_attributes">
                <template #row>
                    <template v-for="(woe, index) in woe.data" :key="index">
                        <tr
                            class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 cursor-pointer hover:transition hover:duration-150"
                            @click="show_info(index)"
                        >
                            <td class="px-5 py-3 w-1/12" v-if="index == 0">
                                <font-awesome-icon
                                    class="text-2xl hover:text-yellow-600"
                                    icon="fa-solid fa-trophy"
                                />
                            </td>
                            <td class="px-5 py-3 w-1/12" v-else>
                                {{ index + 1 }}
                            </td>
                            <td class="px-5 py-3 w-1/6">
                                <div class="flex items-center justify-center">
                                    <div
                                        class="flex justify-center items-center"
                                    >
                                        <img
                                            :src="`/rochar/character-head/${woe.name}`"
                                            alt=""
                                        />
                                        <div>
                                            <span class="text-[#04aaff]">
                                                {{ woe.name }}
                                            </span>
                                            ({{ get_job_name(woe.class) }})
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3 w-1/6">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <img
                                            v-if="woe.emblem != 'X'"
                                            class="mr-2"
                                            :src="`/storage/cache/tmp_emblem/${woe.guild_id}.png `"
                                            alt=""
                                        />
                                        {{ woe.GName }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 w-1/6">
                                {{ getFormatNumber(woe.kill_count) }}
                            </td>
                            <td class="px-5 py-3 w-1/6">
                                {{ getFormatNumber(woe.death_count) }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex justify-center items-center">
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                    
                                        <country-flag
                                            :country="woe.country"
                                            size="normal"
                                        />
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 w-1/6">
                                {{ getFormatNumber(woe.score) }}
                            </td>
                        </tr>
                        <tr
                            :id="'show_info_id_' + index"
                            class="hidden spaceUnder"
                        >
                            <td colspan="9">
                                <div class="flex w-full">
                                    <div class="w-1/5 py-6">
                                        <div
                                            class="flex flex-col items-center h-full justify-center"
                                        >
                                            <div class="flex justify-center">
                                                <img
                                                    class=""
                                                    :src="`/rochar/character/${woe.name}`"
                                                    alt=""
                                                />
                                            </div>
                                            <p>
                                                <span class="font-bold"
                                                    >Playtime:</span
                                                >
                                                {{ secondsToDh(woe.playtime) }}
                                            </p>
                                            <p>
                                                <span class="font-bold"
                                                    >Score:</span
                                                >
                                                {{ getFormatNumber(woe.score) }}
                                            </p>
                                            <div
                                                class="flex w-full justify-center"
                                            >
                                                <img
                                                    src="/img/viewprofile.png"
                                                    alt=""
                                                    class="mr-2"
                                                />
                                                <a
                                                    class="text-[#04aaff]"
                                                    :href="
                                                        route(
                                                            'woe.profile',
                                                            woe.char_id
                                                        )
                                                    "
                                                    >Player Profile</a
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-4/5 grid grid-row-2">
                                        <div class="flex w-full">
                                            <div class="w-1/2 p-2">
                                                <table class="w-full">
                                                    <thead class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white">
                                                        <th
                                                            colspan="3"
                                                            class="p-4"
                                                        >
                                                            Usable items
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/522.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />HP:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.hp_heal_potions
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/505.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />SP:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.sp_heal_potions
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/676.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Zeny:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.zeny_used
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/716.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Gems:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.red_gemstones
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td class="w-1/3">
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/717.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Gems:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.blue_gemstones
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/715.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Gems:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.yellow_gemstones
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/1752.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Arrows:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.ammo_used
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/7136.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Bottles:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.acid_demostration
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/678.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />Bottles:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.poison_bottles
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="w-1/2 p-2 flex">
                                                <table class="w-1/2 mr-2">
                                                    <thead class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white">
                                                        <th
                                                            colspan="3"
                                                            class="p-4"
                                                        >
                                                            Damage vs Player
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/1228.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Best:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.top_damage
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/1281.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Done:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.damage_done
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/2115.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Recv:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.damage_received
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="w-1/2">
                                                    <thead class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white">
                                                        <th
                                                            colspan="3"
                                                            class="p-4"
                                                        >
                                                            War Data
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3 border-b"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/7005.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Kills:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.kill_count
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td
                                                                class="w-1/3 p-3"
                                                            >
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/7024.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Deaths:
                                                                    {{
                                                                        getFormatNumber(
                                                                            woe.death_count
                                                                        )
                                                                    }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="flex w-full">
                                            <div class="w-1/2 p-2">
                                                <table class="w-full">
                                                    <thead
                                                        class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white border-b"
                                                    >
                                                        <th
                                                            colspan="5"
                                                            class="p-4"
                                                        >
                                                            Structure
                                                        </th>
                                                    </thead>
                                                    <thead class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white">
                                                        <th class="w-1/5 p-2">
                                                            Option
                                                        </th>
                                                        <th class="p-2">
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    class="mr-2"
                                                                    src="/img/item/714.png"
                                                                    alt=""
                                                                />
                                                                Emperium
                                                            </div>
                                                        </th>
                                                        <th class="p-2">
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    class="mr-2"
                                                                    src="/img/item/1068.png"
                                                                    alt=""
                                                                />
                                                                Barricade
                                                            </div>
                                                        </th>
                                                        <th class="p-2">
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    class="mr-2"
                                                                    src="/img/item/7430.png"
                                                                    alt=""
                                                                />
                                                                G.Stone
                                                            </div>
                                                        </th>
                                                        <th class="p-2">
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <img
                                                                    class="mr-2"
                                                                    src="/img/item/14288.png"
                                                                    alt=""
                                                                />
                                                                Guardian
                                                            </div>
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="border-b">
                                                            <td class="p-3">
                                                                Damage Structure
                                                            </td>
                                                            <td>
                                                                {{
                                                                    woe.emperium_damage
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.barricade_damage
                                                                }}
                                                            </td>
                                                            <td>
                                                                {{
                                                                    woe.gstone_damage
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.guardian_damage
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td class="p-3">
                                                                Eliminated
                                                                Structures
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.emperium_kill
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.barricade_kill
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.gstone_kill
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.guardian_kill
                                                                }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="w-1/2 p-2">
                                                <table class="w-full">
                                                    <thead
                                                        class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white border-b"
                                                    >
                                                        <th
                                                            class="p-4"
                                                            colspan="3"
                                                        >
                                                            Support Skills
                                                        </th>
                                                    </thead>
                                                    <thead class="bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md text-white">
                                                        <th class="p-2">
                                                            Option
                                                        </th>
                                                        <th class="p-2">
                                                            Total Skills
                                                        </th>
                                                        <th class="p-2">
                                                            Total Healing
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="border-b">
                                                            <td class="p-3">
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/2273.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Correct
                                                                </div>
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.support_skills_used
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.healing_done
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr class="spaceUnder">
                                                            <td class="p-3">
                                                                <div
                                                                    class="flex justify-center"
                                                                >
                                                                    <img
                                                                        src="/img/item/5272.png"
                                                                        alt=""
                                                                        class="mr-2"
                                                                    />
                                                                    Erroneous
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{
                                                                    woe.wrong_support_skills_used
                                                                }}
                                                            </td>
                                                            <td class="p-3">
                                                                {{
                                                                    woe.wrong_healing_done
                                                                }}
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
            <pagination class="p-3 text-white" :links="woe.links" />
        </ranking-panel>
    </RankingLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
