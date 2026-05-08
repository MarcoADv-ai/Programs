<script>
import GuestLayout from "@/Layouts/GuestLayout.vue";

export default {
    layout: GuestLayout,
};
</script>

<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
const lang = ref(localStorage.getItem("lang"));

const downloads = [
    {
        name: "Full Client (Google Drive)",
        last_update: "17/04/2024",
        size: "4.4GB",
        url: "#",
        type: "drive",
    },
    {
        name: "Full Client (MediaFire)",
        last_update: "17/04/2024",
        size: "4.4GB",
        url: "#",
        type: "mediafire",
    },
    {
        name: "Full Client (Mega)",
        last_update: "17/04/2024",
        size: "4.4GB",
        url: "#",
        type: "mega",
    },
];
function redirect_download(type) {
    const download = downloads.find((download) => download.type === type);
    if (download && download.url) {
        window.open(download.url, "_blank");
    }
}

onMounted(() => {
    lang.value = localStorage.getItem("lang");
});
</script>

<template>
    <Head :title="'Downloads'" />

    <div
        class="antialiased w-full md:w-10/12 h-full text-gray-400 font-inter p-10 mx-auto text-xl"
    >
        <div class="container w-full p-6 mx-auto md:mt-36 text-black">
            <div class="p-8 space-y-6 bg-white rounded-xl dark:bg-gray-800">
                <div class="space-y-2">
                    <h3
                        class="uppercase text-lg font-bold md:text-2xl dark:text-sky-500"
                        v-if="lang == 'US'"
                    >
                        Select one of the following options:
                    </h3>
                    <h3
                        class="uppercase text-lg font-bold md:text-2xl"
                        v-if="lang == 'PT'"
                    >
                        Selecione uma das seguintes opções:
                    </h3>
                    <h3
                        class="uppercase text-lg font-bold md:text-2xl"
                        v-if="lang == 'FR'"
                    >
                        Sélectionnez l'une des options suivantes:
                    </h3>
                    <h3
                        class="uppercase text-lg font-bold md:text-2xl"
                        v-if="lang == 'ES'"
                    >
                        Seleccione una de las siguientes opciones:
                    </h3>
                    <p
                        class="text-xs font-light italic dark:text-white"
                        v-if="lang == 'US'"
                    >
                        Download the full game (recommended), which contains all
                        files needed to play.
                    </p>
                    <p class="text-xs font-light italic" v-if="lang == 'PT'">
                        Baixe o jogo completo (recomendado), que contém todos os
                        arquivos necessários para jogar.
                    </p>
                    <p class="text-xs font-light italic" v-if="lang == 'FR'">
                        Téléchargez le jeu complet (recommandé), qui contient
                        tous les fichiers nécessaires pour jouer.
                    </p>
                    <p class="text-xs font-light italic" v-if="lang == 'ES'">
                        Descargue el juego completo (recomendado), que contiene
                        todos los archivos necesarios para jugar.
                    </p>
                </div>
                <div
                    class="overflow-hidden border border-slate-300 rounded-xl dark:border-gray-900"
                    v-for="(download, index) in downloads"
                    :key="index"
                >
                    <div v-if="index == 0">
                        <img
                            class="h-[270px] w-full object-cover"
                            src="/img/bg1.jpg"
                        />
                    </div>
                    <div
                        class="flex flex-wrap justify-between p-4 bg-gray-100 items-center gap-4 dark:bg-gray-900"
                    >
                        <div class="space-y-2">
                            <h3 class="font-bold lg:text-xl dark:text-white">
                                {{ download.name }}
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <div
                                    class="px-3 py-1 text-xs font-light text-center bg-sky-500 rounded-full md:text-sm text-white"
                                >
                                    <h3 v-if="lang == 'US'">
                                        Last update: {{ download.last_update }}
                                    </h3>
                                    <h3 v-if="lang == 'PT'">
                                        Última atualização:
                                        {{ download.last_update }}
                                    </h3>
                                    <h3 v-if="lang == 'FR'">
                                        Dernière mise à jour:
                                        {{ download.last_update }}
                                    </h3>
                                    <h3 v-if="lang == 'ES'">
                                        Última actualización:
                                        {{ download.last_update }}
                                    </h3>
                                </div>
                                <div
                                    class="px-3 py-1 text-xs font-light text-center bg-sky-500 rounded-full md:text-sm text-white"
                                >
                                    <h3 v-if="lang == 'US'">
                                        Size: {{ download.size }}
                                    </h3>
                                    <h3 v-if="lang == 'PT'">
                                        Tamanho: {{ download.size }}
                                    </h3>
                                    <h3 v-if="lang == 'FR'">
                                        Taille: {{ download.size }}
                                    </h3>
                                    <h3 v-if="lang == 'ES'">
                                        Tamaño: {{ download.size }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <a
                            @click="redirect_download(download.type)"
                            class="cursor-pointer flex items-center gap-2 p-3 transition-all duration-300 bg-green-500 rounded-lg hover:scale-105 hover:bg-green-600"
                        >
                            <h3
                                class="text-base text-white md:text-lg"
                                v-if="lang == 'US'"
                            >
                                Download
                            </h3>
                            <h3
                                class="text-base text-white md:text-lg"
                                v-if="lang == 'PT'"
                            >
                                Baixar
                            </h3>
                            <h3
                                class="text-base text-white md:text-lg"
                                v-if="lang == 'FR'"
                            >
                                Télécharger
                            </h3>
                            <h3
                                class="text-base text-white md:text-lg"
                                v-if="lang == 'ES'"
                            >
                                Descargar
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
