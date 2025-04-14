<script setup>
defineProps({
    links: Array
});

const emit = defineEmits(['page-change']);
</script>

<template>
    <div class="flex justify-center space-x-2 mt-6">
        <button
            v-for="link in links"
            :key="link.label"
            @click="emit('page-change', link.url)"
            v-html="link.label"
            class="px-4 py-2 border rounded transition-colors duration-200"
            :class="{
                'bg-blue-600 text-white border-blue-600': link.active,
                'text-gray-600 hover:bg-gray-100': !link.active && link.url,
                'text-gray-400 cursor-not-allowed': !link.url
            }"
            :disabled="!link.url"
        />
    </div>
</template>
