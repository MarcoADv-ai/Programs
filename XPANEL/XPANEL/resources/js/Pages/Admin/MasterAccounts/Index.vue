
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router, usePage } from '@inertiajs/vue3';
import axios from "axios";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import Pagination from "@/Components/Pagination.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import { reactive, computed, ref, watch } from "vue";
import { notify } from "notiwind";
import {
    FunnelIcon,
    BarsArrowUpIcon,
    UsersIcon,
} from "@heroicons/vue/20/solid";

const master_accounts = computed(() => usePage().props.master_accounts);
const master_account = ref({});
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

function masterAccountDestroy(id) {
    axios
        .delete(route("admin.master_accounts.destroy", id))
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

function editMasterAccount(id) {
    axios.put(route("admin.master_accounts.update", id), master_account.value)
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
        .get(route("admin.master_accounts.show", id))
        .then((response) => {
            master_account.value = response.data.master_account;
            modal.showAccount = true;
        })
        .catch((error) => {
            console.log(error);
        });
}

watch(query_search, (value) => {
    if (value.length > 0) {
        router.get(
            route("admin.master_accounts"),
            { q: value },
            { preserveState: true }
        )
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
    } else {
        router.reload();
    }
});
</script>
<template>
    <AdminLayout :title="'Master Accounts'">
        <div class="flex flex-wrap">
            <div class="w-full">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-md rounded bg-white px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex w-full"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Master Accounts
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
                                        ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Country
                                    </th>

                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Donation Points
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Vote Points
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Role
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Status
                                    </th>
                                   

                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="master_account in master_accounts.data"
                                >
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center flex items-center"
                                    >
                                        <span
                                            class="ml-3 font-bold text-blueGray-600"
                                        >
                                            {{ master_account.id }}</span
                                        >
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        {{ master_account.email }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <i
                                            class="fas fa-circle text-orange-500 mr-2"
                                        ></i>
                                        {{ master_account.country }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            master_account.donation_points
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            master_account.vote_points
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            master_account.role
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span
                                            v-if="master_account.status === 1"
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
                                                                master_account.id
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
                                                            masterAccountDestroy(
                                                                master_account.id
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
                        <pagination
                            class="p-2"
                            :links="master_accounts.links"
                        />
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
            <template #title>Account: {{ master_account.email }}</template>
            <template #content>
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <h2
                            class="text-base font-semibold leading-7 text-gray-900"
                        >
                            Current master account information
                        </h2>
                        <p
                            class="mt-1 max-w-2xl text-sm leading-6 text-gray-600"
                        >
                            This information will be changed the information of
                            the master account.
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
                                    >ID</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <div class="mt-2 sm:col-span-2 sm:mt-0">
                                        <input
                                            v-model="master_account.id"
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
                                    >Email</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="master_account.email"
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
                                    >Country</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="master_account.country"
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
                                    for="street-address"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Donation Points</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="master_account.donation_points"
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
                                    >Vote Points</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <input
                                        v-model="master_account.vote_points"
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
                                    for="country"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Role</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <select
                                        v-model="master_account.role"
                                        id="country"
                                        name="country"
                                        autocomplete="country-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    >
                                        <option value="User">User</option>
                                        <option value="Admin">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:py-6"
                            >
                                <label
                                    for="country"
                                    class="block text-sm font-medium leading-6 text-gray-900 sm:pt-1.5"
                                    >Status</label
                                >
                                <div class="mt-2 sm:col-span-2 sm:mt-0">
                                    <select
                                        v-model="master_account.status"
                                        id="country"
                                        name="country"
                                        autocomplete="country-name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                    >
                                        <option value="1">Active</option>
                                        <option value="0">Banned</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <jet-button class="mr-2" @click="editMasterAccount(master_account.id)">Edit</jet-button>
                <jet-secondary-button
                    @click="modal.showAccount = !modal.showAccount"
                >
                    Close
                </jet-secondary-button>
            </template>
        </jet-modal-dialog>
    </AdminLayout>
</template>
