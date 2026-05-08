<script setup>
import RankingLayout from "@/Layouts/RankingLayout.vue";
import { Link } from "@inertiajs/vue3";
const features = [
    {
        name: "Server Information",
        description:
            "Demo is a free-to-play private Ragnarok Online server that focuses on a classic gameplay experience with quality of life improvements.",
        href: "/wiki/genera/info",
        img: "/img/server_info_icon.png",
    },
    {
        name: "Unique Mechanics",
        description:
            "Demo has a lot of unique mechanics that you can't find in other servers, such as the Demo System, the Demo Quests, and more!",
        href: "#",
        img: "/img/unique_mechanics.png",
    },
];
</script>

<template>
    <ranking-layout :title="'General'">
        <div class="py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <img src="/img/logo.png" alt="" class="w-1/2 mx-auto"/>
                    <p
                        class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl"
                    >
                        Server Information Details
                    </p>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        See all the information about the server here, from the server rates, to the server mechanics, and more!
                    </p>
                </div>
                <div
                    class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none"
                >
                    <dl
                        class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2"
                    >
                        <div
                            v-for="feature in features"
                            :key="feature.name"
                            class="bg-white flex flex-col border border-gray-200 px-8 py-6 shadow-sm sm:px-10 sm:py-8 sm:rounded-lg hover:bg-gray-50 hover:shadow-xl transition duration-300"
                        >
                            <dt
                                class="flex items-center gap-x-3 text-base font-semibold leading-7 text-gray-900"
                            >
                                <img :src="feature.img" alt="">
                                {{ feature.name }}
                            </dt>
                            <dd
                                class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600"
                            >
                                <p class="flex-auto">
                                    {{ feature.description }}
                                </p>
                                
                            </dd>
                            <Link :href="feature.href" class="mt-2 hover:text-sky-400 text-sm inline-block font-semibold leading-6 text-gray-900">Learn more <span aria-hidden="true">→</span></Link>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </ranking-layout>
</template>
