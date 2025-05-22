<template>
    <nav class="relative flex justify-center">
        <template v-for="link in links" :key="link.label">
            <!-- Active link display -->
            <span v-if="link.active" v-html="link.label"
                class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600 bg-gray-200 cursor-default">
            </span>

            <!-- Disabled link display -->
            <span v-else-if="!link.url" v-html="link.label"
                class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-300 cursor-default">
            </span>

            <!-- Normal link display -->
            <Link v-else preserve-scroll :href="link.url" v-html="link.label"
                class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600 hover:bg-gray-100" />
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Object,
})

</script>
