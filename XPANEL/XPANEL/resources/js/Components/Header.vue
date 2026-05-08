<script setup>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import {
    Bars3Icon,
    ArrowLeftOnRectangleIcon,
    XMarkIcon,
    MoonIcon,
    SunIcon,
} from "@heroicons/vue/24/outline";
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, onMounted, reactive, inject } from "vue";
import ShopCart from "@/Components/ShopCart.vue";
import xLenguage from "@/Components/xLenguage.vue";

const props = defineProps({
    user: Object,
});
const config = usePage().props.xpanel;
const user = reactive({
    email: "roarc@example.com",
    imageUrl:
        "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
});

const lang = ref(localStorage.getItem("lang") || config.default_lang);

function logout() {
    router.post("/logout");
}

const navigation = reactive([
    {
        name_US: "Home",
        name_PT: "Início",
        name_ES: "Inicio",
        name_FR: "Accueil",
        href: route("home"),
        current: false,
        isLogged: false,
    },
    {
        name_US: "Information",
        name_PT: "Informações",
        name_ES: "Información",
        name_FR: "Information",
        isPopover: true,
        isLogged: false,
        current: false,
        children: [
            {
                name_US: "📰 Wiki",
                name_PT: "📰 Wiki",
                name_ES: "📰 Wiki",
                name_FR: "📰 Wiki",
                href: route("wiki.index"),
            },
            {
                name_US: "🏪 Who Sell",
                name_PT: "🏪 Lojas de Venda",
                name_ES: "🏪 Quien Vende",
                name_FR: "🏪 Qui vend",
                href: route("information.whosell"),
            },
            {
                name_US: "🃏 MvP Cards",
                name_PT: "🃏 Cartas MvP",
                name_ES: "🃏 Cartas MvP",
                name_FR: "🃏 Cartes MvP",
                href: route("information.mvp_cards"),
            },
            {
                name_US: "🔎 Item DB",
                name_PT: "🔎 Item DB",
                name_ES: "🔎 Item DB",
                name_FR: "🔎 Item DB",
                href: route("information.item_db"),
            },
//            {
//                name_US: "👻 Monster DB",
//                name_PT: "👻 Monster DB",
//                name_ES: "👻 Monster DB",
//                name_FR: "👻 Monster DB",
//                href: route("information.mvp_cards"),
//            },
        ],
    },
    {
        name_US: "Downloads",
        name_PT: "Downloads",
        name_ES: "Descargas",
        name_FR: "Téléchargements",
        href: route("downloads"),
        current: false,
        isLogged: false,
    },
    {
        name_US: "Rankings",
        name_PT: "Rankings",
        name_ES: "Rankings",
        name_FR: "Rankings",
        isPopover: true,
        isLogged: false,
        current: false,
        children: [
            {
                name_US: "🎖️ Battlegrounds",
                name_PT: "🎖️ Batalhas Campais",
                name_ES: "🎖️ Battlegrounds",
                name_FR: "🎖️ Battlegrounds",
                href: route("rank.bg"),
            },
            {
                name_US: "👑 Guild vs Guild",
                name_PT: "👑 Guilda vs Guilda",
                name_ES: "👑 Gremio vs Gremio",
                name_FR: "👑 Guilde contre Guilde",
                href: route("rank.woe-damage"),
            },
            {
                name_US: "🏰 War of Emperium",
                name_PT: "🏰 Guerra do Emperium",
                name_ES: "🏰 Guerra del Emperium",
                name_FR: "🏰 Guerre de l'Emperium",
                href: route("rank.woe"),
            },
            {
                name_US: "🐲 MvP",
                name_PT: "🐲 MvP",
                name_ES: "🐲 MvP",
                name_FR: "🐲 MvP",
                href: route("rank.mvps"),
            },
            {
                name_US: "🥊 PvP",
                name_PT: "🥊 PvP",
                name_ES: "🥊 PvP",
                name_FR: "🥊 PvP",
                href: route("rank.pvp"),
            },
            {
                name_US: "💵 Cash",
                name_PT: "💵 Cash",
                name_ES: "💵 Cash",
                name_FR: "💵 Cash",
                href: route("rank.cashpoints"),
            },
            {
                name_US: "💰 Zeny",
                name_PT: "💰 Zeny",
                name_ES: "💰 Zeny",
                name_FR: "💰 Zeny",
                href: route("rank.zeny"),
            },
            {
                name_US: "⌚ Playtime",
                name_PT: "⌚ Tempo de Jogo",
                name_ES: "⌚ Tiempo de Juego",
                name_FR: "⌚ Temps de jeu",
                href: route("rank.playtime"),
            },
            {
                name_US: "🏁 Level",
                name_PT: "🏁 Nivel",
                name_ES: "🏁 Nivel",
                name_FR: "🏁 Niveaux",
                href: route("rank.leveling"),
            },
        ],
    },
    {
        name_US: "Donations",
        name_PT: "Doações",
        name_ES: "Donaciones",
        name_FR: "Dons",
        href: route("donations.index"),
        current: false,
        isLogged: false,
    },
    {
        name_US: "Transfer",
        name_PT: "Transferir",
        name_ES: "Transferir",
        name_FR: "Transfert",
        href: route("transfer.index"),
        current: false,
        isLogged: true,
    },
    {
        name_US: "Vote 4 Points",
        name_PT: "Votar por Pontos",
        name_ES: "Votar por Puntos",
        name_FR: "Vote pour Points",
        href: route("vote4points"),
        current: false,
        isLogged: true,
    },
]);
const userNavigation = [
    {
        name_US: "Admin Panel",
        name_PT: "Painel de Administração",
        name_ES: "Panel de Administración",
        name_FR: "Panneau d'administration",
        href: route("admin.dashboard"),
        isAdmin: true,
    },
    {
        name_US: "Master Account",
        name_PT: "Conta Mestre",
        name_ES: "Cuenta Maestra",
        name_FR: "Compte Maître",
        href: route("master-account"),
        isAdmin: false,
    },
    {
        name_US: "Profile",
        name_PT: "Perfil",
        name_ES: "Perfil",
        name_FR: "Profil",
        href: route("profile.show"),
        isAdmin: false,
    },
];

