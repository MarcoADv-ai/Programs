<template>
    <Link :href="'/'">
        <img src="/img/logo.png" alt="logo" srcset="" />
    </Link>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
    components: {
        Link,
    },
});
</script>
