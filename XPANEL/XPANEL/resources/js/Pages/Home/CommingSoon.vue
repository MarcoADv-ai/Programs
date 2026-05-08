<script setup>
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";

const gotoDiscord = () => {
    window.open("https://discord.gg/Bkw7Ps8RaD", "_blank");
};

onMounted(() => {
    loadBitsJS();
});
</script>
<template>
    <Head :title="'Coming Soon'" />
    <div class="grid grid-rows-2 md:grid-rows-1 md:grid-cols-2 md:overflow-hidden">
        <div class="bg-arc text-white relative">
            <div
                class="h-screen text-center md:px-8 xl:px-28 xl:py-14 flex flex-col justify-evenly"
            >
                <img
                    src="/img/arcane-logo.png"
                    alt=""
                    class="mx-auto md:h-1/4 lg:h-1/3 z-10"
                />
                <div class="space-y-10 z-10">
                    <h1 class="font-bold text-5xl">Coming Soon</h1>
                    <p class="text-md font-light">
                        RO:Arc is a new Ragnarök Online Mid Rates. With a
                        revamped concept to improve the User Experience.
                        Adventurers, prepare yourselves for the upcoming server.
                    </p>

                    <button
                        @click="gotoDiscord()"
                        class="bg-amber-500 p-5 hover:bg-white hover:text-black transition duration-400"
                    >
                        Join Our Discord!
                    </button>
                </div>

                <p>© Copyright | RO:Arc 2023</p>
            </div>
        </div>
        <div class="bg-white h-screen md:overflow-y-auto px-10 py-20 xl:px-28 xl:py-14">
            <div class="h-full text-center">
                <h1 class="text-3xl font-semibold uppercase mb-3">
                    Server Information
                </h1>
                <p class="text-lg mb-4">
                    Demo is a free-to-play private Ragnarok Online server that
                    focuses on a classic gameplay experience with quality of
                    life improvements.
                </p>

                <ul class="mb-10 text-lg">
                    <li>
                        <span class="font-bold">Rates:</span> 35x | 35x | 25x
                    </li>
                    <li><span class="font-bold">Max Lv:</span> 99/70</li>
                    <li><span class="font-bold">Card Drop:</span> 0.50%</li>
                    <li>
                        <span class="font-bold">MVP & Mini-Boss Cards:</span>
                        0.01%
                    </li>
                    <li>
                        <span class="font-bold">Server Episode:</span> 12 Satan
                        Morroc + Custom Content
                    </li>
                    <li>
                        <span class="font-bold">Anti-Nodelay Frostdelays:</span>
                        Allow you to skill on the server like if you had 0 ping
                    </li>
                    <li>
                        <span class="font-bold">Datacenter:</span> Singapore
                    </li>
                </ul>

                <h2 class="uppercase font-semibold text-3xl mb-2">
                    Unique Systems
                </h2>
                <p class="text-md mb-4">
                    Demo has a lot of unique mechanics that you can't find in
                    other servers, such as the Demo System, the Demo Quests,
                    and more!
                </p>
                <a
                    class="text-center"
                    href="/img/Battlegrounds.png"
                    itemprop="contentUrl"
                    data-size="600x277"
                    style="height: 360px"
                >
                    <img
                        class="mx-auto rounded-lg hover:bg-red-400 hover:opacity-50 transition duration-400"
                        alt="Hunting Missions"
                        src="/img/Battlegrounds.png"
                    />
                    <p class="mb-8 text-xl font-bold">Improved Battlegrounds</p>
                </a>
                <a
                    class="text-center"
                    href="/img/Hunting.png"
                    itemprop="contentUrl"
                    data-size="600x277"
                    style="height: 360px"
                >
                    <img
                        class="mx-auto rounded-lg hover:bg-red-400 hover:opacity-50 transition duration-400"
                        alt="Hunting Missions"
                        src="/img/Hunting.png"
                    />
                    <p class="mb-8 text-xl font-bold">Hunting Missions</p>
                </a>
                <a
                    class="text-center"
                    href="/img/Petvol.png"
                    itemprop="contentUrl"
                    data-size="600x277"
                    style="height: 360px"
                >
                    <img
                        class="mx-auto rounded-lg hover:bg-red-400 hover:opacity-50 transition duration-400"
                        alt="Improved Battlegrounds"
                        src="/img/Petvol.png"
                    />
                    <p class="mb-8 text-xl font-bold">Pet Evolution</p>
                </a>
                <a
                    class="text-center"
                    href="/img/Costumesuit.png"
                    itemprop="contentUrl"
                    data-size="600x277"
                    style="height: 360px"
                >
                    <img
                        class="mx-auto rounded-lg hover:bg-red-400 hover:opacity-50 transition duration-400"
                        alt="Improved Battlegrounds"
                        src="/img/Costumesuit.png"
                    />
                    <p class="mb-8 text-xl font-bold">Costumes Suit</p>
                </a>
                <h3 class="text-2xl font-semibold mb-3">AND MUCH MORE!</h3>
                <p class="text-lg mb-5">
                    Stay tuned! If you want to know more, please feel free to
                    join our discord server
                </p>
                <a
                    href="https://discord.gg/Bkw7Ps8RaD"
                    target="”_blank”"
                    data-v-3c0d6e26=""
                    ><img
                        src="https://discordapp.com/api/guilds/1117209838435446804/widget.png?style=banner2"
                        class="mx-auto"
                        data-v-3c0d6e26=""
                /></a>
                <p
                    class="text-center py-5 animate-text bg-gradient-to-r from-teal-500 via-purple-500 to-orange-500 bg-clip-text text-transparent"
                >
                    © 2023 - RO:Arc
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-arc {
    background: radial-gradient(ellipse at bottom, #d08cff 0%, #0f090d 100%);
}
</style>
