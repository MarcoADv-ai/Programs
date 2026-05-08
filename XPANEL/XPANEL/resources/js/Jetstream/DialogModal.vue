<template>
    <modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-6 py-4">
            <div class="text-lg text-black dark:text-white">
                <slot name="title"> </slot>
                <jet-validation-errors class="mb-4" />
            </div>

            <div class="mt-4 text-black dark:text-white">
                <slot name="content"> </slot>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right dark:bg-gray-900">
            <slot name="footer"> </slot>
        </div>
    </modal>
</template>

<script>
import { defineComponent } from "vue";
import Modal from "./Modal.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default defineComponent({
    emits: ["close"],

    components: {
        Modal,
        JetValidationErrors,
    },

    props: {
        show: {
            default: false,
        },
        maxWidth: {
            default: "2xl",
        },
        closeable: {
            default: true,
        },
    },

    methods: {
        close() {
            this.$emit("close");
        },
    },
});
</script>
