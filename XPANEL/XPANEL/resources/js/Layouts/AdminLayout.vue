<script setup>
import { onMounted, ref } from "vue";
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    BanknotesIcon,
    Bars3Icon,
    BellIcon,
    BookOpenIcon,
    Cog6ToothIcon,
    FolderOpenIcon,
    HomeIcon,
    UserGroupIcon,
    UsersIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { ChevronDownIcon, MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import { ArrowDownIcon, ArrowUpIcon } from "@heroicons/vue/20/solid";
import { reactive } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { getFormatNumber } from "@/helpers";
import xAlert from "@/Components/Alert.vue";

const onlineData = usePage().props.online;
const admin = usePage().props.admin;

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});

onMounted(() => {
    setNavigationCurrent(props.title);
});

const setNavigationCurrent = (option) => {
    navigation.forEach((opt) => {
        opt.current = opt.name === option;
    });
};

const navigation = reactive([
    {
        name: "Dashboard",
        href: route("admin.dashboard"),
        icon: HomeIcon,
        current: true,
    },
    {
        name: "Donations Info",
        href: route("admin.donations"),
        icon: BanknotesIcon,
        current: false,
    },
    {
        name: "Game Accounts",
        href: route("admin.game_accounts"),
        icon: UsersIcon,
        current: false,
    },
    {
        name: "Master Accounts",
        href: route("admin.master_accounts"),
        icon: UserGroupIcon,
        current: false,
    },
    {
        name: "Wiki",
        href: route("admin.wiki.index"),
        icon: BookOpenIcon,
        current: false,
    },
]);

// const teams = [
//     { id: 1, name: "Send RODEX", href: "#", initial: "R", current: false },
//     { id: 2, name: "Ban Account", href: "#", initial: "G", current: false },
// ];
const userNavigation = [
    { name: "Your profile", href: route("profile.show") },
    { name: "Sign out" },
];

const sidebarOpen = ref(false);

const stats = reactive([
    {
        name: "Total Donations",
        stat: "$" + getFormatNumber(admin.donationsOfMonth),
        previousStat: "$" + getFormatNumber(admin.totalDonations),
        change: getPercentageChange(
            admin.totalDonations,
            admin.donationsOfMonth
        ),
        changeType: getStatusChange(
            admin.totalDonations,
            admin.donationsOfMonth
        ),
    },
    {
        name: "New Users",
        stat: getFormatNumber(admin.users.news),
        previousStat: getFormatNumber(admin.users.total),
        change: getPercentageChange(admin.users.total, admin.users.news),
        changeType: getStatusChange(admin.users.total, admin.users.news),
    },
    {
        name: "Online",
        stat: getFormatNumber(onlineData.users),
        previousStat: getFormatNumber(onlineData.peak),
        change: getPercentageChange(onlineData.peak, onlineData.users),
        changeType: getStatusChange(onlineData.peak, onlineData.users),
    },
]);

function getPercentageChange(oldNumber, newNumber) {
    return getFormatNumber(
        (((newNumber - oldNumber) / oldNumber) * 100).toFixed(2)
    );
}

function getStatusChange(oldNumber, newNumber) {
    return getPercentageChange(oldNumber, newNumber) > 0
        ? "increase"
        : "decrease";
}
</script>

