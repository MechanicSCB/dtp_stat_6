<script setup>
defineProps({post:Object});

function excerpt(text, len = 300) {
    if (!text) {
        return text;
    }

    text = text.replace(/<\/?[^>]+(>|$)/g, "");

    if (text.length > len) {
        text = text.substring(0, len) + '...';
    }

    return text;
}

function getPreview(){

}
</script>

<template>
    <card class="rounded-xl hover:bg-[#EBEEF0] py-3 px-4">
        <Link :href="'/posts/' + post.id">
            <img class="rounded-xl mb-6 aspect-[3/2]" :src="'/storage/img/posts/post'+post.id+'_prev.jpg'" alt="">
        </Link>

        <!-- tags -->
        <div class="flex gap-2">
            <Link v-for="tag in JSON.parse(post.tags)" :href="'/posts?tag=' + tag">
                <tag class="rounded px-2 py-1 bg-[#F4F8FA] text-xs font-bold">
                    {{tag}}
                </tag>
            </Link>
        </div>

        <Link :href="'/posts/' + post.id">
            <h2 class="my-2 text-2xl font-bold">{{post.title}}</h2>
            <div v-html="excerpt(post.body)"></div>
        </Link>
    </card>
</template>
