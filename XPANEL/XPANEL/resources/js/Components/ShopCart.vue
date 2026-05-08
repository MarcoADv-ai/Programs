<script setup>
import { ShoppingCartIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { onMounted, reactive, ref } from "vue";
import { cartShop } from "@/store.js";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

const config = usePage().props.xpanel.donations;
const cartInfo = reactive({
    products: [],
    totalPrice: 0,
});

const loading = ref(false);
const enabled_payment_methods = Object.entries(config.methods)
    .filter(([key, method]) => method.enabled)
    .map(([key, method]) => ({ key, ...method }));

const selected = ref(enabled_payment_methods[0]);
function getTotalPrice() {
    let total = 0;
    cartInfo.products.forEach((product) => {
        total += product.price * product.quantity;
    });
    return total;
}

async function makePurchase() {
    loading.value = true;

    const productsInfo = cartInfo.products.map((product) => ({
        id: product.id,
        qty: product.quantity,
    }));

    await axios
        .post("/donations/create", {
            items: productsInfo,
            payment_method: selected.value.name,
        })
        .then((response) => {
            if (response.data.status === "success") {
                loading.value = false;
                cartShop().clearCart();
                window.location.href = response.data.payment_url;
            } else {
                loading.value = false;
                alert("Something went wrong, please try again later.");
            }
        });
}

onMounted(() => {
    cartInfo.products = cartShop().items;
    getTotalPrice();
});
</script>

<template>
    <Popover class="ml-4 flow-root text-sm lg:relative lg:ml-8" v-if="enabled_payment_methods.length > 0">
        <PopoverButton class="group -m-2 flex items-center p-2">
            <ShoppingCartIcon
                class="h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-500 dark:text-gray-300 dark:group-hover:text-gray-200"
                aria-hidden="true"
            />
            <span
                class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800 dark:text-gray-300 dark:group-hover:text-gray-200"
                >{{ cartInfo.products.length }}</span
            >
            <span class="sr-only">items in cart, view bag</span>
        </PopoverButton>
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <PopoverPanel
                class="absolute inset-x-0 top-16 mt-px bg-white pb-6 shadow-lg sm:px-2 lg:left-auto lg:right-0 lg:top-full lg:-mr-1.5 lg:mt-3 lg:w-80 lg:rounded-lg lg:ring-1 lg:ring-black lg:ring-opacity-5 z-10 dark:bg-gray-800"
            >
                <h2 class="sr-only">Shopping Cart</h2>

                <form
                    class="mx-auto max-w-2xl px-4"
                    v-if="cartInfo.products.length > 0"
                >
                    <ul role="list" class="divide-y divide-gray-200">
                        <li
                            v-for="product in cartInfo.products"
                            :key="product.id"
                            class="flex items-center py-6"
                        >
                            <div class="ml-4 flex-auto">
                                <div class="flex items-center space-x-1">
                                    <h3
                                        class="font-semibold text-md text-gray-900 dark:text-gray-200"
                                    >
                                        {{ product.name }} x
                                        {{ product.quantity }}
                                    </h3>
                                    <TrashIcon
                                        class="h-4 w-4 text-gray-400 hover:text-red-500 cursor-pointer ease-in-out duration-150 dark:text-gray-300 dark:hover:text-red-400"
                                        @click="
                                            cartShop().removeItem(product.id)
                                        "
                                    />
                                </div>
                                <ul class="list-inside list-disc text-xs">
                                    <li
                                        class="text-gray-500 dark:text-gray-400"
                                        v-for="feature in product.features"
                                    >
                                        {{ feature.replace("+ ", "") }}
                                    </li>
                                </ul>
                            </div>
                            <p class="text-lg font-semibold">
                                $ {{ product.price }}
                                <span class="text-xs">/ unit</span>
                            </p>
                        </li>
                    </ul>

                    <button
                        @click.prevent="makePurchase()"
                        type="submit"
                        :disabled="loading"
                        class="w-full rounded-md border space-x-1 border-transparent bg-sky-400 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-gray-50"
                    >
                        Checkout
                        <p class="font-bold inline">(${{ getTotalPrice() }})</p>
                        <span
                            class="loader animation-spin h-3 w-3 bg-transparent opacity-0 absolute box-border transition-all duration-500 ease-in-out mx-auto rounded-full"
                            :class="{ 'opacity-100': loading }"
                        >
                        </span>
                    </button>
                    <div id="wallet_container"></div>
                    <div class="relative z-100 mt-4">
                        <Listbox as="div" v-model="selected">
                            <ListboxLabel
                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200"
                                >Payment Method</ListboxLabel
                            >
                            <div class="relative mt-2">
                                <ListboxButton
                                    class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 sm:text-sm sm:leading-6"
                                >
                                    <span class="flex items-center">
                                        <img
                                            :src="selected.icon"
                                            alt=""
                                            class="h-5 w-5 flex-shrink-0 rounded-full"
                                        />
                                        <span class="ml-3 block truncate">{{
                                            selected.name
                                        }}</span>
                                    </span>
                                    <span
                                        class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                                    >
                                        <ChevronUpDownIcon
                                            class="h-5 w-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </ListboxButton>

                                <transition
                                    leave-active-class="transition ease-in duration-100"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <ListboxOptions
                                        class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <ListboxOption
                                            as="template"
                                            v-for="payment in enabled_payment_methods"
                                            :key="payment.id"
                                            :value="payment"
                                            v-slot="{ active, selected }"
                                        >
                                            <li
                                                :class="[
                                                    active
                                                        ? 'bg-sky-600 text-white'
                                                        : 'text-gray-900',
                                                    'relative cursor-default select-none py-2 pl-3 pr-9',
                                                ]"
                                            >
                                                <div class="flex items-center">
                                                    <img
                                                        :src="payment.icon"
                                                        alt=""
                                                        class="h-5 w-5 flex-shrink-0 rounded-full"
                                                    />
                                                    <span
                                                        :class="[
                                                            selected
                                                                ? 'font-semibold'
                                                                : 'font-normal',
                                                            'ml-3 block truncate',
                                                        ]"
                                                        >{{ payment.name }}</span
                                                    >
                                                </div>

                                                <span
                                                    v-if="selected"
                                                    :class="[
                                                        active
                                                            ? 'text-white'
                                                            : 'text-sky-600',
                                                        'absolute inset-y-0 right-0 flex items-center pr-4',
                                                    ]"
                                                >
                                                    <CheckIcon
                                                        class="h-5 w-5"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                    </div>
                </form>
                <template v-else>
                    <p class="mt-6 text-center">
                        <a href="#" class="text-sm font-medium text-gray-400 dark:text-gray-300"
                            >Cart shop is empty :-(
                        </a>
                    </p>
                </template>
            </PopoverPanel>
        </transition>
    </Popover>
</template>

<style scoped>
.loader {
    bottom: 11.4%;
    border-top: 2px solid #fff;
    border-left: 2px solid #fff;
    border-bottom: 2px solid #fff;
    border-right: 2px solid rgba(#fff, 0.35);
}

.animation-spin {
    animation-name: spin;
    animation-duration: 0.75s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
