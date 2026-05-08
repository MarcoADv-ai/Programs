<script setup>
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    log: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="p-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">
                    {{ log.name }}
                </h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    {{ log.description }}
                </p>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <table class="min-w-full border-separate border-spacing-0">
                        <thead>
                            <tr>
                                <th
                                    v-for="(
                                        column, columnIdx
                                    ) in log.columnsItems"
                                    scope="col"
                                    class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8 dark:bg-gray-800 dark:text-white"
                                >
                                    {{ column.split("_").join(" ") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(logItem, logIdx) in log.dataItems"
                                :key="logIdx"
                            >
                                <td
                                    v-for="(
                                        columnItem, columnIndex
                                    ) in log.columnsItems"
                                    :key="columnIndex"
                                    :class="[
                                        logIdx !== log.dataItems.length - 1
                                            ? 'border-b border-gray-200'
                                            : '',
                                        'whitespace-nowrap py-4 pl-4 pr-3 text-xs font-medium text-gray-900 sm:pl-6 lg:pl-8 dark:text-white',
                                    ]"
                                >
                                    {{ logItem[columnItem.toLowerCase()] }}
                                </td>
                            </tr>
                        </tbody>
                        <!-- <pagination
                            :links="log.links"
                            class="mt-4 text-gray-600"
                        /> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
