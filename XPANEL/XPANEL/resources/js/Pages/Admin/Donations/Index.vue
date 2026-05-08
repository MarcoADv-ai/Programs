<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetModalDialog from "@/Jetstream/DialogModal.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetButton from "@/Jetstream/Button.vue";
import Pagination from "@/Components/Pagination.vue";
import { reactive, computed, ref, watch } from "vue";
import { notify } from "notiwind";
import { UsersIcon } from "@heroicons/vue/20/solid";

const donations = computed(() => usePage().props.donations);
const rank_doners = usePage().props.rank_doners;
const most_donated = usePage().props.most_donated;
const donation = ref({});
const modal = reactive({
    showDonation: false,
});
const query_search = ref("");
const state = reactive({
    title: "",
    message: "",
    group: "",
    duration: 5000,
});

function showDonation(id) {
    axios
        .get(route("admin.donations.show", id))
        .then((response) => {
            donation.value = response.data.donation;
            modal.showDonation = true;
        })
        .catch((error) => {
            console.log(error);
        });
}

function closeDonation() {
    modal.showDonation = false;
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

function donationDestroy(id) {
    axios
        .delete(route("admin.donations.destroy", id))
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
function donationUpdate(id) {
    axios
        .put(route("admin.donations.update", id), donation.value)
        .then((response) => {
            state.title = "Success";
            state.message = response.data.message;
            state.group = "success";
            notifyMe();
            router.reload();
        });
}

watch(query_search, (value) => {
    router.reload({ data: { q: value } });
});
</script>
<template>
    <AdminLayout :title="'Donations Info'">
        <div class="grid grid-rows-2">
            <div class="flex ">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full shadow-md rounded bg-white px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex w-full"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Donations
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
                                    placeholder="Search Donation"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="block w-full">
                        <table
                            class="items-center w-full bg-transparent border-collapse"
                        >
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        ID #
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Master Email
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Credits
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Gross
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Payment Date
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Tax
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        T. ID
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    >
                                        Payer Email
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="dona in donations.data">
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center"
                                    >
                                        <span
                                            class="ml-3 font-bold text-blueGray-600"
                                        >
                                            {{ dona.id }}</span
                                        >
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ dona.user.email }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ dona.credits }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ dona.mc_gross }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <span
                                            v-if="
                                                dona.payment_status ===
                                                'Completed'
                                            "
                                            class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-1.5 py-0.5 text-xs font-medium text-green-700"
                                        >
                                            <svg
                                                class="h-1.5 w-1.5 fill-green-500"
                                                viewBox="0 0 6 6"
                                                aria-hidden="true"
                                            >
                                                <circle cx="3" cy="3" r="3" />
                                            </svg>
                                            Completed
                                        </span>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            <span class="mr-2">{{
                                                dona.payment_date
                                            }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            <span class="mr-2">{{
                                                dona.mc_fee
                                            }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            <span class="mr-2">{{
                                                dona.txn_id
                                            }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <div class="flex items-center">
                                            <span class="mr-2">{{
                                                dona.payer_email
                                            }}</span>
                                        </div>
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
                                                        Edit
                                                    </JetDropdownLink>
                                                    <JetDropdownLink
                                                        :as="'button'"
                                                        @click="
                                                            donationDestroy(
                                                                dona.xid
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
                                                    <!-- <JetDropdownLink
                                                        href=""
                                                        class="flex justify-start w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                    >
                                                        <font-awesome-icon
                                                            icon="archive"
                                                            class="mr-2"
                                                        ></font-awesome-icon>
                                                        Archive
                                                    </JetDropdownLink> -->
                                                </div>
                                            </template>
                                        </jet-dropdown>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination class="p-2" :links="donations.links" />
                </div>
            </div>
            <div class="flex flex-wrap mt-4">
            <div class="w-full xl:w-8/12 xl:mb-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-md rounded px-3"
                >
                    <div class="rounded-t border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="rounded-t mb-0 px-4 py-3 border-0"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Most Donated Package
                                </h3>
                            </div>
                            <table
                                class="items-center w-full bg-transparent border-collapse"
                            >
                                <thead>
                                    <tr>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Package
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Total Donations
                                        </th>
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100"
                                        >
                                            Gross
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="dona in most_donated">
                                        <th
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center"
                                        >
                                            <span
                                                class="ml-3 font-bold text-blueGray-600"
                                            >
                                                {{ dona.mc_gross }}</span
                                            >
                                        </th>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ dona.count }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                        >
                                            {{ dona.total }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full xl:w-4/12 pl-4 h-full">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full shadow-md rounded h-full px-3"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full max-w-full flex-grow flex-1"
                            >
                                <h3
                                    class="font-semibold text-lg text-blueGray-700"
                                >
                                    Top Donators
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-hidden">
                        <table
                            class="items-center w-full bg-transparent border-collapse"
                        >
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Count
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Total donated
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="doner in rank_doners.data">
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                    >
                                        {{ doner.user.email }}
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ doner.count_donations }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ doner.total_donated }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <jet-modal-dialog
            :maxWidth="'4xl'"
            :show="modal.showDonation"
            @close="closeDonation"
            class="text-black"
        >
            <template #title>Transaction ID: {{ donation.txn_id }}</template>
            <template #content>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Email (Master Account)</jet-label>
                        <jet-input v-model="donation.email" class="w-full" />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Transaction ID</jet-label>
                        <jet-input v-model="donation.txn_id" class="w-full" />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-full">
                        <jet-label>Item</jet-label>
                        <jet-input
                            v-model="donation.item_name"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Quantity</jet-label>
                        <jet-input v-model="donation.quantity" class="w-full" />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Payment Status</jet-label>
                        <select
                            v-model="donation.payment_status"
                            class="w-full"
                        >
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Canceled">Canceled</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>Payment Date</jet-label>
                        <jet-input
                            v-model="donation.payment_date"
                            class="w-full"
                        />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Mc gross</jet-label>
                        <jet-input v-model="donation.mc_gross" class="w-full" />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-full">
                        <jet-label>Payer Email</jet-label>
                        <jet-input
                            v-model="donation.payer_email"
                            class="w-full"
                        />
                    </div>
                </div>
                <div class="flex flex-cols space-x-4">
                    <div class="w-1/2">
                        <jet-label>First Name</jet-label>
                        <jet-input
                            v-model="donation.first_name"
                            class="w-full"
                        />
                    </div>
                    <div class="w-1/2">
                        <jet-label>Last Name</jet-label>
                        <jet-input
                            v-model="donation.last_name"
                            class="w-full"
                        />
                    </div>
                </div>
            </template>
            <template #footer>
                <jet-button class="mr-2" @click="donationUpdate(donation.xid)"
                    >Edit</jet-button
                >
                <jet-secondary-button @click="closeDonation">
                    Close
                </jet-secondary-button>
            </template>
        </jet-modal-dialog>

        
    </AdminLayout>
</template>
