<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label text-sm dark:text-white" :for="id"
            >{{ label }}:</label
        >
        <br />
        <input
            :id="id"
            ref="input"
            v-bind="{ ...$attrs, class: null }"
            class="form-input w-full rounded-sm border border-gray-400 text-sm"
            :class="{ error: error }"
            :type="type"
            :placeholder="placeholder"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import { v4 as uuid } from "uuid";

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${uuid()}`;
            },
        },
        type: {
            type: String,
            default: "text",
        },
        error: String,
        label: String,
        modelValue: String,
        placeholder: String,
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        },
    },
};
</script>
