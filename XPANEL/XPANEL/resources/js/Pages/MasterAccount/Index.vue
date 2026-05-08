<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GamePanel from "@/Components/GamePanel.vue";
import Tabs from "@/Components/Tabs.vue";
import DataTable from "@/Components/DataTable.vue";
import { onMounted, ref } from "vue";

import {
    GiftIcon,
    CreditCardIcon,
    ArrowTrendingUpIcon,
    BookOpenIcon,
    ShieldCheckIcon,
} from "@heroicons/vue/24/outline";
import { usePage } from "@inertiajs/vue3";

const paypalLogs = usePage().props.paypal_logs;
const transferLogs = usePage().props.transfer_logs;
const vote4PointsLogs = usePage().props.vote4points_logs;
const masterAccountLogs = usePage().props.master_account_logs;
const banLogs = usePage().props.ban_logs;
const lang = localStorage.getItem("lang");
const tabList = ["Game Accounts", "Logs"];
const navigation = ref([
    {
        name: "Donations",
        href: "#",
        icon: CreditCardIcon,
        current: true,
        log: {
            name: "Donations",
            description:
                "See the details of all your donations maded in the server.",
            columnsItems: [
                "Date",
                "Amount",
                "Credits",
                "Status",
                "Transaction_ID",
            ],
            dataItems: [],
        },
    },
    {
        name: "Transfer",
        icon: GiftIcon,
        current: false,
        log: {
            name: "Transfer",
            description:
                "See the details of all your transfers maded in the server.",
            columnsItems: [
                "Date",
                "Transfer_Type",
                "Account",
                "Char",
                "Amount",
                "IP",
            ],
            dataItems: [],
        },
    },
    {
        name: "Vote 4 Points",
        icon: ArrowTrendingUpIcon,
        current: false,
        log: {
            name: "Vote 4 Points",
            description:
                "See the details of all your votes maded in the server.",
            columnsItems: ["Date", "Rank_ID", "Points", "IP"],
            dataItems: [],
        },
    },
    {
        name: "Master Account Logs",
        icon: BookOpenIcon,
        current: false,
        log: {
            name: "Master Account Logs",
            description: "See the details of all your master account logs.",
            columnsItems: ["Date", "Action", "IP"],
            dataItems: [],
        },
    },
    {
        name: "Bans",
        icon: ShieldCheckIcon,
        current: false,
        log: {
            name: "Bans",
            description: "See the details of all your bans.",
            columnsItems: [
                "Violator_Account_id",
                "Block_Time",
                "Unban_Time",
                "Violator_Name",
                "Initiator_Name",
                "Reason",
            ],
            dataItems: [],
        },
    },
]);

const selectedLog = ref("Donations");
const getLogs = (logType) => {
    const selectedLog = navigation.value.find((log) => log.name === logType);

    if (selectedLog) {
        setNavigationCurrent(logType);
        return selectedLog.log;
    } else {
        return [];
    }
};

const setNavigationCurrent = (logType) => {
    navigation.value.forEach((logItem) => {
        logItem.current = logItem.name === logType;
    });
};

function createDonationsLog() {
    const donationsLog = navigation.value.find(
        (log) => log.name === "Donations"
    ).log;

    const processedLogs = paypalLogs.data.map((element) => {
        return {
            date: element.payment_date,
            amount: element.mc_gross,
            credits: element.credits + element.bonus,
            status: element.payment_status,
            transaction_id: element.txn_id,
        };
    });

    donationsLog.dataItems.push(...processedLogs);
}

function createTransferLogs() {
    const transferLog = navigation.value.find(
        (log) => log.name === "Transfer"
    ).log;

    const processedLogs = transferLogs.data.map((element) => {
        return {
            date: element.date,
            transfer_type: element.transfer_type,
            account: element.account,
            char: element.char,
            amount: element.amount,
            ip: element.ip,
        };
    });

    transferLog.dataItems.push(...processedLogs);
}

function createVote4PointsLogs() {
    const vote4PointsLog = navigation.value.find(
        (log) => log.name === "Vote 4 Points"
    ).log;

    const processedLogs = vote4PointsLogs.data.map((element) => {
        return {
            date: element.date,
            rank_id: element.rank_id,
            points: element.points,
            ip: element.ip,
        };
    });

    vote4PointsLog.dataItems.push(...processedLogs);
}

function createMasterAccountLogs() {
    const masterAccountLog = navigation.value.find(
        (log) => log.name === "Master Account Logs"
    ).log;

    const processedLogs = masterAccountLogs.data.map((element) => {
        return {
            date: element.date,
            action: element.action,
            ip: element.ip,
        };
    });

    masterAccountLog.dataItems.push(...processedLogs);
}

function createBansLog() {
    const bansLog = navigation.value.find((log) => log.name === "Bans").log;

    const processedLogs = banLogs.data.map((element) => {
        return {
            violator_account_id: element.violator_account_id,
            block_time: element.block_time,
            unban_time: element.unban_time,
            violator_name: element.violator_name,
            initiator_name: element.initiator_name,
            reason: element.reason,
        };
    });

    bansLog.dataItems.push(...processedLogs);
}

onMounted(() => {
    createDonationsLog();
    createTransferLogs();
    createVote4PointsLogs();
    createMasterAccountLogs();
    createBansLog();
});
</script>

<template>
    <AppLayout title="Game Accounts">
        <div class="py-12 min-h-[90rem] relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-36">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg dark:bg-gray-800">
                    <Tabs :tabList="tabList">
                        <template #tabPanel-1>
                            <GamePanel />
                        </template>
                        <template #tabPanel-2>
                            <div class="flex">
                                <!-- Static sidebar for desktop -->
                                <aside
                                    class="hidden lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col"
                                >
                                    <!-- Sidebar component, swap this element with another sidebar if you like -->
                                    <div
                                        class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white p-6 dark:bg-gray-800 dark:border-gray-700"
                                    >
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
                                                                :href="
                                                                    item.href
                                                                "
                                                                :class="[
                                                                    item.current
                                                                        ? 'bg-gray-50 text-sky-400 dark:bg-gray-700 dark:text-sky-400'
                                                                        : 'text-gray-700 hover:text-sky-400 hover:bg-gray-50 dark:text-white dark:hover:text-sky-400 dark:hover:bg-gray-700',
                                                                    'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold cursor-pointer',
                                                                ]"
                                                                @click="
                                                                    selectedLog =
                                                                        item.name
                                                                "
                                                            >
                                                                <component
                                                                    :is="
                                                                        item.icon
                                                                    "
                                                                    :class="[
                                                                        item.current
                                                                            ? 'text-sky-400'
                                                                            : 'text-gray-400 group-hover:text-sky-400',
                                                                        'h-6 w-6 shrink-0',
                                                                    ]"
                                                                    aria-hidden="true"
                                                                />
                                                                {{ item.name }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </aside>
                                <div class="w-full">
                                    <DataTable
                                        class="w-full"
                                        :log="getLogs(selectedLog)"
                                    />
                                </div>
                            </div>
                        </template>
                    </Tabs>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