let currentSection = window.location.pathname.split("/")[1] || "home";

function getLoggedOptions() {
    const userInfo = props.user || {};

    const updatedNavigation = navigation.filter((item) => {
        if (Object.keys(userInfo).length > 0) {
            return true;
        } else {
            return item.isLogged === false;
        }
    });

    navigation.splice(0, navigation.length, ...updatedNavigation);
}

function getAdminOptions() {
    const userInfo = props.user || {};

    const updatedNavigation = userNavigation.filter((item) => {
        if (userInfo.role === "Admin") {
            return item;
        } else {
            return item.isAdmin === false;
        }
    });

    userNavigation.splice(0, userNavigation.length, ...updatedNavigation);
}

function getUserProfile() {
    const userInfo = props.user || {};
    getAdminOptions();
    getLoggedOptions();

    if (userInfo) {
        user.email = userInfo.email;
    }
}

onMounted(() => {
    lang.value = localStorage.getItem("lang");
    getUserProfile();
    navigation.forEach((item) => {
        if (item["name_" + lang.value].toLowerCase() === currentSection) {
            item.current = true;
        }
    });
});
const toggleDarkMode = inject("toggleDarkMode");
const isDarkMode = inject("isDarkMode");
</script>
<template>
    <Disclosure
        as="header"
        class="bg-white shadow fixed w-full z-30 dark:bg-gray-900 dark:text-white"
        v-slot="{ open }"
    >
        <div
            class="mx-auto max-w-7xl px-2 sm:px-4 lg:divide-y lg:divide-gray-200 lg:px-8"
        >
            <div class="relative flex h-16 justify-between">
                <div class="relative z-10 flex px-2 lg:px-0">
                    <div class="relative z-10 flex px-2 lg:px-0">
                        <Link :href="route('home')" class="flex items-center">
                            <img
                                src="/img/rov.png"
                                alt="Logo"
                                class="w-8 mr-2"
                            />
                            <!-- Ajusta la ruta de la imagen y el tamaño según tus necesidades -->
                            <h1
                                class="animate-text bg-gradient-to-r from-sky-500 via-purple-500 to-blue-500 bg-clip-text text-transparent text-2xl font-black uppercase"
                            >
                                xPanel
                            </h1>
                        </Link>
                    </div>
                </div>

                <div class="relative z-10 flex items-center lg:hidden">
                    <div class="mr-4">
                        <button
                            @click="toggleDarkMode"
                            type="button"
                            class="relative border flex-shrink-0 rounded-lg p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-gray-900"
                            :class="
                                isDarkMode
                                    ? 'border-gray-700'
                                    : 'border-gray-400'
                            "
                        >
                            <MoonIcon
                                v-if="!isDarkMode"
                                class="h-6 w-6 text-black"
                                aria-hidden="true"
                            />
                            <SunIcon
                                v-else
                                class="h-6 w-6 text-yellow-500"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                    <!-- Mobile menu button -->
                    <DisclosureButton
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                    >
                        <span class="absolute -inset-0.5" />
                        <span class="sr-only">Open menu</span>
                        <Bars3Icon
                            v-if="!open"
                            class="block h-6 w-6"
                            aria-hidden="true"
                        />
                        <XMarkIcon
                            v-else
                            class="block h-6 w-6"
                            aria-hidden="true"
                        />
                    </DisclosureButton>
                </div>
                <div
                    v-if="$page.props.user === null"
                    class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center"
                >
                    <div class="mr-4">
                        <button
                            @click="toggleDarkMode"
                            type="button"
                            class="relative border flex-shrink-0 rounded-lg p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-gray-900"
                            :class="
                                isDarkMode
                                    ? 'border-gray-700'
                                    : 'border-gray-400'
                            "
                        >
                            <MoonIcon
                                v-if="!isDarkMode"
                                class="h-6 w-6 text-black"
                                aria-hidden="true"
                            />
                            <SunIcon
                                v-else
                                class="h-6 w-6 text-yellow-500"
                                aria-hidden="true"
                            />
                        </button>
                    </div>

                    <xLenguage v-show="config.langtype_selector" />
                    <Link
                        :href="route('register')"
                        class="rounded-lg px-2 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:text-sky-500 dark:text-white"
                    >
                        <template v-if="lang === 'US'">Register</template>
                        <template v-if="lang === 'PT'">Registrar</template>
                        <template v-if="lang === 'ES'">Registrarse</template>
                        <template v-if="lang === 'FR'">S'inscrire</template>
                    </Link>
                    <span> | </span>
                    <Link
                        :href="route('login')"
                        class="rounded-lg px-2 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:text-sky-500 dark:text-white"
                    >
                        <template v-if="lang === 'US'">Login</template>
                        <template v-if="lang === 'PT'">Entrar</template>
                        <template v-if="lang === 'ES'">Iniciar sesión</template>
                        <template v-if="lang === 'FR'">S'identifier</template>
                        <span aria-hidden="true">&rarr;</span></Link
                    >
                </div>

                <div
                    v-else
                    class="hidden lg:relative lg:z-10 lg:ml-4 lg:flex lg:items-center"
                >
                    <div class="mr-4">
                        <button
                            @click="toggleDarkMode"
                            type="button"
                            class="relative border flex-shrink-0 rounded-lg p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-gray-900"
                            :class="
                                isDarkMode
                                    ? 'border-gray-700'
                                    : 'border-gray-400'
                            "
                        >
                            <span class="absolute -inset-1.5" />
                            <MoonIcon
                                v-if="!isDarkMode"
                                class="h-6 w-6 text-black"
                                aria-hidden="true"
                            />
                            <SunIcon
                                v-else
                                class="h-6 w-6 text-yellow-500"
                                aria-hidden="true"
                            />
                        </button>
                    </div>

                    <xLenguage v-show="config.langtype_selector" />
                    <button
                        @click="logout"
                        type="button"
                        class="relative flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-gray-900"
                    >
                        <span class="absolute -inset-1.5" />
                        <ArrowLeftOnRectangleIcon
                            class="h-6 w-6 text-red-500"
                            aria-hidden="true"
                        />
                    </button>

                    <!-- Profile dropdown -->
                    <Menu as="div" class="relative ml-4 flex-shrink-0">
                        <div>
                            <MenuButton
                                class="relative space-x-2 flex rounded-full bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold dark:bg-gray-900"
                            >
                                <span class="absolute -inset-1.5" />
                                <span class="sr-only" v-if="lang === 'US'"
                                    >Open user menu</span
                                >
                                <span class="sr-only" v-if="lang === 'PT'"
                                    >Abrir menu de usuário</span
                                >
                                <span class="sr-only" v-if="lang === 'ES'"
                                    >Abrir menú de usuario</span
                                >
                                <span class="sr-only" v-if="lang === 'FR'"
                                    >Ouvrir le menu utilisateur</span
                                >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="w-6 h-6 rounded-full text-gray-400"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                        clip-rule="evenodd"
                                    />
                                </svg>

                                <p
                                    class="flex space-x-1 items-center text-gray-900 dark:text-white"
                                >
                                    {{ user.email }}
                                    <ChevronDownIcon
                                        class="h-4 w-4 flex"
                                        aria-hidden="true"
                                    />
                                </p>
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
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-900 dark:text-white"
                            >
                                <MenuItem
                                    v-for="item in userNavigation"
                                    :key="item['name_' + lang]"
                                    v-slot="{ active }"
                                >
                                    <Link
                                        :href="item.href"
                                        :class="[
                                            active ? 'bg-gray-100' : '',
                                            'block px-4 py-2 text-sm text-gray-700 dark:text-white dark:hover:bg-gray-700',
                                        ]"
                                        >{{ item["name_" + lang] }}</Link
                                    >
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
            <nav
                class="hidden lg:flex lg:space-x-8 lg:py-2"
                aria-label="Global"
            >
                <template
                    v-for="item in navigation"
                    :key="item['name_' + lang]"
                >
                    <Link
                        :href="item.href"
                        v-if="!item.isPopover"
                        :class="[
                            item.current
                                ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:hover:bg-gray-800 dark:hover:text-white',
                            'inline-flex items-center rounded-md py-2 px-3 text-sm font-medium',
                        ]"
                        :aria-current="item.current ? 'page' : undefined"
                        >{{ item["name_" + lang] }}</Link
                    >

                    <Popover class="relative" v-else-if="item.isPopover">
                        <PopoverButton
                            class="py-2 pl-3 inline-flex items-center gap-x-1 text-sm leading-6 text-gray-900 dark:text-white"
                        >
                            <span>{{ item["name_" + lang] }}</span>
                            <ChevronDownIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </PopoverButton>

                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-1"
                        >
                            <PopoverPanel
                                class="absolute left-1/2 z-10 mt-5 flex w-screen max-w-max -translate-x-1/2 px-4"
                            >
                                <div
                                    class="w-52 w-screen max-w-sm flex-auto rounded-3xl bg-white p-4 text-sm leading-6 shadow-lg ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-gray-900 dark:text-white"
                                >
                                    <div
                                        v-for="popover in item.children"
                                        :key="item['name_' + lang]"
                                        class="relative rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700"
                                    >
                                        <Link
                                            :href="popover.href"
                                            class="font-semibold text-gray-900 dark:text-white"
                                        >
                                            {{ popover["name_" + lang] }}
                                            <span class="absolute inset-0" />
                                        </Link>
                                        <p class="mt-1 text-gray-600">
                                            {{ popover.description }}
                                        </p>
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </template>
                <div
                    class="z-0 flex flex-1 items-center justify-end sm:inset-0"
                >
                    <div
                        class="w-full sm:max-w-xs text-gray-900 text-sm text-center flex space-x-2 justify-end dark:text-white"
                    >
                        <p>
                            <template v-if="lang === 'US'">
                                Online Players:
                            </template>
                            <template v-if="lang === 'PT'">
                                Jogadores Online:
                            </template>
                            <template v-if="lang === 'ES'">
                                Jugadores en línea:
                            </template>
                            <template v-if="lang === 'FR'">
                                Joueurs en ligne:
                            </template>
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.users
                            }}</span>
                        </p>
                        <span class="text-gray-400">|</span>
                        <p>
                            <template v-if="lang === 'US'">
                                Peak Online:
                            </template>
                            <template v-if="lang === 'PT'">
                                Pico Online:
                            </template>
                            <template v-if="lang === 'ES'">
                                Pico en línea:
                            </template>
                            <template v-if="lang === 'FR'">
                                Sommet en ligne:
                            </template>
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.peak
                            }}</span>
                        </p>
                    </div>
                    <ShopCart
                        v-if="
                            $page.props.user &&
                            config.donations.shop_type !== 'points'
                        "
                    />
                </div>
            </nav>
        </div>

        <DisclosurePanel as="nav" class="lg:hidden" aria-label="Global">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <template
                    v-for="item in navigation"
                    :key="item['name_' + lang]"
                >
                    <DisclosureButton
                        as="a"
                        v-if="!item.isPopover"
                        :href="item.href"
                        :class="[
                            item.current
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-900 hover:bg-gray-50 hover:text-gray-900 dark:text-white dark:hover:bg-gray-800 dark:hover:text-white',
                            'block rounded-md py-2 px-3 text-base font-medium',
                        ]"
                        :aria-current="item.current ? 'page' : undefined"
                        >{{ item["name_" + lang] }}</DisclosureButton
                    >
                    <Popover class="relative" v-else-if="item.isPopover">
                        <PopoverButton
                            class="py-2 pl-3 inline-flex items-center gap-x-1 text-base leading-6 text-gray-900 dark:text-white"
                        >
                            <span>{{ item["name_" + lang] }}</span>
                            <ChevronDownIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                            />
                        </PopoverButton>

                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 translate-y-1"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 translate-y-1"
                        >
                            <PopoverPanel
                                class="absolute left-1/2 z-10 mt-5 flex w-screen max-w-max -translate-x-1/2 px-4"
                            >
                                <div
                                    class="w-screen max-w-sm flex-auto rounded-3xl bg-white p-4 text-sm leading-6 shadow-lg ring-1 ring-gray-900/5"
                                >
                                    <div
                                        v-for="popover in item.children"
                                        :key="item['name_' + lang]"
                                        class="relative rounded-lg p-4 hover:bg-gray-50"
                                    >
                                        <Link
                                            :href="popover.href"
                                            class="font-semibold text-gray-900"
                                        >
                                            {{ popover["name_" + lang] }}
                                            <span class="absolute inset-0" />
                                        </Link>
                                        <p class="mt-1 text-gray-600">
                                            {{ popover.description }}
                                        </p>
                                    </div>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </template>
            </div>

            <div v-if="$page.props.user === null" class="">
                <div class="space-y-1 px-2">
                    <Link
                        :href="route('login')"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:text-white"
                        >Login</Link
                    >
                    <Link
                        :href="route('register')"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:text-white"
                        >Register</Link
                    >
                    <div
                        class="w-full sm:max-w-xs text-gray-900 text-sm text-center items-center flex space-x-2 justify-center p-3 dark:text-white"
                    >
                        <p>
                            Online Players:
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.users
                            }}</span>
                        </p>
                        <span class="text-gray-400">|</span>
                        <p>
                            Peak Online:
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.peak
                            }}</span>
                        </p>
                        <xLenguage
                            v-show="config.langtype_selector"
                            class="mb-1"
                        />
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pb-3 pt-4" v-else>
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-6 h-6 rounded-full text-gray-400"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3 flex justify-around">
                        <div class="text-base font-medium text-gray-800">
                            {{ user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ user.email }}
                        </div>
                    </div>

                    <button
                        type="button"
                        class="relative ml-auto flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500"
                    >
                        <button
                            @click="logout"
                            type="button"
                            class="relative flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-500 dark:bg-gray-900"
                        >
                            <span class="absolute -inset-1.5" />
                            <ArrowLeftOnRectangleIcon
                                class="h-6 w-6 text-red-500"
                                aria-hidden="true"
                            />
                        </button>
                    </button>
                </div>
                <div class="mt-3 space-y-1 px-2">
                    <div
                        class="w-full sm:max-w-xs text-gray-900 text-sm text-center flex space-x-2 justify-center items-center p-3"
                    >
                        <p>
                            Online Players:
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.users
                            }}</span>
                        </p>
                        <span class="text-gray-400">|</span>
                        <p>
                            Peak Online:
                            <span class="font-bold text-sky-400">{{
                                $page.props.online.peak
                            }}</span>
                        </p>
                        <xLenguage
                            v-show="config.langtype_selector"
                            class="mb-1"
                        />
                    </div>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>
