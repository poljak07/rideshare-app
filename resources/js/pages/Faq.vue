<script setup>
import { ref } from 'vue';
import AppNavbar from '@/Components/AppNavbar.vue';

const faqItems = ref([
    {
        question: "How does this work?",
        answer: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor.",
        isOpen: false
    },
    {
        question: "Can I pay with my card?",
        answer: "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.",
        isOpen: false
    },
    {
        question: "How can I submit a ride?",
        answer: "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.",
        isOpen: false
    }
]);

const toggleItem = (index) => {
    faqItems.value[index].isOpen = !faqItems.value[index].isOpen;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navbar na vrhu -->
        <AppNavbar />

        <!-- FAQ sadržaj ispod navbara -->
        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Često postavljana pitanja</h1>

                <div class="flex w-full flex-col gap-4 overflow-hidden">
                    <div
                        v-for="(item, index) in faqItems"
                        :key="index"
                        class="flex flex-col items-start justify-start rounded-lg border border-slate-200 bg-white p-3 hover:cursor-pointer hover:bg-gray-50"
                    >
                        <button
                            type="button"
                            @click="toggleItem(index)"
                            class="group rounded-lg align-middle text-sm font-semibold transition-all duration-300 ease-in-out stroke-slate-500 text-black min-w-[38px] gap-2 hover:opacity-80 flex h-auto w-full items-center justify-between overflow-hidden whitespace-pre-wrap p-0 text-left leading-tight"
                            :aria-expanded="item.isOpen"
                            :aria-controls="`faq-content-${index}`"
                        >
                            <div>
                                <div class="flex w-full items-center justify-start gap-2">
                                    <p class="w-full font-medium">{{ item.question }}</p>
                                </div>
                            </div>
                            <div class="size-5">
                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 20 20"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="stroke-black transition-transform duration-300 ease-in-out"
                                    :class="{ 'transform rotate-180': item.isOpen }"
                                >
                                    <path d="M5.83325 8.33325L9.99992 12.4999L14.1666 8.33325"></path>
                                </svg>
                            </div>
                        </button>
                        <div
                            :id="`faq-content-${index}`"
                            role="region"
                            :aria-hidden="!item.isOpen"
                            class="grid w-full text-xs text-slate-600 transition-[grid-template-rows,opacity] duration-300 ease-out md:pr-7 text-justify"
                            :class="item.isOpen ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'"
                        >
                            <div class="overflow-hidden">
                                <p class="py-2">{{ item.answer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
