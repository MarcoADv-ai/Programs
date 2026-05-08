<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
const config = usePage().props.xpanel;
const langtype = ref(config.default_lang);
const langs_active = [];
function changeLang(lang) {
    if (config.langtype_selector === false) return;

    langtype.value = lang;
    localStorage.setItem("lang", lang);
    location.reload();
}

const langs = {
    US: "English",
    PT: "Português",
    ES: "Español",
    FR: "Français",
};

function getLangs() {
    config.langs_available.forEach((lang) => {
        langs_active.push({
            lang: lang,
            name: langs[lang],
        });
    });
}

onMounted(() => {
    getLangs()
    let lang = localStorage.getItem("lang");

    if (!lang || config.langtype_selector === false) {
        lang = config.default_lang;
        localStorage.setItem("lang", lang);
    }

    langtype.value = lang;
});
</script>

<template>
    <Menu as="div" class="relative inline-block text-left my-auto mr-5">
        <div class="flex items-center">
            <MenuButton
                class="relative -top-0.5 w-full justify-center gap-x-1.5 rounded-md bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:outline-none"
            >
                <country-flag
                    :country="langtype"
                    :size="normal"
                    :squircle="true"
                />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800"
            >
                <div class="py-1">
                    <MenuItem v-slot="{ active }" v-for="lang in langs_active" :key="lang.lang">
                        <span
                            @click="changeLang(lang.lang)"
                            :class="[
                                active
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'text-gray-700 dark:text-gray-300',
                                'block px-4 py-2 text-sm cursor-pointer flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white',
                            ]"
                        >
                            <country-flag
                                :country="lang.lang"
                                :size="20"
                                :squircle="true"
                            />
                            <span class="ml-2 mt-2">{{ lang.name }}</span>
                        </span>
                    </MenuItem>
                    
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
