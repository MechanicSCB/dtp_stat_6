<script setup>
import TwitterLogo from "../../Svg/TwitterLogo.vue";
import TelegramLogo from "../../Svg/TelegramLogo.vue";
import VKLogo from "../../Svg/VKLogo.vue";
import OKLogo from "../../Svg/OKLogo.vue";

defineProps({post: Object});
</script>

<template>
    <div class="mx-auto pt-12 px-[15px] max-w-[900px] text-[#18334A]">
        <!-- tags -->
        <div class="flex flex-wrap gap-2 justify-center mb-6">
            <Link v-for="tag in JSON.parse(post.tags)" :href="'/posts?tag=' + tag">
                <tag class="text-xs font-bold underline">
                    {{ tag }}
                </tag>
            </Link>
        </div>

        <h1 class="text-center text-5xl font-bold">{{ post.title }}</h1>
        <p class="mt-4 text-center text-[rgba(24,51,74,0.72)]">
            {{ post.date }}
            <span v-if="post.author_name"> | {{post.author_name}} </span>
        </p>
        <img class="mx-auto my-6 max-w-full aspect-[3/2]" :src="post.image" alt="">

        <div class="flex md:flex-row flex-col">
            <div class="flex md:flex-col flex-row gap-1 h-fit md:sticky top-0 justify-center mb-3">
                <a href="#"><TwitterLogo class="p-1 w-6 h-6 fill-white bg-[#00aced] rounded"/></a>
                <a href="#"><TelegramLogo class="p-0.5 w-6 h-6 fill-white bg-[#64a9dc] rounded"/></a>
                <a href="#"><VKLogo class="w-6 h-6 fill-white bg-[#0077FF] rounded"/></a>
                <a href="#"><OKLogo class="w-6 h-6 rounded"/></a>
            </div>

            <div class="post_body md:mx-12 text-lg mb-6" v-html="post.body"></div>
        </div>
    </div>
</template>
<style>
.post_body p{
    font-family: Merriweather !important;
    font-size: 18px;
    text-align: justify;
    margin-bottom: 12px;
}
.post_body a{
    text-decoration: underline;
}
.post_body a:hover{
    color: #0056b3;
}
.post_body ul{
    padding-left: 40px;
    padding-bottom: 12px;
    font-size: 0.95rem;
}
.post_body li{
    list-style:disc;
}
</style>
