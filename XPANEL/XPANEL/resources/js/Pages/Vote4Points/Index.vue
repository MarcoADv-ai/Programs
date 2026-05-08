<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router, usePage } from '@inertiajs/vue3';
import { computed } from "vue";
import { formatDate } from "@/helpers";
import axios from "axios";

const rankingsInfo = usePage().props.rankings_info;
const votePoints = usePage().props.vote_points;
const delayExpire = computed(() => usePage().props.vote_delay);
const date = new Date();
const todayFormat = formatDate(date);
const lang = localStorage.getItem("lang");
function delay_diff(rank_id) {
    const foundDelay = delayExpire.value.find((d) => d.rank_id == rank_id);

    if (foundDelay) {
        const delayExpireDate = new Date(foundDelay.delay_expire);
        const today = new Date(todayFormat);
        const diff = delayExpireDate.getTime() - today.getTime();
        return diff;
    }

    return -1;
}

function is_delayed(rank_id) {
    // Verify if todayFormat is a string or a Date object
    const today =
        typeof todayFormat === "string" ? new Date(todayFormat) : todayFormat;
    // Verify if the delay is expired
    const isDelayed = (d) =>
        d.rank_id === rank_id && new Date(d.delay_expire) > today;

    return delayExpire.value.some(isDelayed);
}

function voteRank(ranking) {
    axios
        .post(route("vote4points.store"), {
            ranking_id: ranking.id,
            ranking_points: ranking.points,
        })
        .then((response) => {
            if (response.status === 200) {
                router.reload();
                window.open(ranking.url, "_blank");
            }
        })
        .catch((error) => {
            console.log(error);
        });
}
</script>

<template>
    <app-layout title="Vote 4 Points">
        <div class="py-12 h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-36">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <h1 class="text-center text-4xl font-bold p-10 text-black dark:text-white">
                        <template v-if="lang == 'US'"
                            >Earn points for voting!</template
                        >
                        <template v-else-if="lang == 'FR'">
                            Gagnez des points pour voter!
                        </template>
                        <template v-else-if="lang == 'ES'">
                            ¡Gana puntos por votar!
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Ganhe pontos por votar!
                        </template>
                    </h1>
                    <div class="flex justify-center mb-4">
                        <img
                            src="https://cdn.discordapp.com/attachments/1075324047463817297/1177799274638090290/banner.gif"
                            alt=""
                        />
                    </div>

                    <p
                        class="italic text-center text-sm text-gray-400 w-1/2 mx-auto"
                    >
                        <template v-if="lang == 'US'"
                            >Supporting the server has its rewards, when you click,
                        you will be redirected to the chosen ranking. If
                        validated, the voting point will be automatically
                        awarded.</template>
                        <template v-else-if="lang == 'FR'">
                            Soutenir le serveur a ses récompenses, lorsque vous
                            cliquez, vous serez redirigé vers le classement
                            choisi. Si validé, le point de vote sera
                            automatiquement attribué.
                        </template>
                        <template v-else-if="lang == 'ES'">
                            Apoyar al servidor tiene sus recompensas, cuando
                            haga clic, será redirigido al ranking elegido. Si se
                            valida, el punto de voto se otorgará
                            automáticamente.
                        </template>
                        <template v-else-if="lang == 'PT'">
                            Apoiar o servidor tem suas recompensas, quando você
                            clica, você será redirecionado para o ranking
                            escolhido. Se validado, o ponto de voto será
                            atribuído automaticamente.
                        </template>
                         <br />
                        <br />
                        <span class="text-lg font-semibold text-black dark:text-white">
                            <template v-if="lang == 'US'"
                                >You currently have </template>
                            <template v-else-if="lang == 'FR'">
                                Vous avez actuellement 
                            </template>
                            <template v-else-if="lang == 'ES'">
                                Actualmente tienes 
                            </template>
                            <template v-else-if="lang == 'PT'">
                                Você atualmente tem 
                            </template>
                            <span class="text-green-600"> {{ votePoints }} </span>
                            <template v-if="lang == 'US'"
                                > points</template>
                            <template v-else-if="lang == 'FR'">
                                 points
                            </template>
                            <template v-else-if="lang == 'ES'">
                                 puntos
                            </template>
                            <template v-else-if="lang == 'PT'">
                                 pontos
                            </template>
                        </span>
                    </p>

                    <div
                        class="my-5 flex animated fadeIn faster flex justify-center items-center outline-none focus:outline-none"
                        v-for="(ranking, index) in rankingsInfo"
                    >
                        <div
                            class="relative inline-block rounded-xl bg-slate-50 p-4 text-black shadow-sm transition-all hover:shadow-md dark:bg-gray-900"
                            :class="{
                                'bg-slate-50': is_delayed(ranking.id),
                            }"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img :src="ranking.img_url" class="mr-2" />
                                    <div class="flex flex-col ml-3">
                                        <div
                                            class="text-lg font-medium leading-none text-black text-bold dark:text-white"
                                        >
                                            {{ ranking.name }}
                                        </div>
                                    </div>
                                </div>
                                <button
                                    v-if="is_delayed(ranking.id)"
                                    disabled
                                    class="flex-no-shrink bg-gray-400 px-5 ml-4 py-2 text-sm shadow-sm font-medium tracking-wider border-2 border-gray-400 text-white rounded-xl"
                                >
                                    Disabled until
                                    <br />
                                    <vue-countdown
                                        :time="delay_diff(ranking.id)"
                                        v-slot="{ days, hours, minutes }"
                                    >
                                        {{ days }}D {{ hours }}:{{ minutes }}
                                    </vue-countdown>
                                </button>
                                <button
                                    v-else
                                    @click="voteRank(ranking)"
                                    class="flex-no-shrink cursor-pointer bg-green-500 hover:bg-cyan-600 px-5 ml-4 py-2 text-sm font-medium tracking-wider text-white rounded-full"
                                >
                                    <template v-if="lang == 'US'"
                                        >Vote Now!</template>
                                    <template v-else-if="lang == 'FR'">
                                        Vote maintenant!
                                    </template>
                                    <template v-else-if="lang == 'ES'">
                                        ¡Vota ahora!
                                    </template>
                                    <template v-else-if="lang == 'PT'">
                                        Vote agora!
                                    </template>
                                        
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
