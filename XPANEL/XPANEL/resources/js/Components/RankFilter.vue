<script setup>
import {
    FunnelIcon,
    BarsArrowUpIcon,
    UsersIcon,
} from "@heroicons/vue/20/solid";
import xSelectInput from "@/Components/SelectInput.vue";
import { ranking_jobs, rank_woe_type, whosell_type, itemdb_type } from "@/helpers";
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
} from "@headlessui/vue";
import { onMounted, ref, watch, computed, reactive } from "vue";
import { usePage, router } from "@inertiajs/vue3";

let pageInitialized = false;
const props = defineProps(["rank_types", "rank", "filter_type", "dates_woe"]);
const dates_woe = ref(props.dates_woe ?? []);
const filterSelected = props.filter_type ?? "ranking";
const woe_dates = ref([]);
const woe_filter = ref([]);
const woe_id = usePage().props.woe_id;
const woe_played = usePage().props.woe_played;
const querySearch = ref("");
const lang = localStorage.getItem("lang");
const filters = reactive({
    classes: [],
    type: [],

    // WoE Attributes
    woeDay: "",
    woeId: 0,
});
const getRankJobsFilters = () => {
    const mergedFilters = [
        {
            value: "All",
            label: "All",
            checked: true,
        },
        ...ranking_jobs.map((job_info) => ({
            value: job_info.id,
            label: job_info.name,
            checked: false,
        })),
    ];

    return mergedFilters;
};

const getRankTypeFilters = () => {
    return props.rank_types.map((type) => ({
        value: type.id,
        label: type["name_" + lang],
        checked: false,
    }));
};

const getWoeDates = () => {
    return dates_woe.value.map((dates) => ({
        value: dates.date,
        label: dates.date,
        select: dates.date,
    }));
};

const getWoeDamageTypeFilters = () => {
    return rank_woe_type.map((type) => ({
        value: type.id,
        label: type.name,
        checked: type.id === woe_id,
    }));
};

const getWhosellTypeFilters = () => {
    return itemdb_type.map((type) => ({
        value: type.id,
        label: type["name_" + lang],
        checked: false,
    }));
};

const getItemDBTypeFilters = () => {
    return whosell_type.map((type) => ({
        value: type.id,
        label: type["name_" + lang],
        checked: false,
    }));
};

const handleWoEDate = (value) => {
    if (!pageInitialized) return;
    filters.woeDay = value;
    updateFilter();
};

function getFilters() {
    // console.log(filterSelected);
    switch (filterSelected) {
        case "woe-profile":
        case "woe-damage":
            woe_dates.value = getWoeDates();
            woe_filter.value = getWoeDamageTypeFilters();
            break;

        case "ranking":
            filters.classes = getRankJobsFilters();
            filters.type = getRankTypeFilters();
            if (props.rank === "woe") {
                woe_filter.value = getWoeDamageTypeFilters();
                woe_dates.value = getWoeDates();
            }
            break;

        case "whosell":
            filters.type = getWhosellTypeFilters();
            break;

        case "itemdb":
            filters.type = getItemDBTypeFilters();
            break;
    }
    return;
}

const filterType = (event, type) => {
    const group = event.target.name;
    const eventValue = event.target.value;
    const checked = event.target.checked;
    const items = document.getElementsByName(group);

    switch (type) {
        case "woe-profile":
        case "woe-damage":
            if (!pageInitialized) return;
            filters.woeId = parseInt(eventValue);
            if (checked) {
                filters.type.forEach((type_info) => {
                    type_info.checked = type_info.value === eventValue;
                });
            } else {
                filters.type.forEach((type_info) => {
                    type_info.checked = false;
                });
            }

            items.forEach((item) => {
                item.checked = item.value === eventValue && checked;
            });

            updateFilter();
            break;

        case "classes":
            if (checked) {
                filters.classes.forEach((class_info) => {
                    if (eventValue === "All") {
                        filters.classes.forEach((class_info) => {
                            if (class_info.value != "All")
                                class_info.checked = false;
                        });
                    }

                    if (class_info.value == eventValue) {
                        class_info.checked = true;
                    } else if (class_info.value == "All") {
                        class_info.checked = false;
                    }
                });
            } else {
                filters.classes.filter((class_info) => {
                    if (class_info.value == eventValue) {
                        class_info.checked = false;
                    }
                });
            }
            updateFilter();
            break;

        case "type":
            if (checked) {
                filters.type.forEach((type_info) => {
                    type_info.checked = type_info.value === eventValue;
                });
            } else {
                filters.type.forEach((type_info) => {
                    type_info.checked = false;
                });
            }

            items.forEach((item) => {
                item.checked = item.value === eventValue && checked;
            });

            updateFilter();
            break;
    }
};

