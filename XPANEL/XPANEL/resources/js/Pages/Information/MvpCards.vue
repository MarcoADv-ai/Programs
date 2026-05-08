<script setup>
import RankingLayout from "@/Layouts/RankingLayout.vue";
import { usePage } from "@inertiajs/vue3";
const lang = localStorage.getItem("lang");

const mvp_cards = usePage().props.mvp_cards;
</script>
<template>
    <ranking-layout :title="'MvP Cards'">
        <section class="justify-around py-14 md:pt-40 min-h-screen">
            <h1
                class="mt-10 text-4xl font-bold tracking-tight text-black sm:text-5xl drop-shadow mb-5 text-center dark:text-white"
            >
                <template v-if="lang == 'US'">MvP Cards</template>
                <template v-else-if="lang == 'PT'">Cartas MvP</template>
                <template v-else-if="lang == 'ES'">Cartas MvP</template>
                <template v-else-if="lang == 'FR'">Cartes MvP</template>
            </h1>
            <img src="/img/logo-light.png" alt="" class="mx-auto" />

            <div class="flex flex-wrap justify-center gap-10 mt-10">
                <div
                    v-for="mvp_card in mvp_cards"
                    class="text-center"
                >
                    <img
                        :src="`/img/mvpcards/${mvp_card.nameid}.jpg`"
                        alt="mvp_card"
                        
                    />
                    <p class="font-bold">
                        Total:
                        <span class="text-green-500">{{ mvp_card.total }}</span>
                    </p>
                </div>
            </div>
        </section>
    </ranking-layout>
</template>
