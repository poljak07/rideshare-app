<script setup>
import { ref } from 'vue';
import AppNavbar from '@/Components/AppNavbar.vue';

const principles = ref([
    {
        title: "Vivamus luctus",
        description: "Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque eu turpis sed erat pulvinar ullamcorper.",
        icon: "👥"
    },
    {
        title: "Aenean nec",
        description: "Aenean eu aliquet nibh. Nam fringilla nibh id rhoncus lobortis. Cras dui velit, volutpat non dignissim a, accumsan quis quam.",
        icon: "✨"
    },
    {
        title: "Curabitu",
        description: " Pellentesque a consequat risus, vitae viverra lorem. Sed scelerisque, risus quis tincidunt sollicitudin, elit mi consectetur libero, non iaculis ipsum neque vel diam",
        icon: "🚀"
    },
    {
        title: "Pellentesque",
        description: "Pellentesque nunc risus, laoreet eu blandit in, luctus sit amet leo. Integer porttitor volutpat dictum.",
        icon: "🔍"
    },
    {
        title: "Vivamus",
        description: "Cras vel elementum purus. Duis lobortis fringilla ipsum, eu vehicula sem tristique at. Aliquam porta odio vitae lorem tempor iaculis.",
        icon: "🤝"
    },
    {
        title: "Aliquam",
        description: "Vestibulum in orci neque. Sed sodales leo bibendum vestibulum sodales. Duis laoreet dictum scelerisque. ",
        icon: "📚"
    }
]);

const activePrinciple = ref(0);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <AppNavbar />

        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Fusce eu purus id neque</h1>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Sed hendrerit condimentum erat a vestibulum. Sed sit amet eleifend risus.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <div
                            v-for="(principle, index) in principles"
                            :key="index"
                            @click="activePrinciple = index"
                            :class="{
                'bg-white shadow-md border-blue-500': activePrinciple === index,
                'bg-gray-100 hover:bg-gray-200': activePrinciple !== index
              }"
                            class="p-4 rounded-lg border border-transparent cursor-pointer transition-all duration-300"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">{{ principle.icon }}</span>
                                <h3 class="text-lg font-medium text-gray-900">{{ principle.title }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="bg-white p-6 rounded-lg shadow-md h-full">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="text-3xl">{{ principles[activePrinciple].icon }}</span>
                                <h2 class="text-2xl font-bold text-gray-900">{{ principles[activePrinciple].title }}</h2>
                            </div>
                            <p class="text-gray-700 text-lg leading-relaxed">
                                {{ principles[activePrinciple].description }}
                            </p>

                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Interdum et malesuada fames?</h3>
                                <ul class="space-y-2 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-500 mr-2">•</span>
                                        <span>Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
