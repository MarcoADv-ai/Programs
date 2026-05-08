<script setup>
import JetBanner from "@/Jetstream/Banner.vue";
import { Head, router} from "@inertiajs/vue3";
import { computed, ref, onMounted } from "vue";
const showingNavigationDropdown = ref(false);

function logout() {
    router.post(route("logout"));
}
</script>

<template>
    <div>
        <Head :title="title" />

        <jet-banner />
        <!-- Page Content -->
        <main class="mx-auto">
            <div class="w-full text-white">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<style scoped>
main {
    /* The image used */
    background-image: url("/img/bg2.jpg");

    /* Full height */
    width: 100%;
    height: auto;
    background-attachment: fixed;
    background-size: cover;
}
</style>
