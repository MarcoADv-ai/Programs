<script setup>
import { Head } from "@inertiajs/vue3";
import Filter from "@/Components/RankFilter.vue";

const props = defineProps({
    title: String,
    rank_filter: Array,
    rank: String,
    filter_type: String,
    dates_woe: Object,

});

const ranking_filter = props.rank_filter;
const rank = props.rank;
const filter_type = props.filter_type;
const dates_woe = props.dates_woe;
</script>

<template>
    <Head :title="title" />

    <div class="">
        <div class="flex flex-col items-center h-full relative z-10 min-h-screen">
            <div class="max-w-screen-2xl w-full mx-auto mt-40">
                <Filter
                    :rank_types="ranking_filter"
                    :rank="rank"
                    :filter_type="filter_type"
                    :dates_woe="dates_woe"
                />
                <div class="h-full mb-10">
                    <div class="h-auto bg-white dark:bg-gray-800">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

