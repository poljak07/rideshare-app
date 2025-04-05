<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppNavbar from "@/components/AppNavbar.vue";

</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <AppNavbar />
    <section class="flex w-full items-start justify-center bg-[url('/images/squares-bg.webp')] dark:bg-[url('/images/dark-squares.jpg')] bg-cover bg-center bg-no-repeat">
        <div class="m-auto flex max-w-screen-xl grow flex-col items-center justify-start gap-6 py-20 md:gap-12 px-3 sm:px-8 lg:px-16 xl:px-32 relative">
            <AppLogoIcon class="size-16 fill-current text-black dark:text-white" />
            <div class="flex flex-1 flex-col items-center gap-6 text-center">
                <span class="inline-flex rounded-[64px] border text-center font-semibold transition-all duration-300 ease-in-out h-7 px-3 py-1 text-sm border-blue-300 bg-blue-50 text-blue-600 dark:border-blue-700 dark:bg-blue-900 dark:text-blue-300">Ride Share Application</span>
                <div class="flex max-w-lg flex-col gap-6">
                    <h3 class="text-4xl font-semibold text-slate-950 dark:text-white md:text-6xl">Welcome to <br> Ride Share App</h3>
                    <p class="text-lg font-normal leading-7 text-slate-500 dark:text-gray-300">Get a ride in minutes with our fast, reliable, and affordable ride-sharing service.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <a :href="route('trip.create')" aria-disabled="false"
                   class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[42px] min-w-[42px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 dark:bg-blue-600 dark:hover:bg-blue-800 dark:disabled:bg-gray-700 dark:disabled:text-gray-500">
                    <div>Create a Ride</div>
                </a>
                <a :href="route('trip.index')" aria-disabled="false"
                class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 px-2 text-blue-700 h-[42px] min-w-[42px] gap-2 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 dark:stroke-blue-400 dark:text-blue-400 dark:hover:stroke-blue-300 dark:hover:text-blue-300">
                <div>Find a Ride</div>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" class="size-6 stroke-inherit">
                    <path d="M11 16L15 12L11 8" stroke-linecap="round" stroke-linejoin="round"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                </svg>
                </a>
            </div>
        </div>
    </section>

</template>
