<template>
    <div>
        <slot />
    </div>
    <ul class="flex space-x-4 justify-center">
        <li
            v-for="(tab, i) of tabs"
            :key="i"
            @click="selectTab(i)"
            class="flex flex-col mt-10 items-center px-6 py-3 rounded-tl-md rounded-tr-md overflow-hidden cursor-pointer hover:text-gray-800"
        >
            <div
                :class="
                    active === i
                        ? 'border-b-4 border-green-500 box-content text-gray-800'
                        : 'border-b-2 border-white text-gray-500'
                "
                class="step rounded-full bg-blue-300"
            ></div>
            {{ tab.props.title }}
        </li>
    </ul>
</template>

<script>
import { provide, computed, ref } from "vue";

export default {
    props: {
        modelValue: {
            type: [String, Number],
        },
    },
    emits: ["update:modelValue"],
    setup(props, { slots, emit }) {
        const active = computed(() => props.modelValue);
        const tabs = ref([]);

        function selectTab(tab) {
            emit("update:modelValue", tab);
        }
        provide("tabsState", {
            active,
            tabs,
        });

        return {
            tabs,
            active,
            selectTab,
        };
    },
};
</script>

<style scoped>
.step {
    width: 13px;
    height: 13px;
    border: 1px solid #997338;
}

.step::before {
    content: "";
    position: absolute;
    display: block;
    border-radius: 50%;
    top: 50%;
    left: 50%;
}
</style>
