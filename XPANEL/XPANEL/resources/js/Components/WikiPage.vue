<script setup>
import { defineProps, computed } from "vue";
const props = defineProps(["post"]);
</script>

<template>
    <article class="w-[65%] mx-auto h-full">
        <template v-if="props.post.body != null"> 
            <div  v-html="props.post.body" class="overflow-x-auto ck-content" ></div>
        </template>
        <template v-else>
            <h1 class="text-3xl text-center text-gray-500 my-auto mt-10">
                Welcome to the Wiki! Please select a category to view articles.
            </h1>
            <p class="text-center text-gray-500 my-auto mt-5">
                Here is your index wiki page. You can add more categories and articles in the admin panel.
                before you can edit this file to make a custom welcome message.
            </p>
        </template>
        
    </article>
</template>
