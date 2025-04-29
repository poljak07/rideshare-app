<script setup lang="ts">
import {Head, Link, router} from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppNavbar from "@/components/AppNavbar.vue";
import '@vuepic/vue-datepicker/dist/main.css'
import SearchForm from "@/components/SearchForm.vue";

const handleSearch = (form) => {
    router.get(route('trip.search'), {
        startingplace: form.startingplace,
        destination: form.destination,
        datetime: form.datetime,
    }, {
        preserveState: true,
        replace: true,
    });
};

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
                <span class="inline-flex rounded-[64px] border text-center font-semibold transition-all duration-300 ease-in-out h-7 px-3 py-1 text-sm border-blue-300 bg-blue-50 text-blue-600 dark:border-blue-700 dark:bg-blue-900 dark:text-blue-300">University Project</span>
                <div class="flex max-w-lg flex-col gap-6">
                    <h3 class="text-4xl font-semibold text-slate-950 dark:text-white md:text-6xl">Ride Share App</h3>
                    <p class="text-lg font-normal leading-7 text-slate-500 dark:text-gray-300">Get a ride in minutes</p>
                </div>
            </div>

            <SearchForm
                @search="handleSearch"
                :startingplace="$page.props.startingplace"
                :destination="$page.props.destination"
                :datetime="$page.props.datetime"
            />

            <!-- Navigation Links -->
            <div class="flex justify-center mt-6 space-x-6 text-sm font-medium text-gray-800">
                <a :href="route('trip.my')" class="hover:underline pr-4 border-r border-gray-300">My Trips</a>
                <a :href="route('trip.create')" class="hover:underline pr-4 border-r border-gray-300">Create a Trip</a>
                <a href="#" class="hover:underline pr-4 border-r border-gray-300">How does this work?</a>
                <a href="#" class="hover:underline">FAQ</a>
            </div>



        </div>


    </section>

</template>
