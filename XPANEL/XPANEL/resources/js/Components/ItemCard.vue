<script setup>
import { ref } from "vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});
const open = ref(false);
</script>

<template>
    <div
    class="w-full max-w-xs relative"
    @mouseenter="open = true"
    @mouseleave="open = false"
>
    <div
        class="group inline-flex items-center rounded-md px-3 py-2 text-base font-medium hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
    >
        <img
            :src="`https://static.divine-pride.net/images/items/item/${props.item.id}.png`"
            class="relative"
            alt=""
        />
    </div>
    <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="translate-y-1 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-1 opacity-0"
    >
        <div v-if="open" class="absolute z-10 mt-3 transform px-4 sm:px-0">
            <div class="shadow-lg ring-1 ring-black/5">
                <div class="bg-gray-50 p-2 w-full rounded-md shadow-md max-w-sm">
                    <div
                        class="flex bg-gray-100 space-x-5 rounded-lg px-2 py-2 transition duration-150 ease-in-out focus:outline-none focus-visible:ring focus-visible:ring-orange-500/50"
                    >
                        <div class="flex-shrink-0">
                            <img
                                :src="
                                    'https://divine-pride.net/img/items/collection/iRO/' +
                                    item.id
                                "
                                alt="item image"
                            />
                        </div>
                        <div class="min-w-0 w">
                            <div class="text-sm font-medium text-gray-900 font-bold break-words">
                                {{ item.name_english }}
                            </div>
                            <div class="text-sm text-gray-500 whitespace-normal break-words">
                                <div class="flex flex-wrap">
                                    {{ item.description[0] ?? "" }}
                                </div>
                                <div>{{ item.description[1] ?? "" }}</div>
                                <div>{{ item.description[2] ?? "" }}</div>
                            </div>
                            <div class="text-black">
                                --------------------------------------------
                            </div>
                            <div class="text-black">
                                <ul>
                                    <li>Class: {{ item.type }}</li>
                                    <li>Defense: {{ item.defense ?? 0 }}</li>
                                    <li>Weight: {{ item.weight }}</li>
                                    <li>Requires Level: {{ item.equip_level_min ?? "0" }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</div>

</template>
