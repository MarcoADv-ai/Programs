<script setup>
import Pagination from "@/Components/Pagination.vue";
import RankingPanel from "@/Components/RankingPanel.vue";
import RankingLayout from "@/Layouts/RankingLayout.vue";
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { usePage } from "@inertiajs/vue3"
import { computed } from "vue";
import ItemCard from "@/Components/ItemCard.vue";

const items = computed(() => usePage().props.items);
const lang = localStorage.getItem("lang");
</script>
<template>
    <RankingLayout>
        <RankingPanel
            title="Item Database"
            :rank="'itemdb'"
            :filter_type="'itemdb'"
        >
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full py-2 align-middle">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="text-black py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-3 dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Item</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Article</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Artículo</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Item</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Server count</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Compte du serveur</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Conteo del servidor</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Contagem do servidor</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Refinable</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Raffinable</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Refinable</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Refinável</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Min Level</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Niveau minimum</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Nivel mínimo</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Nível mínimo</template
                                    >
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-black dark:text-gray-300"
                                >
                                    <template v-if="lang == 'US'"
                                        >Description</template
                                    >
                                    <template v-else-if="lang == 'FR'"
                                        >Description</template
                                    >
                                    <template v-else-if="lang == 'ES'"
                                        >Descripción</template
                                    >
                                    <template v-else-if="lang == 'PT'"
                                        >Descrição</template
                                    >
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(item, index) in items.data">
                                <td
                                    class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0"
                                >
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div
                                                class="font-medium text-black flex space-x-2 dark:text-gray-300 flex items-center"
                                            >
                                                <div>
                                                    <ItemCard :item="item"/>
                                                </div>
                                                <div>
                                                    {{ item.name_english }}
                                                    <span v-if="item.slots != null">
                                                        [{{
                                                            item.slots != null
                                                                ? item.slots
                                                                : 0
                                                        }}] 
                                                    </span>
                                                    ({{ item.type }})
                                                </div>
                                            </div>
                                            <div
                                                class="mt-1 text-[#3b87f6] flex space-x-2"
                                            >
                                                <div>
                                                    ID:
                                                    <span
                                                        class="text-xs text-gray-400"
                                                        >{{ item.id }}</span
                                                    >
                                                </div>
                                                <div>
                                                    Weight
                                                    <span
                                                        class="text-xs text-gray-400"
                                                        >{{ item.weight }}</span
                                                    >
                                                </div>
                                                <div>
                                                    Level:
                                                    <span
                                                        class="text-xs text-gray-400"
                                                        >{{
                                                            item.armor_level
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap py-5 pl-4 p-3 text-sm sm:pl-0 text-black dark:text-gray-300"
                                >
                                    <div class="px-3">
                                        <p>{{ item.count }}</p>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap py-5 pl-4 p-3 text-sm sm:pl-0"
                                >
                                    <div class="px-3">
                                        <p>
                                            {{
                                                item.refineable != null
                                                    ? "✅"
                                                    : "❌"
                                            }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap py-5 pl-4 p-3 text-sm sm:pl-0 text-black dark:text-gray-300"
                                >
                                    <div class="px-3">
                                        <p>
                                            {{
                                                item.equip_level_min != null
                                                    ? item.equip_level_min
                                                    : "❌"
                                            }}
                                        </p>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0 text-black dark:text-gray-300"
                                >
                                    <div class="flex items-center px-3">
                                        <p>
                                            {{
                                                item &&
                                                item.description &&
                                                item.description[0]
                                                    ? item.description[0]
                                                    : ""
                                            }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <pagination class="p-3 text-black" :links="items.links" />
                </div>
            </div>
        </RankingPanel>
    </RankingLayout>
</template>