const filterCounts = computed(() => {
    const count =
        filters.classes.reduce(
            (acc, class_info) => acc + (class_info.checked ? 1 : 0),
            0
        ) +
        filters.type.reduce(
            (acc, type_info) => acc + (type_info.checked ? 1 : 0),
            0
        );

    return count;
});

const clearChecks = () => {
    const clear = (arr) =>
        arr.forEach((item) => {
            if (item.value != "All") item.checked = false;
            else item.checked = true;
        });

    clear(filters.classes);
    clear(filters.type);
    updateFilter();
};

function updateFilter() {
    const checkedClasses = filters.classes.filter(
        (class_info) => class_info.checked
    );
    const checkedTypes = filters.type.filter((type_info) => type_info.checked);
    const classes = checkedClasses
        .map((class_info) => class_info.value)
        .join(",");

    const types = checkedTypes.map((type_info) => type_info.value).join(",");
    const generalParams = {
        q: querySearch.value,
        class: classes,
        filter_type: types,
    };

    const woeParams = {
        q: querySearch.value,
        class: classes,
        filter_type: types,
        woe_id: filters.woeId,
        woe_day: filters.woeDay,
    };

    const woeDamageParams = {
        woe_id: filters.woeId,
        woe_day: filters.woeDay,
    };

    const whosellParams = {
        filter_type: types,
        q: querySearch.value,
    };

    const itemdbParams = {
        filter_type: types,
        q: querySearch.value,
    };

    switch (filterSelected) {
        case "woe-profile":
        case "woe-damage":
            if (!pageInitialized) return;
            const url = new URL(window.location.href);
            const pathSegmenths = url.pathname.split("/");
            const id = pathSegmenths[pathSegmenths.length - 1];
            const filtredWoeParams = Object.fromEntries(
                Object.entries(woeDamageParams).filter(
                    ([key, value]) => value != ""
                )
            );

            const rank_url =
                filterSelected === "woe-profile"
                    ? `/rankings/woe-profile/${id}`
                    : `/rankings/${props.rank}`;

            router.get(rank_url, filtredWoeParams, {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => {
                    dates_woe.value = usePage().props.dates_woe;
                    woe_dates.value = getWoeDates();
                },
            });

            break;

        case "ranking":
            const filtredParams = Object.fromEntries(
                Object.entries(
                    props.rank === "woe" ? woeParams : generalParams
                ).filter(([key, value]) => value != "")
            );
            router.get(`/rankings/${props.rank}`, filtredParams, {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => {
                    dates_woe.value = usePage().props.dates_woe;
                    woe_dates.value = getWoeDates();
                },
            });
            break;

        case "whosell":
            router.get(`/information/${props.rank}`, whosellParams, {
                preserveState: true,
                preserveScroll: true,
            });
            break;

        case "itemdb":
            router.get(`/information/${props.rank}`, itemdbParams, {
                preserveState: true,
                preserveScroll: true,
            });
            break;
    }
}

watch(querySearch, () => {
    updateFilter();
});

