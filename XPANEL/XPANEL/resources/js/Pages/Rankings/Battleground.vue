<script>
import RankingLayout from "@/Layouts/RankingLayout.vue";
export default defineComponent({
    layout: RankingLayout,
});
</script>

<script setup>
import { defineComponent, ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingTable from "@/Components/RankingTable.vue";
import { get_job_name, getFormatNumber, secondsToDh } from "@/helpers";
import CountryFlag from "vue-country-flag-next";
import Pagination from "@/Components/Pagination.vue";

const bg_data = computed(() => usePage().props.bg_data);
const show = ref(false);
const lang = localStorage.getItem("lang");
const rank_attributes = ref([
    "Player",
    "Guild",
    "Kill",
    "Death",
    "Country",
    "Score",
]);

const rank_types = ref([
    {
        id: "GW",
        name_US: "Game Wins",
        name_ES: "Juegos Ganados",
        name_PT: "Jogos Ganhou",
        name_FR: "Jeux Gagnés",
    },
    {
        id: "GL",
        name_US: "Game Lost",
        name_ES: "Juegos Perdidos",
        name_PT: "Jogos Perdidos",
        name_FR: "Jeux Perdus",
    },
    {
        id: "GT",
        name_US: "Game Tie",
        name_ES: "Juegos Empatados",
        name_PT: "Jogos Empatados",
        name_FR: "Jeux Égalité",
    },
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
        title="Battleground"
        :rank_filter="rank_types"
        :rank="'battleground'"
        :filter_type="'ranking'"
    >
        <ranking-table :rank_attributes="rank_attributes">
            <template #row>
                <template v-for="(bg, index) in bg_data.data" :key="index">
                    <tr
                        class="spaceUnder hover:bg-gray-300/40 dark:hover:bg-gray-800/40 cursor-pointer transition duration-150"
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
                                <div class="flex justify-center items-center">
                                    <img
                                        :src="`/rochar/character-head/${bg.name}`"
                                        alt=""
                                    />
                                    <div>
                                        <span class="text-[#04aaff]">
                                            {{ bg.name }}
                                        </span>
                                        ({{ get_job_name(bg.class) }})
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3 w-1/6">
                            <div class="flex justify-center items-center">
                                <div class="flex items-center justify-center">
                                    <img
                                        v-if="bg.emblem != 'X'"
                                        class="mr-2"
                                        :src="`/${bg.emblem} `"
                                        alt=""
                                    />
                                    {{ bg.gname ?? "No guild ❌" }}
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 w-1/6">
                            {{ getFormatNumber(bg.kill_count) }}
                        </td>
                        <td class="px-5 py-3 w-1/6">
                            {{ getFormatNumber(bg.death_count) }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex justify-center items-center">
                                <div class="flex items-center justify-center">
                                    <country-flag
                                        :country="bg.country"
                                        size="normal"
                                    />
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 w-1/6">
                            {{ getFormatNumber(bg.score) }}
                        </td>
                    </tr>
                    <tr :id="'show_info_id_' + index" class="hidden transition-height duration-500 ease-in-out overflow-hidden">
                        <td colspan="9">
                            <div class="flex w-full">
                                <div class="w-1/5 py-6">
                                    <div
                                        class="flex flex-col items-center h-full justify-center"
                                    >
                                    <div class="bg-player hover:rounded-tr-[5rem] hover:rounded-bl-[5rem] rounded-tr-[3rem] rounded-bl-[3rem] space-y-10 h-1/3 flex flex-col justify-center items-center shadow-md transition-all duration-500">

                                        >
                                            <div>
                                                <img
                                                    class=""
                                                    :src="`/rochar/character/${bg.name}`"
                                                    alt=""
                                                />
                                            </div>
                                            <div class="text-white w-full text-xs">
                                                <p>
                                                    <span class="font-bold "
                                                        >Playtime:</span
                                                    >
                                                    {{
                                                        secondsToDh(bg.playtime)
                                                    }}
                                                </p>
                                                <p>
                                                    <span class="font-bold"
                                                        >Score:</span
                                                    >
                                                    {{
                                                        getFormatNumber(
                                                            bg.score
                                                        )
                                                    }}
                                                </p>
                                                <div
                                                    class="flex w-full justify-center items-center"
                                                >
                                                    <img
                                                        src="/img/viewprofile.png"
                                                        alt=""
                                                        class="mr-2 h-4"
                                                    />

                                                    <a
                                                        class="text-[#04aaff]"
                                                        :href="
                                                            route(
                                                                'bg.profile',
                                                                bg.char_id
                                                            )
                                                        "
                                                        >Player Profile</a
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-4/5 grid grid-row-2">
                                    <div class="flex w-full">
                                        <div class="w-1/2 p-2">
                                            <table
                                                class="w-full border text-black"
                                            >
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="3" class="p-4">
                                                        Usable items
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="spaceUnder dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.hp_heal_potions
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.sp_heal_potions
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.zeny_used
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.red_gemstones
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
                                                                        bg.blue_gemstones
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.yellow_gemstones
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.ammo_used
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.acid_demostration
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.poison_bottles
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 flex p-2">
                                            <table class="w-1/2 mr-2 border">
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="3" class="p-4">
                                                        Damage vs Player
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.top_damage
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.damage_done
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.damage_received
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="w-1/2 border">
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="3" class="p-4">
                                                        War Data
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.kill_count
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
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
                                                                        bg.death_count
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="w-1/3 p-3">
                                                            <div
                                                                class="flex justify-center"
                                                            >
                                                                <img
                                                                    src="/img/item/5431.png"
                                                                    alt=""
                                                                    class="mr-2"
                                                                />
                                                                Desertor:
                                                                {{
                                                                    getFormatNumber(
                                                                        bg.deserter
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="flex w-full rounded">
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="border-b border-b-1 text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="4" class="p-4">
                                                        General Standings
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="w-1/5 p-2">
                                                        Option
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Won
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            Total
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.win
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            As Team Leader
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.leader_win
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.leader_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.leader_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-4" colspan="3">
                                                        Support Skills
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">Option</th>
                                                    <th class="p-2">
                                                        Total Skills
                                                    </th>
                                                    <th class="p-2">
                                                        Total Healing
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
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
                                                                bg.support_skills_used
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                bg.healing_done
                                                            }}
                                                        </td>
                                                    </tr>
                                                    <tr
                                                        class="spaceUnder hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
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
                                                                getFormatNumber(
                                                                    bg.wrong_support_skills_used
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.wrong_healing_done
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="flex w-full">
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="5" class="p-4">
                                                        Team vs Team
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7253.gif"
                                                                alt=""
                                                            />
                                                            Wins
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7005.png"
                                                                alt=""
                                                            />
                                                            Deaths
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Wins
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tvt_kills
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tvt_deaths
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tvt_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tvt_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.tvt_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-4" colspan="6">
                                                        Conquest
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="w-1/5 p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/714.png"
                                                                alt=""
                                                            />
                                                            Emp. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/14287.gif"
                                                                alt=""
                                                            />
                                                            Bar. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7839.gif"
                                                                alt=""
                                                            />
                                                            G.Stone Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Bar. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            <div
                                                                class="flex justify-center"
                                                            >
                                                                {{
                                                                    getFormatNumber(
                                                                        bg.cq_emperium_kill
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cq_barricade_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cq_gstone_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cq_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cq_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="flex w-full">
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="3" class="p-4">
                                                        Battle Zone
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="w-1/5 p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Wins
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.bz_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.bz_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.bz_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-4" colspan="5">
                                                        Destroy the Emperium
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/714.png"
                                                                alt=""
                                                            />
                                                            Emp.Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Wins
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            <div
                                                                class="flex justify-center"
                                                            >
                                                                <img
                                                                    src="/img/item/2273.png"
                                                                    alt=""
                                                                    class="mr-2"
                                                                />
                                                                {{
                                                                    getFormatNumber(
                                                                        bg.dte_emperium_kill
                                                                    )
                                                                }}
                                                            </div>
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.dte_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.dte_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.dte_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="flex w-full">
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="7" class="p-4">
                                                        Castle Siege
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/714.png"
                                                                alt=""
                                                            />
                                                            Emp. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/14287.gif"
                                                                alt=""
                                                            />
                                                            Bar. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7839.gif"
                                                                alt=""
                                                            />
                                                            G.Stone Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Win
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_emperium_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_barricade_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_gstone_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.cs_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="7" class="p-4">
                                                        King of Emperium
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/714.png"
                                                                alt=""
                                                            />
                                                            Emp. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/14287.gif"
                                                                alt=""
                                                            />
                                                            Bar. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Win
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.koe_emperium_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.koe_barricade_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.koe_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.koe_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.koe_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="flex w-full">
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="7" class="p-4">
                                                        Triple Inferno
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7005.png"
                                                                alt=""
                                                            />
                                                            Skulls
                                                        </div>
                                                    </th>

                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7427.gif"
                                                                alt=""
                                                            />
                                                            Win
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ti_skulls
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ti_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ti_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ti_lost
                                                                )
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="w-1/2 p-2">
                                            <table class="w-full border">
                                                <thead
                                                    class="text-sm border-b border-b-1 text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th colspan="7" class="p-4">
                                                        Crused Treasure
                                                    </th>
                                                </thead>
                                                <thead
                                                    class="text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md"
                                                >
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/311026.png"
                                                                alt=""
                                                            />
                                                            Base
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/1009.png"
                                                                alt=""
                                                            />
                                                            Take
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/6075.png"
                                                                alt=""
                                                            />
                                                            Droped
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/14287.gif"
                                                                alt=""
                                                            />
                                                            B. Kills
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Win
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7428.gif"
                                                                alt=""
                                                            />
                                                            Tie
                                                        </div>
                                                    </th>
                                                    <th class="p-2">
                                                        <div
                                                            class="flex items-center justify-center"
                                                        >
                                                            <img
                                                                class="mr-2"
                                                                src="/img/item/7426.gif"
                                                                alt=""
                                                            />
                                                            Lost
                                                        </div>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="border-b hover:bg-gray-50 dark:hover:bg-gray-800/40 transition duration-150"
                                                    >
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_base
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_take
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_droped
                                                                )
                                                            }}
                                                        </td>
                                                        <td class="p-3">
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_barricade_kill
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_wins
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_tie
                                                                )
                                                            }}
                                                        </td>
                                                        <td>
                                                            {{
                                                                getFormatNumber(
                                                                    bg.ct_lost
                                                                )
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
        <pagination class="p-3 text-white" :links="bg_data.links" />
    </ranking-panel>
</template>

<style scoped>
.bg-player {
    position: relative;
    background: url("/img/bg-player.png") no-repeat center center;
    background-size: cover;
    background-color: black;
    transition: background-color 0.5s ease, border-radius 0.5s ease; /* Añade la transición */
  }
  
  .bg-player::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Ajusta la opacidad según sea necesario */
  border-radius: 0 3rem 0 3rem;
  z-index: 1;
  transition: background-color 0.5s ease, border-radius 0.5s ease; /* Añade la transición */
}

.bg-player:hover::before {
  border-radius: 0 5rem 0 5rem;
}

.bg-player > * {
  position: relative;
  z-index: 2;
}

.transition-height {
  transition: height 0.5s ease-in-out, opacity 0.5s ease-in-out;
}
</style>
