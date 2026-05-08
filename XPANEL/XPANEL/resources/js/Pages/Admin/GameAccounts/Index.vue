
<script setup>
import axios from "axios";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router, usePage } from '@inertiajs/vue3';
import JetDropdown from "@/Jetstream/Dropdown.vue";
import Pagination from "@/Components/Pagination.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import { reactive, computed, ref, watch } from "vue";
import { notify } from "notiwind";
import {
    UsersIcon,
} from "@heroicons/vue/20/solid";

const game_accounts = computed(() => usePage().props.game_accounts);
const gepard_banned = computed(() => usePage().props.gepard_banned);
const game_account = ref({});
const query_search = ref("");

const modal = reactive({
    showAccount: false,
});

const state = reactive({
    title: "",
    message: "",
    group: "",
    duration: 5000,
});

function notifyMe() {
    notify(
        {
            group: state.group,
            title: state.title.toUpperCase(),
            text: state.message + " ",
        },
        state.duration
    );
}

function gameAccountDestroy(id) {
    axios
        .delete(route("admin.game_accounts.destroy", id))
        .then((response) => {
            state.title = "Success";
            state.message = response.data.message;
            state.group = "success";
            notifyMe();
            router.reload();
        })
        .catch((error) => {
            console.log(error);
        });
}

function showAccount(id) {
    axios
        .get(route("admin.game_accounts.show", id))
        .then((response) => {
            game_account.value = response.data.game_account;
            modal.showAccount = true;
        })
        .catch((error) => {
            console.log(error);
        });
}

watch(query_search, (value) => {
    if (value.length > 0) {
        router.get(route("admin.game_accounts"),{ q: value}, { preserveState: true })
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            })
    } else {
        router.reload();
    }
});

