<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
    components: {
        Link,
    },
    props: ["href", "active"],

    computed: {
        classes() {
            return this.active
                ? "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text_active focus:outline-none transition"
                : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text_color hover:text-gray-700  focus:outline-none focus:text-gray-700 focus:border-gray-300 transition";
        },
    },
});
</script>

<style scoped>