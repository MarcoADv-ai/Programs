<script setup>
import JetBanner from "@/Jetstream/Banner.vue";
import xFooter from "@/Components/Footer.vue";
import { Head } from "@inertiajs/vue3";
import xHeader from "@/Components/Header.vue";
import { ref, provide, defineProps } from "vue";
import "vue3-carousel/dist/carousel.css";
const props = defineProps({
    title: {
        type: String,
    },
});
const isDarkMode = ref(localStorage.getItem("darkMode") === "true");
if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
} else {
    document.documentElement.classList.remove("dark");
}

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add("dark");
        localStorage.setItem("darkMode", "true");
    } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("darkMode", "false");
    }
};

provide("toggleDarkMode", toggleDarkMode);
provide("isDarkMode", isDarkMode);
</script>
<template>
    <div class="relative">
        <Head :title="title" />
        <jet-banner />
        <div class="">
            <xHeader :user="$page.props.user" />

            <!-- Page Content -->
            <main class="mx-auto min-h-screen">
                <div class="w-full text-white dark:bg-black/60">
                    <slot></slot>
                </div>
            </main>
            <x-footer />
        </div>
    </div>
</template>

<style scoped>
main {
    /* The image used */
    background-image: url("/img/bg-main.jpg");
    background-size: cover;
    background-attachment: fixed;
    /* Full height */
    width: 100%;
    height: auto;
}
</style>
