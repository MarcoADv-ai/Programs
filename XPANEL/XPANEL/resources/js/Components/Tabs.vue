<template>
    <div>
        <ul
            class="list-none flex flex-row bg_black bg-opacity-30 p-1.5 rounded-sm text-center overflow-auto whitespace-nowrap"
        >
            <li
                v-for="(tab, index) in tabList"
                :key="index"
                class="w-full px-4 py-1.5 rounded-md"
                :class="{
                    'text_gray bg-gray-500 shadow-xl': index + 1 === activeTab,
                    'text-white': index + 1 !== activeTab,
                }"
            >
                <label
                    :for="`${index}`"
                    v-text="tab"
                    class="cursor-pointer block"
                />
                <input
                    :id="`${index}`"
                    type="radio"
                    :name="`${index}-tab`"
                    :value="index + 1"
                    v-model="activeTab"
                    class="hidden"
                />
            </li>
        </ul>
        <template v-for="(tab, index) in tabList">
            <div
                :key="index"
                v-if="index + 1 === activeTab"
                class=" p-2 mt-1 overflow-hidden"
            >
                <slot :name="`tabPanel-${index + 1}`" />
            </div>
        </template>
    </div>
</template>

<script>
export default {
    data() {
        return {
            activeTab: 1,
        };
    },

    props: {
        tabList: {
            type: Array,
            required: true,
        },
    },
};
</script>

<style scoped>
.bg_black {
    background-color: #171717;
}

.bg_gray {
    background-color: #444444;
}

.text_gray {
    color: #ededed;
}
</style>
