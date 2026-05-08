<script setup>
import { computed, onMounted, onUpdated, reactive, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import jetButton from "@/Jetstream/Button.vue";
import { QuestionMarkCircleIcon } from "@heroicons/vue/20/solid";
import { TrashIcon } from "@heroicons/vue/20/solid";
import xAlert from "@/Components/Alert.vue";
import { notify } from "notiwind";

const items_db = computed(() => usePage().props.items);
const error = usePage().props.error;
const item_name = ref("");
const items_structure = ref([]);
const items_requested = ref([]);

const state = reactive({
    title: "",
    message: "",
    group: "",
    duration: 5000,
});

function addItem(item) {
    let refine = item.refine ? item.refine.replace(/[^0-9]/g, "") : 0;
    let quantity = item.quantity ? item.quantity.replace(/[^0-9]/g, "") : 0;

    if (refine == "") {
        item.refine = 0;
    }

    if (quantity == "") {
        item.quantity = 0;
    }

    if (
        items_requested.value.find((i) => i.id === item.id) &&
        item.type != "Armor" &&
        item.type != "Weapon" &&
        item.type != "Petarmor" &&
        item.type != "Petegg"
    ) {
        state.title = "Item already added";
        state.message = "You already added " + item.name + " to the list";
        state.group = "error";
        notifyMe();
        return;
    }

    if (item.refine > 10) {
        state.title = "Invalid refine";
        state.message = "You can't refine more than 10";
        state.group = "error";
        notifyMe();
        return;
    }

    if (item.quantity < 1) {
        state.title = "Invalid quantity";
        state.message = "You can't add less than 1";
        state.group = "error";
        notifyMe();
        return;
    }

    if(item.quantity > 30000)
    {
        state.title = "Invalid quantity";
        state.message = "You can't add more than 30000";
        state.group = "error";
        notifyMe();
        return;
    }

    items_requested.value.push({
        id: item.id,
        name: item.name,
        type: item.type,
        refine: item.refine,
        quantity: item.quantity,
    });

    state.title = "Item added";
    state.message = "You added " + item.name + " to the list";
    state.group = "success";
    notifyMe();
}
function loadItems() {
    items_db.value.data.forEach((item) => {
        items_structure.value.push({
            id: item.id,
            name: item.name_english,
            type: item.type,
            refineable: item.refineable,
            slots: item.slots,
            refine: 0,
            quantity: 0,
        });
    });
}
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
function realRefine(event) {
    console.log(event.target.value);
    if (event.target.value > 10) {
        event.target.value = 10;
    }
}

function sendRequest() {
    if (items_requested.value.length < 1) {
        state.title = "Request failed";
        state.message = "You need to add at least one item to the list";
        state.group = "error";
        notifyMe();
        return;
    }

    axios
        .post(route("storage-request.store"), {
            items: items_requested.value,
        })
        .then((response) => {
            state.title = "Request sent";
            state.message = response.data.message;
            state.group = "success";
            notifyMe();
            items_requested.value = [];
        })
        .catch((error) => {
            state.title = "Request failed";
            state.message = error.response.data.message;
            state.group = "error";
            notifyMe();
        });
}

onMounted(() => {
    loadItems();
});

watch(items_db, (newVal) => {
    items_structure.value = [];
    loadItems();
});

watch(item_name, (newVal) => {
    router.get(
        "/storage-recovery",
        { item_name: newVal },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
});
</script>

<template>
    <div class="bg-gray-50">
        <div
            class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8"
        >
            <h2 class="sr-only">Checkout</h2>

            <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
                <div>
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">
                            Storage Request
                        </h2>

                        <div class="mt-4">
                            <label
                                for="item_name"
                                class="block text-sm font-medium text-gray-700"
                                >Search Item</label
                            >
                            <div class="mt-1">
                                <input
                                    type="text"
                                    id="item_name"
                                    name="item_name"
                                    v-model="item_name"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="sm:px-6">
                        <div class="mt-8 flow-root">
                            <div
                                class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"
                            >
                                <div
                                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-2"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead>
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                >
                                                    Item Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                                                >
                                                    Type
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Refine
                                                </th>

                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Quantity
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr
                                                v-for="item_db in items_structure"
                                                :key="item_db.id"
                                            >
                                                <td
                                                    class="overflow-x-auto py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0"
                                                >
                                                    <img
                                                        :src="`https://static.divine-pride.net/images/items/item/${item_db.id}.png`"
                                                        alt=""
                                                    />
                                                    {{ item_db.name }}
                                                    <span
                                                        v-if="
                                                            item_db.type ===
                                                                'Armor' ||
                                                            item_db.type ===
                                                                'Weapon'
                                                        "
                                                        >[{{
                                                            item_db.slots ?? 0
                                                        }}]</span
                                                    >
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0"
                                                >
                                                    {{ item_db.type }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-400"
                                                >
                                                    <div
                                                        v-if="
                                                            item_db.refineable ===
                                                            1
                                                        "
                                                    >
                                                        <div
                                                            class="relative mt-2 rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                type="text"
                                                                v-model="
                                                                    item_db.refine
                                                                "
                                                                name="account-number"
                                                                id="account-number"
                                                                class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                                            >
                                                                <QuestionMarkCircleIcon
                                                                    class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <xInput
                                                        :type="'number'"
                                                        v-model="item_db.refine"
                                                        class="w-1/2 text-black"
                                                        v-if="
                                                            item_db.refineable ===
                                                            1
                                                        "
                                                    />-->
                                                    <p v-else>No refinable</p>
                                                </td>

                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    <div>
                                                        <div
                                                            class="relative mt-2 rounded-md shadow-sm"
                                                        >
                                                            <input
                                                                type="text"
                                                                v-model="
                                                                    item_db.quantity
                                                                "
                                                                name="account-number"
                                                                id="account-number"
                                                                class="block w-full rounded-md border-0 py-1.5 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                            />
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                                            >
                                                                <QuestionMarkCircleIcon
                                                                    class="h-5 w-5 text-gray-400"
                                                                    aria-hidden="true"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0"
                                                >
                                                    <jet-button
                                                        type="button"
                                                        @click="
                                                            addItem(item_db)
                                                        "
                                                        class="text-white hover:text-indigo-900"
                                                    >
                                                        Add
                                                    </jet-button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="mt-10 lg:mt-0">
                    <h2 class="text-lg font-medium text-gray-900">
                        Items Requested List
                    </h2>

                    <div
                        class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm"
                    >
                        <h3 class="sr-only">Items in your cart</h3>
                        <ul role="list" class="divide-y divide-gray-200">
                            <li
                                v-for="product in products"
                                :key="product.id"
                                class="flex px-4 py-6 sm:px-6"
                            >
                                <div class="ml-6 flex flex-1 flex-col">
                                    <div class="flex">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm">
                                                <a
                                                    :href="product.href"
                                                    class="font-medium text-gray-700 hover:text-gray-800"
                                                    >{{ product.title }}</a
                                                >
                                            </h4>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                {{ product.color }}
                                            </p>
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                {{ product.size }}
                                            </p>
                                        </div>

                                        <div
                                            class="ml-4 flow-root flex-shrink-0"
                                        >
                                            <button
                                                type="button"
                                                class="-m-2.5 flex items-center justify-center bg-white p-2.5 text-gray-400 hover:text-gray-500"
                                            >
                                                <span class="sr-only"
                                                    >Remove</span
                                                >
                                                <TrashIcon
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </button>
                                        </div>
                                    </div>

                                    <div
                                        class="flex flex-1 items-end justify-between pt-2"
                                    >
                                        <p
                                            class="mt-1 text-sm font-medium text-gray-900"
                                        >
                                            {{ product.price }}
                                        </p>

                                        <div class="ml-4">
                                            <label
                                                for="quantity"
                                                class="sr-only"
                                                >Quantity</label
                                            >
                                            <select
                                                id="quantity"
                                                name="quantity"
                                                class="rounded-md border border-gray-300 text-left text-base font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                            >
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <template v-for="(item, index) in items_requested">
                            <dl
                                class="space-y-6 border-t border-gray-200 px-4 py-6 sm:px-6"
                            >
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">
                                        Item# {{ index + 1 }}
                                    </dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900 flex items-center"
                                    >
                                        <img
                                            class="pr-2"
                                            :src="`https://static.divine-pride.net/images/items/item/${item.id}.png`"
                                        />
                                        {{ item.name }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Refine</dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ item.refine }}
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm">Quantity</dt>
                                    <dd
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        x{{ item.quantity }}
                                    </dd>
                                </div>
                            </dl>
                        </template>

                        <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                            <button
                                @click="sendRequest()"
                                type="submit"
                                class="w-full rounded-md border border-transparent bg-gray-900 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                            >
                                Send request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-alert />
</template>