<template>
    <div>
        <Head :title="props.title" />
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                as="div"
                class="relative z-50 lg:hidden"
                @close="sidebarOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-900/80" />
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel
                            class="relative mr-16 flex w-full max-w-xs flex-1"
                        >
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div
                                    class="absolute left-full top-0 flex w-16 justify-center pt-5"
                                >
                                    <button
                                        type="button"
                                        class="-m-2.5 p-2.5"
                                        @click="sidebarOpen = false"
                                    >
                                        <span class="sr-only"
                                            >Close sidebar</span
                                        >
                                        <XMarkIcon
                                            class="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div
                                class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4 ring-1 ring-white/10"
                            >
                                <div class="flex h-16 shrink-0 items-center">
                                    <h1>{{ $page.props }}</h1>
                                </div>
                                <nav class="flex flex-1 flex-col">
                                    <ul
                                        role="list"
                                        class="flex flex-1 flex-col gap-y-7"
                                    >
                                        <li>
                                            <ul
                                                role="list"
                                                class="-mx-2 space-y-1"
                                            >
                                                <li
                                                    v-for="item in navigation"
                                                    :key="item.name"
                                                >
                                                    <a
                                                        @click="
                                                            setNavigationCurrent(
                                                                item.name
                                                            )
                                                        "
                                                        :class="[
                                                            item.current
                                                                ? 'bg-gray-800 text-white'
                                                                : 'text-gray-400 hover:text-white hover:bg-gray-800',
                                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                                        ]"
                                                    >
                                                        <component
                                                            :is="item.icon"
                                                            class="h-6 w-6 shrink-0"
                                                            aria-hidden="true"
                                                        />
                                                        {{ item.name }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <div
                                                class="text-xs font-semibold leading-6 text-gray-400"
                                            >
                                                Actions
                                            </div>
                                            <ul
                                                role="list"
                                                class="-mx-2 mt-2 space-y-1"
                                            >
                                                <li
                                                    v-for="team in teams"
                                                    :key="team.name"
                                                >
                                                    <Link
                                                        :href="team.href"
                                                        :class="[
                                                            team.current
                                                                ? 'bg-gray-800 text-white'
                                                                : 'text-gray-400 hover:text-white hover:bg-gray-800',
                                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                                        ]"
                                                    >
                                                        <span
                                                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white"
                                                            >{{
                                                                team.initial
                                                            }}</span
                                                        >
                                                        <span
                                                            class="truncate"
                                                            >{{
                                                                team.name
                                                            }}</span
                                                        >
                                                    </Link>
                                                </li>
                                            </ul>
                                        </li>
                                        <!-- <li class="mt-auto">
                                            <a
                                                href="#"
                                                class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white"
                                            >
                                                <Cog6ToothIcon
                                                    class="h-6 w-6 shrink-0"
                                                    aria-hidden="true"
                                                />
                                                Settings
                                            </a>
                                        </li> -->
                                    </ul>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col"
        >
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div
                class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4"
            >
                <div class="flex h-16 shrink-0 items-center mt-3">
                    <img src="/img/main_logo.png" alt="Server Logo" />
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="item in navigation" :key="item.name">
                                    <Link
                                        @click="setNavigationCurrent(item.name)"
                                        :href="item.href"
                                        :class="[
                                            item.current
                                                ? 'bg-gray-800 text-white'
                                                : 'text-gray-400 hover:text-white hover:bg-gray-800',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                        ]"
                                    >
                                        <component
                                            :is="item.icon"
                                            class="h-6 w-6 shrink-0"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div
                                class="text-xs font-semibold leading-6 text-gray-400"
                            >
                                Extra Actions
                            </div>
                            <ul role="list" class="-mx-2 mt-2 space-y-1">
                                <li v-for="team in teams" :key="team.name">
                                    <Link
                                        :href="team.href"
                                        :class="[
                                            team.current
                                                ? 'bg-gray-800 text-white'
                                                : 'text-gray-400 hover:text-white hover:bg-gray-800',
                                            'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold',
                                        ]"
                                    >
                                        <span
                                            class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 text-[0.625rem] font-medium text-gray-400 group-hover:text-white"
                                            >{{ team.initial }}</span
                                        >
                                        <span class="truncate">{{
                                            team.name
                                        }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </li>
                        <li class="mt-auto">
                            <a
                                href="#"
                                class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white"
                            >
                                <Cog6ToothIcon
                                    class="h-6 w-6 shrink-0"
                                    aria-hidden="true"
                                />
                                Settings
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-72 bg-gray-100 h-screen overflow-y-auto">
            <div
                class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8"
            >
                <button
                    type="button"
                    class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>

                <!-- Separator -->
                <div
                    class="h-6 w-px bg-gray-900/10 lg:hidden"
                    aria-hidden="true"
                />

                <div class="flex flex-1 gap-x-4 justify-end lg:gap-x-6">
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <button
                            type="button"
                            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500"
                        >
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Separator -->
                        <div
                            class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10"
                            aria-hidden="true"
                        />

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative">
                            <MenuButton class="-m-1.5 flex items-center">
                                <span class="sr-only">Open user menu</span>
                                <span class="hidden lg:flex lg:items-center">
                                    <span
                                        class="text-sm font-semibold leading-6 text-gray-900"
                                        aria-hidden="true"
                                        >{{ $page.props.user.email }}</span
                                    >
                                    <ChevronDownIcon
                                        class="ml-2 h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </span>
                            </MenuButton>
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                >
                                    <MenuItem
                                        v-for="item in userNavigation"
                                        :key="item.name"
                                        v-slot="{ active }"
                                    >
                                        <template
                                            v-if="item.name != 'Sign out'"
                                        >
                                            <Link
                                                :href="item.href"
                                                :class="[
                                                    active ? 'bg-gray-50' : '',
                                                    'block px-3 py-1 text-sm leading-6 text-gray-900',
                                                ]"
                                                >{{ item.name }}</Link
                                            >
                                        </template>
                                        <template v-else>
                                            <a
                                                class="cursor-pointer"
                                                @click.prevent="
                                                    router.post(route('logout'))
                                                "
                                                :class="[
                                                    active ? 'bg-gray-50' : '',
                                                    'block px-3 py-1 text-sm leading-6 text-gray-900',
                                                ]"
                                                >{{ item.name }}</a
                                            >
                                        </template>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>

            <main class="py-5">
                <div class="px-4 sm:px-6 lg:px-8 space-y-4">
                    <div>
                        <h3
                            class="text-base font-semibold leading-6 text-gray-900"
                        >
                            Server Information
                        </h3>
                        <dl
                            class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-x md:divide-y-0"
                        >
                            <div
                                v-for="item in stats"
                                :key="item.name"
                                class="px-4 py-5 sm:p-6"
                            >
                                <dt class="text-base font-normal text-gray-900">
                                    {{ item.name }}
                                </dt>
                                <dd
                                    class="mt-1 flex items-baseline justify-between md:block lg:flex"
                                >
                                    <div
                                        class="flex items-baseline text-2xl font-semibold text-sky-400"
                                    >
                                        {{ item.stat }}
                                        <span
                                            class="ml-2 text-sm font-medium text-gray-500"
                                            >from {{ item.previousStat }}</span
                                        >
                                    </div>

                                    <div
                                        :class="[
                                            item.changeType === 'increase'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800',
                                            'inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium md:mt-2 lg:mt-0',
                                        ]"
                                    >
                                        <ArrowUpIcon
                                            v-if="
                                                item.changeType === 'increase'
                                            "
                                            class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500"
                                            aria-hidden="true"
                                        />
                                        <ArrowDownIcon
                                            v-else
                                            class="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500"
                                            aria-hidden="true"
                                        />
                                        <span class="sr-only">
                                            {{
                                                item.changeType === "increase"
                                                    ? "Increased"
                                                    : "Decreased"
                                            }}
                                            by
                                        </span>
                                        {{ item.change }}
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <slot> </slot>
                </div>
            </main>
        </div>
        <x-alert />
    </div>
</template>
