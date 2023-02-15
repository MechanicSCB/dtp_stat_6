<script setup>
import PostCard from "./Partials/PostCard.vue";

let props = defineProps({posts: Object, tag:String});
</script>

<template>
    <div class="w-full text-[#18334A]">
        <h1 class="mt-12 text-center text-3xl font-bold mb-12">
            Статьи и исследования
            <span v-if="tag"> - {{tag}}</span>
        </h1>
        <div class="mx-auto grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 max-w-[1140px] px-4">
            <PostCard v-for="post in posts" :post="post"/>
        </div>
    </div>
</template>
