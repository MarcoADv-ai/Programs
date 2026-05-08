<script setup>
import JetBanner from "@/Jetstream/Banner.vue";
import xFooter from "@/Components/Footer.vue";
import xHeader from "@/Components/Header.vue";
import xAlert from "@/Components/Alert.vue";
import { Head } from "@inertiajs/vue3";
import { ref, provide } from "vue";

const props = defineProps({
    title: String,
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
            <main class="mx-auto ">
                <div class="w-full text-white dark:bg-black/60" id="wrap">
                    <slot></slot>
                </div>
            </main>
            <x-footer />
            <x-alert />
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
