<script setup>
import xWikiPage from '@/Components/WikiPage.vue';
import WikiLayout from '@/Layouts/WikiLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const wikiPost = computed(()=>usePage().props.wiki_post);
</script>

<template>
    <wiki-layout :title="wikiPost.title">
        <xWikiPage :post="wikiPost"/>
    </wiki-layout>
</template>