onMounted(() => {
    pageInitialized = true;
    getFilters();
});
</script>
<template>
    <div class="bg-transparent">
        <div
            class="px-4 py-16 text-center sm:px-6 lg:px-8"
            v-if="filterSelected === 'ranking'"
        >
            <h1 class="text-4xl font-bold tracking-tight text-black">
                <template v-if="lang == 'US'"> Ranking </template>
                <template v-else-if="lang == 'FR'"> Classement </template>
                <template v-else-if="lang == 'ES'"> Clasificación </template>
                <template v-else-if="lang == 'PT'"> Classificação </template>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-base text-black">
                <template v-if="lang == 'US'">
                    Filter the ranking by class, type and search for a specific
                    player.
                </template>
                <template v-else-if="lang == 'FR'">
                    Filtrez le classement par classe, type et recherchez un
                    joueur spécifique.
                </template>
                <template v-else-if="lang == 'ES'">
                    Filtra la clasificación por clase, tipo y busca un jugador
                    específico.
                </template>
                <template v-else-if="lang == 'PT'">
                    Filtrar a classificação por classe, tipo e procurar um
                    jogador específico.
                </template>
            </p>
        </div>
        <div
            class="px-4 py-16 text-center sm:px-6 lg:px-8"
            v-else-if="filterSelected === 'whosell'"
        >
            <h1 class="text-4xl font-bold tracking-tight text-black">
                <template v-if="lang == 'US'"> Who Sell </template>
                <template v-else-if="lang == 'FR'"> Qui vend </template>
                <template v-else-if="lang == 'ES'"> Quien vende </template>
                <template v-else-if="lang == 'PT'"> Lojas de Venda </template>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-base text-black">
                <template v-if="lang == 'US'">
                    Filter the who sell by type and search for a specific item.
                </template>
                <template v-else-if="lang == 'FR'">
                    Filtrez le qui vend par type et recherchez un article
                    spécifique.
                </template>
                <template v-else-if="lang == 'ES'">
                    Filtra el que vende por tipo y busca un artículo específico.
                </template>
                <template v-else-if="lang == 'PT'">
                    Filtrar o que vende por tipo e procurar um item específico.
                </template>
            </p>
        </div>
        <div
            class="px-4 py-16 text-center sm:px-6 lg:px-8"
            v-else-if="filterSelected === 'woe-damage'"
        >
            <h1 class="text-4xl font-bold tracking-tight text-black">
                <template v-if="lang == 'US'"> Guild vs Guild </template>
                <template v-else-if="lang == 'FR'">
                    Guilde contre Guilde
                </template>
                <template v-else-if="lang == 'ES'">
                    Gremio contra Gremio
                </template>
                <template v-else-if="lang == 'PT'">
                    Guilda contra Guilda
                </template>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-base text-black">
                <template v-if="lang == 'US'">
                    Filter the guild vs guild by class, type and search for a
                    specific player.
                </template>
                <template v-else-if="lang == 'FR'">
                    Filtrez le guilde contre guilde par classe, type et
                    recherchez un joueur spécifique.
                </template>
                <template v-else-if="lang == 'ES'">
                    Filtra el gremio contra gremio por clase, tipo y busca un
                    jugador específico.
                </template>
                <template v-else-if="lang == 'PT'">
                    Filtrar a guilda contra guilda por classe, tipo e procurar
                    um jogador específico.
                </template>
            </p>
        </div>
        <div
            class="px-4 py-16 text-center sm:px-6 lg:px-8"
            v-else-if="filterSelected === 'woe-profile'"
        >
            <h1 class="text-4xl font-bold tracking-tight text-black">
                <template v-if="lang == 'US'"> WoE Profile </template>
                <template v-else-if="lang == 'FR'"> Profil de WoE </template>
                <template v-else-if="lang == 'ES'"> Perfil de WoE </template>
                <template v-else-if="lang == 'PT'"> Perfil de WoE </template>
            </h1>
            <p class="mx-auto mt-4 max-w-xl text-base text-black">
                <template v-if="lang == 'US'">
                    Filter the WoE Profile by class, type and search for a
                    specific player.
                </template>
                <template v-else-if="lang == 'FR'">
                    Filtrez le profil de WoE par classe, type et recherchez un
                    joueur spécifique.
                </template>
                <template v-else-if="lang == 'ES'">
                    Filtra el perfil de WoE por clase, tipo y busca un jugador
                    específico.
                </template>
                <template v-else-if="lang == 'PT'">
                    Filtrar o perfil de WoE por classe, tipo e procurar um
                    jogador específico.
                </template>
            </p>
        </div>

        <!-- Filters -->
        <Disclosure
            as="section"
            aria-labelledby="filter-heading"
            class="bg-white grid items-center border-b border-sky-400 rounded-t-lg shadow-md dark:bg-gray-900"
        >
            <h2 id="filter-heading" class="sr-only">Filters</h2>
            <div class="relative col-start-1 row-start-1 py-4">
                <div
                    class="mx-auto flex max-w-7xl space-x-6 divide-x divide-gray-200 px-4 text-sm sm:px-6 lg:px-8"
                >
                    <div>
                        <DisclosureButton
                            class="group flex items-center font-medium text-gray-900 dark:text-gray-300 dark:group-hover:text-gray-200"
                        >
                            <FunnelIcon
                                class="mr-2 h-5 w-5 flex-none text-gray-700 group-hover:text-black-500 dark:text-gray-300 dark:group-hover:text-gray-200"
                                aria-hidden="true"
                            />{{ filterCounts }}
                            {{ filterCounts === 1 ? "Filter" : "Filters" }}
                        </DisclosureButton>
                    </div>
                    <div class="pl-6">
                        <button
                            type="button"
                            @click="clearChecks"
                            class="text-gray-900 dark:text-gray-300 dark:hover:text-gray-200"
                        >
                            <template v-if="lang == 'US'"> Clear all </template>
                            <template v-else-if="lang == 'FR'">
                                Effacer tout
                            </template>
                            <template v-else-if="lang == 'ES'">
                                Borrar todo
                            </template>
                            <template v-else-if="lang == 'PT'">
                                Limpar tudo
                            </template>
                        </button>
                    </div>
                </div>
            </div>
            <DisclosurePanel class="border-t border-sky-400 py-10">
                <div
                    class="mx-auto grid max-w-7xl grid-cols-2 gap-x-4 px-4 text-sm sm:px-6 md:gap-x-6 lg:px-8 text-gray-900 dark:text-gray-300"
                >
                    <div
                        v-if="props.filter_type === 'ranking'"
                        class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6"
                    >
                        <fieldset>
                            <legend class="block font-medium">
                                <template v-if="lang == 'US'">
                                    Filter Class
                                </template>
                                <template v-else-if="lang == 'FR'">
                                    Filtre de classe
                                </template>
                                <template v-else-if="lang == 'ES'">
                                    Filtro de clase
                                </template>
                                <template v-else-if="lang == 'PT'">
                                    Filtro de classe
                                </template>
                            </legend>
                            <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                <div
                                    v-for="(
                                        option, optionIdx
                                    ) in filters.classes"
                                    :key="option.value"
                                    class="flex items-center text-base sm:text-sm"
                                >
                                    <img
                                        v-if="option.value != 'All'"
                                        :src="`/img/job_icons/icon_jobs_${option.value}.png`"
                                        class="h-6 w-6 flex-shrink-0 mr-3 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                    />
                                    <input
                                        @change="filterType($event, 'classes')"
                                        :value="option.value"
                                        type="checkbox"
                                        class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                        :checked="option.checked"
                                    />
                                    <label
                                        :for="`class-${optionIdx}`"
                                        class="ml-3 min-w-0 flex-1 text-gray-900 dark:text-gray-300"
                                        >{{ option.label }}</label
                                    >
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="block font-medium">
                                <template v-if="lang == 'US'">
                                    Filter Type
                                </template>
                                <template v-else-if="lang == 'FR'">
                                    Type de filtre
                                </template>
                                <template v-else-if="lang == 'ES'">
                                    Tipo de filtro
                                </template>
                                <template v-else-if="lang == 'PT'">
                                    Tipo de filtro
                                </template>
                            </legend>
                            <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                <div
                                    v-for="(option, optionIdx) in filters.type"
                                    :key="option.value"
                                    class="flex items-center text-base sm:text-sm"
                                >
                                    <input
                                        @change="filterType($event, 'type')"
                                        :id="`type-${optionIdx}`"
                                        name="type"
                                        :value="option.value"
                                        type="checkbox"
                                        class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                        :checked="option.checked"
                                    />
                                    <label
                                        :for="`type-${optionIdx}`"
                                        class="ml-3 min-w-0 flex-1 text-gray-900 dark:text-gray-300"
                                        >{{ option.label }}</label
                                    >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div
                        v-if="props.filter_type === 'whosell' || props.filter_type === 'itemdb'"
                        class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6"
                    >
                        <fieldset>
                            <legend class="block font-medium">
                                <template v-if="lang == 'US'">
                                    Filter Type
                                </template>
                                <template v-else-if="lang == 'FR'">
                                    Type de filtre
                                </template>
                                <template v-else-if="lang == 'ES'">
                                    Tipo de filtro
                                </template>
                                <template v-else-if="lang == 'PT'">
                                    Tipo de filtro
                                </template>
                            </legend>
                            <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                <div
                                    v-for="(option, optionIdx) in filters.type"
                                    :key="option.value"
                                    class="flex items-center text-base sm:text-sm"
                                >
                                    <input
                                        @change="filterType($event, 'type')"
                                        :id="`type-${optionIdx}`"
                                        name="type[]"
                                        :value="option.value"
                                        type="checkbox"
                                        class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                        :checked="option.checked"
                                    />
                                    <label
                                        :for="`type-${optionIdx}`"
                                        class="ml-3 min-w-0 flex-1 text-gray-900 dark:text-gray-300"
                                        >{{ option.label }}</label
                                    >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div
                        v-if="
                            props.rank === 'woe' ||
                            props.rank === 'woe-damage'
                        "
                        class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6"
                    >
                        <fieldset>
                            <legend class="block font-medium">WoE Type</legend>
                            <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                <div
                                    v-for="(option, optionIdx) in woe_filter"
                                    :key="option.id"
                                    class="flex items-center text-base sm:text-sm"
                                >
                                        <input
                                            @change="
                                                filterType($event, 'woe-damage')
                                            "
                                            :id="`woe_type-${optionIdx}`"
                                            name="woe_type[]"
                                            :value="option.value"
                                            type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                            :checked="option.checked"
                                        />
                                        <label
                                            :for="`woe_type-${optionIdx}`"
                                            class="ml-3 min-w-0 flex-1 text-gray-900 dark:text-gray-300"
                                            >{{ option.label }}</label
                                        >
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div
                        v-else-if="
                            props.rank === 'woe-profile'
                        "
                        class="grid auto-rows-min grid-cols-1 gap-y-10 md:grid-cols-2 md:gap-x-6"
                    >
                        <fieldset>
                            <legend class="block font-medium">WoE's Played</legend>
                            <div class="space-y-6 pt-6 sm:space-y-4 sm:pt-4">
                                <div
                                    v-for="(option, optionIdx) in woe_filter"
                                    :key="option.id"
                                    class="flex items-center text-base sm:text-sm"
                                >
                                    <template v-for="woe in woe_played" >
                                        <input
                                            @change="
                                                filterType($event, 'woe-damage')
                                            "
                                            :id="`woe_type-${optionIdx}`"
                                            name="woe_type[]"
                                            :value="option.value"
                                            type="checkbox"
                                            class="h-4 w-4 flex-shrink-0 rounded border-gray-300 text-[#3b87f6] focus:ring-sky-500"
                                            :checked="option.checked"
                                            :disabled="woe.id != option.value"
                                            :class="woe.id != option.value ? 'opacity-50' : 'opacity-100'"
                                        />
                                        <label
                                            :for="`woe_type-${optionIdx}`"
                                            :class="woe.id != option.value ? 'opacity-50' : 'opacity-100' "
                                            class="ml-3 min-w-0 flex-1 text-gray-900 dark:text-gray-300"
                                            >{{ option.label }}</label
                                        >
                                    </template>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </DisclosurePanel>
            <div class="col-start-1 row-start-1 py-4">
                <div
                    class="mx-auto flex max-w-7xl justify-end px-4 sm:px-6 lg:px-8"
                >
                    <Menu as="div" class="relative inline-block">
                        <div class="flex">
                            <div
                                class="flex rounded-md shadow-sm mr-5"
                                v-if="
                                    props.rank === 'woe' ||
                                    props.rank === 'woe-damage' ||
                                    props.rank === 'woe-profile'
                                "
                            >
                                <div
                                    class="relative flex flex-grow items-stretch focus-within:z-10"
                                >
                                    <x-select-input
                                        :class="'w-[250px]'"
                                        :options="woe_dates"
                                        :placeholder="'WoE Date'"
                                        @getSelected="handleWoEDate"
                                    />
                                </div>
                            </div>
                            <div
                                class="flex rounded-md shadow-sm"
                                v-if="
                                    props.filter_type === 'ranking' ||
                                    props.filter_type === 'whosell' ||
                                    props.filter_type === 'itemdb'
                                "
                            >
                                <div
                                    class="relative flex flex-grow items-stretch focus-within:z-10"
                                >
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                    >
                                        <UsersIcon
                                            v-if="
                                                props.filter_type !== 'whosell' && props.filter_type !== 'itemdb'
                                            "
                                            class="h-5 w-5 text-gray-900"
                                            aria-hidden="true"
                                        />
                                        <svg
                                            v-else
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            class="w-6 h-6 text-black"
                                        >
                                            <path
                                                d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 007.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 004.902-5.652l-1.3-1.299a1.875 1.875 0 00-1.325-.549H5.223z"
                                            />
                                            <path
                                                fill-rule="evenodd"
                                                d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 009.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 002.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5H3zm3-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v3a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75v-3zm8.25-.75a.75.75 0 00-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.75-.75h-3z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        v-model="querySearch"
                                        name="search"
                                        id="text"
                                        class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
                                        :placeholder="
                                            props.filter_type === 'ranking'
                                                ? lang == 'US'
                                                    ? 'Search Player'
                                                    : lang == 'FR'
                                                    ? 'Rechercher un joueur'
                                                    : lang == 'ES'
                                                    ? 'Buscar jugador'
                                                    : lang == 'PT'
                                                    ? 'Pesquisar jogador'
                                                    : 'Search Player'
                                                : props.filter_type ===
                                                  'whosell' || props.filter_type === 'itemdb'
                                                ? lang == 'US'
                                                    ? 'Search Item'
                                                    : lang == 'FR'
                                                    ? 'Rechercher un article'
                                                    : lang == 'ES'
                                                    ? 'Buscar artículo'
                                                    : lang == 'PT'
                                                    ? 'Pesquisar item'
                                                    : 'Search Item'
                                                : ''
                                        "
                                    />
                                </div>
                                <button
                                    type="button"
                                    class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 bg-gray-50"
                                >
                                    <BarsArrowUpIcon
                                        class="-ml-0.5 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                    <span>
                                        <template v-if="lang == 'US'">
                                            Filter
                                        </template>
                                        <template v-else-if="lang == 'FR'">
                                            Filtre
                                        </template>
                                        <template v-else-if="lang == 'ES'">
                                            Filtro
                                        </template>
                                        <template v-else-if="lang == 'PT'">
                                            Filtro
                                        </template>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </Menu>
                </div>
            </div>
        </Disclosure>
    </div>
</template>
