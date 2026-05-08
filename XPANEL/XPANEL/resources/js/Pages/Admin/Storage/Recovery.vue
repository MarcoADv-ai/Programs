<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>

<script setup>
import axios from "axios";
import { router, usePage } from '@inertiajs/vue3';
import Pagination from "@/Components/Pagination.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetButton from "@/Jetstream/Button.vue";
import { reactive, computed, ref } from "vue";
import { notify } from "notiwind";
import {
    FunnelIcon,
    BarsArrowUpIcon,
    UsersIcon,
} from "@heroicons/vue/20/solid";

const items_requested = computed(()=>usePage().props.items_requested);

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

function acceptRequest(item){
    axios.post(route("storage-request.accept"), {item_data: item}).then((response) => {
        state.title = "Success";
        state.message = response.data.message;
        state.group = "success";
        notifyMe();
        router.reload();
    }).catch((error) => {
        state.title = "Request failed";
        state.message = error.message;
        state.group = "error";
        notifyMe();
    });
}

function deleteRequest(id){
    axios.delete(route("storage-request.destroy", id)).then((response) => {
        state.title = "Success";
        state.message = response.data.message;
        state.group = "success";
        notifyMe();
        router.reload();
    }).catch((error) => {
        console.log(error);
    });
}

// function gameAccountDestroy(id) {
//     axios
//         .delete(route("admin.game-accounts.destroy", id))
//         .then((response) => {
//             state.title = "Success";
//             state.message = response.data.message;
//             state.group = "success";
//             notifyMe();
//             router.reload();
//         })
//         .catch((error) => {
//             console.log(error);
//         });
// }

// function showAccount(id) {
//     axios
//         .get(route("admin.game_accounts.show", id))
//         .then((response) => {
//             game_account.value = response.data;
//             modal.showAccount = true;
//         })
//         .catch((error) => {
//             console.log(error);
//         });
// }
</script>
<template>
    <div>
        <div class="flex flex-wrap">
            <div class="w-full">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-md rounded bg-white"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex items-center">
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex w-full"
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
                                    placeholder="Search Player"
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
                                        Master ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Item ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Item Name
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Item Type
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Refine
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Quantity
                                    </th>

                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="items in items_requested.data">
                                    
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        {{ items.user.id }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        {{ items.user.email }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <i
                                            class="fas fa-circle text-orange-500 mr-2"
                                        ></i>
                                        {{ items.item_id }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <div
                                            class="flex items-center justify-center"
                                        >
                                            <img
                                                class="mr-3"
                                                :src="`https://static.divine-pride.net/images/items/item/${items.item_id}.png`"
                                            />
                                            {{ items.name_english }} [{{
                                                items.slots ?? "0"
                                            }}]
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            items.type
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">{{
                                            items.refine
                                        }}</span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center"
                                    >
                                        <span class="mr-2">x{{
                                            items.qty
                                        }}</span>
                                    </td>

                                    <td
                                        class="px-5 py-5 bg-white text-sm text-right"
                                    >
                                    <jet-button class="mr-3" @click="acceptRequest(items)">Accept</jet-button>
                                    <jet-danger-button @click="deleteRequest(items.user_id)">Reject</jet-danger-button>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination
                            class="p-3"
                            :links="items_requested.links"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