</script>
<template>
    <AdminLayout :title="'Game Accounts'">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full  shadow-md rounded bg-white px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex items-center">
                            <div
                                class="relative w-full  max-w-full flex-grow flex w-full"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Game Accounts
                                </h3>
                            </div>
                            <div
                                class="relative flex flex-grow items-stretch focus-within:z-10 w-1/2 sm:w-auto sm:ml-auto sm:mr-0 mx-auto"
                            >
                                <div
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                                >
                                    <UsersIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <input
                                    type="email"
                                    v-model="query_search"
                                    name="email"
                                    id="email"
                                    class="block w-full rounded-none rounded-l-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-600 sm:text-sm sm:leading-6"
                                    placeholder="Search Account"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <table
                            class="items-center w-full bg-transparent border-collapse"
                        >
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Account ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        User ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Group ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        State
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Last Login
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Last IP
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Vip Time
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Master ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Last UID
                                    </th>
                                    <th
                                        class=" px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="game_account in game_accounts.data">
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center flex items-center"
                                    >
                                        <span
                                            class="ml-3 font-bold text-blueGray-600"
                                        >
                                            {{ game_account.account_id }}</span
                                        >
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        {{ game_account.userid }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <i
                                            class="fas fa-circle text-orange-500 mr-2"
                                        ></i>
                                        {{ game_account.email }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.group_id
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span
                                            v-if="game_account.state === 0"
                                            class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-1.5 py-0.5 text-xs font-medium text-green-700"
                                        >
                                            <svg
                                                class="h-1.5 w-1.5 fill-green-500"
                                                viewBox="0 0 6 6"
                                                aria-hidden="true"
                                            >
                                                <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Active
                                        </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center gap-x-1.5 rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700"
                                        >
                                            <svg
                                                class="h-1.5 w-1.5 fill-red-500"
                                                viewBox="0 0 6 6"
                                                aria-hidden="true"
                                            >
                                                <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Banned
                                        </span>
                                    </td>
                                    
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.lastlogin
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.last_ip
                                        }}</span>
                                    </td>
                                    
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.vip_time
                                        }}</span>
                                    </td>
                                    
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.master_acc_id
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            game_account.last_unique_id
                                        }}</span>
                                    </td>

                                    <td
                                        class="px-5 py-5 bg-white text-sm text-right"
                                    >
                                        <jet-dropdown>
                                            <template #trigger>
                                                <button
                                                    type="button"
                                                    class="inline-block hover:text-gray-700 z-10"
                                                >
                                                    <svg
                                                        class="inline-block h-6 w-6 fill-current"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                        />
                                                    </svg>
                                                </button>
                                            </template>

                                            <template #content>
                                                <!-- Dropdown items -->
                                                <div
                                                    class="py-1"
                                                    role="menu"
                                                    aria-orientation="vertical"
                                                    aria-labelledby="options-menu"
                                                >
                                                    <JetDropdownLink
                                                        :as="'button'"
                                                        @click="
                                                            showAccount(
                                                                game_account.account_id
                                                            )
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="edit"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Edit
                                                    </JetDropdownLink>
                                                    <JetDropdownLink
                                                        :as="'button'"
                                                        @click="
                                                            gameAccountDestroy(
                                                                game_account.account_id
                                                            )
                                                        "
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="trash"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Destroy
                                                    </JetDropdownLink>
                                                </div>
                                            </template>
                                        </jet-dropdown>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination class="p-2" :links="game_accounts.links" />
                    </div>
                </div>
            </div>
        </div>

        <jet-modal-dialog
            :maxWidth="'4xl'"
            :show="modal.showAccount"
            @close="closeAccount"
            class="text-black"
        >
            <template #title>Account: {{ game_account.userid }}</template>
            <template #content>
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <h2
                            class="text-base font-semibold leading-7 text-gray-900"
                        >
                            Current game account information
                        </h2>
                        <p
                            class="mt-1 max-w-2xl text-sm leading-6 text-gray-600"
                        >
                            This information will be changed the information of
                            the game account.
                        </p>

                        <div
                            class="mt-10 space-y-8 border-b border-gray-900/10 pb-12 sm:space-y-0 sm:divide-y sm:divide-gray-900/10 sm:border-t sm:pb-0"
                        >
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Account ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <div class="mt-2 sm:col-span-2 sm:mt-0">
                                        <input
                                            v-model="game_account.account_id"
                                            type="text"
                                            name="first-name"
                                            id="first-name"
                                            autocomplete="given-name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="first-name"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >User ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.userid"
                                        type="text"
                                        name="first-name"
                                        id="first-name"
                                        autocomplete="given-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="last-name"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Password</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.password"
                                        type="text"
                                        name="last-name"
                                        id="last-name"
                                        autocomplete="family-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="email"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Email address</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.email"
                                        type="text"
                                        name="first-name"
                                        id="first-name"
                                        autocomplete="given-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="country"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Group ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.group_id"
                                        type="text"
                                        name="first-name"
                                        id="first-name"
                                        autocomplete="given-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="country"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >State</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <select
                                        v-model="game_account.state"
                                        id="country"
                                        name="country"
                                        autocomplete="country-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    >
                                        <option value="0">Active</option>
                                        <option value="1">Block Account</option>
                                        <option value="2">Ban Account</option>
                                    </select>
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="street-address"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Unban Time</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.unban_time"
                                        type="text"
                                        name="first-name"
                                        id="first-name"
                                        autocomplete="given-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="city"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Expiration Time</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.expiration_time"
                                        type="text"
                                        name="city"
                                        id="city"
                                        autocomplete="address-level2"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="city"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Login Count</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.logincount"
                                        type="text"
                                        name="city"
                                        id="city"
                                        autocomplete="address-level2"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="region"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Last IP</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.last_ip"
                                        type="text"
                                        name="region"
                                        id="region"
                                        autocomplete="address-level1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="region"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Birthdate</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.birthdate"
                                        type="text"
                                        name="region"
                                        id="region"
                                        autocomplete="address-level1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="region"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Character Slots</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.character_slots"
                                        type="text"
                                        name="region"
                                        id="region"
                                        autocomplete="address-level1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Pincode</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.pincode"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Pincode Change</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.pincode_change"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Vip Time</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.vip_time"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Old Group</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.old_group"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Web Auth Token</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.web_auth_token"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Web Auth Token Enabled</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="
                                            game_account.web_auth_token_enabled
                                        "
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Master Account ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.master_acc_id"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Last Unique ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.last_unique_id"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="postal-code"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Blocked Unique ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="game_account.blocked_unique_id"
                                        type="text"
                                        name="postal-code"
                                        id="postal-code"
                                        autocomplete="postal-code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <jet-button class="mr-2">Edit</jet-button>
                <jet-secondary-button
                    @click="modal.showAccount = !modal.showAccount"
                >
                    Close
                </jet-secondary-button>
            </template>
        </jet-modal-dialog>

        <div class="flex flex-wrap">
            <div class="w-full xl:w-8/12 xl:mb-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-md rounded px-3"
                >
                    <div class="rounded-t mb-0 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex-1 px-4 py-3"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Gepard Banned
                                </h3>
                            </div>
                            <table
                                class="items-center w-full bg-transparent border-collapse"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Unique ID
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Unban Time
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Reason
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        ></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="gepard_info in gepard_banned.data"
                                    >
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                        >
                                            <span
                                                class="ml-3 font-bold text-blueGray-600"
                                            >
                                                {{
                                                    gepard_info.unique_id
                                                }}</span
                                            >
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                        >
                                            {{ gepard_info.unban_time }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                        >
                                            {{ gepard_info.reason }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                        >
                                            <jet-dropdown>
                                                <template #trigger>
                                                    <button
                                                        type="button"
                                                        class="inline-block hover:text-gray-700 z-10"
                                                    >
                                                        <svg
                                                            class="inline-block h-6 w-6 fill-current"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M12 6a2 2 0 110-4 2 2 0 010 4zm0 8a2 2 0 110-4 2 2 0 010 4zm-2 6a2 2 0 104 0 2 2 0 00-4 0z"
                                                            />
                                                        </svg>
                                                    </button>
                                                </template>

                                                <template #content>
                                                    <!-- Dropdown items -->
                                                    <div
                                                        class="py-1"
                                                        role="menu"
                                                        aria-orientation="vertical"
                                                        aria-labelledby="options-menu"
                                                    >
                                                        <JetDropdownLink
                                                            :as="'button'"
                                                            @click="
                                                                showDonation(
                                                                    dona.xid
                                                                )
                                                            "
                                                            class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        >
                                                            <font-awesome-icon
                                                                icon="edit"
                                                                class="mr-2"
                                                            ></font-awesome-icon>
                                                            Unban
                                                        </JetDropdownLink>
                                                    </div>
                                                </template>
                                            </jet-dropdown>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <!-- <table class="items-center w-full bg-transparent border-collapse">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                            User </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                            Reason </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                            Unique Id </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                            Ban Finish </th>
                                        <th
                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                            Banned By </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gepard in gepard_banned.data">
                                        <th
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center">
                                            {{ gepard.violator }}</th>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            {{gepard.reason}} </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            {{ gepard.unique_id }} </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <i class="fas fa-arrow-up text-emerald-500 mr-4"></i> {{gepard.unban_time}}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            {{ gepard.initiator }} </td>
                                    </tr>
                                
                                </tbody>
                            </table> -->
                    </div>
                </div>
            </div>
            <div class="w-full xl:w-4/12 pl-4 ">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-md rounded h-full px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-b border-b-1">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex-1"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Top Logins per day
                                </h3>
                            </div>
                            
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <div class="h-[20rem]"></div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
