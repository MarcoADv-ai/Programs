<template>
    <div
        class="relative rounded-xl bg-white p-4 text-black shadow-sm transition-all hover:shadow-md text-center dark:bg-gray-800 dark:text-white"
    >
        <span
            v-if="char_account.online != 1"
            class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1"
        >
            <span
                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-red-500"
            ></span
            ><span
                class="relative inline-flex rounded-full h-3 w-3 bg-red-500"
            ></span
        ></span>
        <span v-else class="flex absolute h-3 w-3 top-0 right-0 -mt-1 -mr-1">
            <span
                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-green-500"
            ></span
            ><span
                class="relative inline-flex rounded-full h-3 w-3 bg-green-500"
            ></span
        ></span>
        <div class="block overflow-ellipsis overflow-hidden">
            <strong>{{ char_account.name }}</strong>
        </div>
        <div class="block text-xs">
            <template v-if="lang == 'US'">Class: </template>
            <template v-else-if="lang == 'FR'">Classe: </template>
            <template v-else-if="lang == 'ES'">Clase: </template>
            <template v-else-if="lang == 'PT'">Classe: </template>
            <strong>{{ get_job_name(char_account.class) }}</strong>
        </div>
        <div class="block text-xs">
            <template v-if="lang == 'US'">Level: </template>
            <template v-else-if="lang == 'FR'">Niveau: </template>
            <template v-else-if="lang == 'ES'">Nivel: </template>
            <template v-else-if="lang == 'PT'">Nível: </template>
            <strong
                >{{ char_account.base_level }}/{{
                    char_account.job_level
                }}</strong
            >
        </div>
        <div class="block text-xs">
            <template v-if="lang == 'US'">Map: </template>
            <template v-else-if="lang == 'FR'">Carte: </template>
            <template v-else-if="lang == 'ES'">Mapa: </template>
            <template v-else-if="lang == 'PT'">Mapa: </template>
            <strong
                >{{ char_account.last_map }},{{ char_account.last_x }},{{
                    char_account.last_y
                }}</strong
            >
        </div>
        <div
            class="flex flex-col items-center justify-center mt-3 text-sm rounded-b"
        >
            <jet-button
                @click="reset('position', char_account.char_id)"
                class="rounded-xl justify-center w-1/2 mb-1 bg-black hover:bg-sky-400 dark:bg-gray-700 dark:hover:bg-gray-600"
                :disabled="char_account.online == 1"
            >
                <template v-if="lang == 'US'">Reset Position</template>
                <template v-else-if="lang == 'FR'"
                    >Réinitialiser la position</template
                >
                <template v-else-if="lang == 'ES'"
                    >Restablecer posición</template
                >
                <template v-else-if="lang == 'PT'">Redefinir posição</template>
            </jet-button>
            <jet-button
                @click="reset('equipment', char_account.char_id)"
                class="rounded-xl justify-center w-1/2 mb-1 bg-black hover:bg-sky-400 dark:bg-gray-700 dark:hover:bg-gray-600"
                :disabled="char_account.online == 1"
            >
                <template v-if="lang == 'US'">Reset Equipment</template>
                <template v-else-if="lang == 'FR'"
                    >Réinitialiser l'équipement</template
                >
                <template v-else-if="lang == 'ES'">Restablecer equipo</template>
                <template v-else-if="lang == 'PT'"
                    >Redefinir equipamento</template
                >
            </jet-button>
            <jet-button
                @click="reset('appearence', char_account.char_id)"
                class="rounded-xl justify-center w-1/2 bg-black hover:bg-sky-400 dark:bg-gray-700 dark:hover:bg-gray-600"
                :disabled="char_account.online == 1"
            >
                <template v-if="lang == 'US'">Reset Appearence</template>
                <template v-else-if="lang == 'FR'"
                    >Réinitialiser l'apparence</template
                >
                <template v-else-if="lang == 'ES'"
                    >Restablecer apariencia</template
                >
                <template v-else-if="lang == 'PT'"
                    >Redefinir aparência</template
                >
            </jet-button>
        </div>
    </div>
</template>
<script>
import JetButton from "@/Jetstream/Button.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import { reactive } from "vue";
import { get_job_name } from "@/helpers";
import { router, Link } from "@inertiajs/vue3";

export default {
    components: {
        JetButton,
        JetDangerButton,
        Link,
    },
    props: {
        char_account: Object,
    },

    setup() {
        const form = reactive({
            resetType: "",
        });
        const lang = localStorage.getItem("lang");
        function reset(resetType, char_id) {
            if (
                confirm(
                    "Are you sure you want to reset " +
                        resetType +
                        " this character?"
                )
            ) {
                form.resetType = resetType;
                router.post(`game-accounts/reset/${char_id}`, form);
            }
        }
        return {
            reset,
            get_job_name,
            JetButton,
            JetDangerButton,
            lang,
        };
    },
};
</script>

<style scoped>
.bg_black {
    background-color: #171717;
}

.bg_gray {
    color: #444444;
}

.white_text {
    color: #ededed;
}
</style>
