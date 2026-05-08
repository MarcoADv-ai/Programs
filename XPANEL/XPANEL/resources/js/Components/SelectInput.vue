<script setup>
import { ref, defineEmits, watchEffect, watch, onMounted } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";
let pageInitialized = false;
const emit = defineEmits(["getSelected"]);
const props = defineProps({
    options: Object,
    label: String,
    required: Boolean,
    disabled: Boolean,
    placeholder: String,
});
const selected = ref();

onMounted(() => {
    pageInitialized = true;
});

watchEffect(() => {
    if(!pageInitialized) return;

    if (props.options.length > 0) {
        selected.value = props.options[0].select;
    }
});

watchEffect(() => {
    emit("getSelected", selected.value);
});
</script>

<template>
    <Listbox as="div" v-model="selected">
        <ListboxLabel class="block text-sm font-medium leading-6 text-gray-900 dark:text-white"
            >{{ props.label }}
            <span v-if="props.required" class="text-red-500">*</span>
        </ListboxLabel>
        <div class="relative">
            <ListboxButton
                :disabled="disabled"
                :class="{ 'cursor-not-allowed': disabled, 'bg-gray-200' : disabled}"
                class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-cyan-400 sm:text-sm sm:leading-6"
            >
                <span class="block truncate">{{ selected ?? '-- Select --'}}</span>
                <span
                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
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
                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        as="template"
                        v-for="option in props.options"
                        :key="option.select"
                        :value="option.select"
                        v-slot="{ active, selected }"
                    >
                        <li
                            :class="[
                                active
                                    ? 'bg-sky-500 text-white'
                                    : 'text-gray-900',
                                'relative cursor-default select-none py-2 pl-3 pr-9',
                            ]"
                        >
                            <span
                                :class="[
                                    selected ? 'font-semibold' : 'font-normal',
                                    'block truncate',
                                ]"
                                >{{ option.select }}</span
                            >

                            <span
                                v-if="selected"
                                :class="[
                                    active ? 'text-white' : 'text-indigo-600',
                                    'absolute inset-y-0 right-0 flex items-center pr-4',
                                ]"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>
