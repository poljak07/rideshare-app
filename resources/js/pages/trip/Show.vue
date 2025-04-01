<script setup>

import MapComponent from '@/components/MapComponent.vue';

const props = defineProps({
    trip: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <!--   <div>
           <MapComponent :trip="trip" :driverLocation="trip.driverLocation" />
       </div> -->
    <section class="flex flex-col gap-3 py-16 md:gap-6 2xl:py-32 m-auto w-full px-3 sm:px-4 lg:px-6 xl:px-8 min-h-[80vh] items-center justify-center">
        <div class="flex flex-1 flex-col justify-center gap-3 overflow-hidden md:flex-row w-full max-w-screen-xl">
            <div class="flex h-[280px] w-full items-center justify-center overflow-hidden rounded-3xl bg-slate-100 md:h-auto md:flex-1">
                <MapComponent class="h-full w-full" :trip="trip" :driverLocation="trip.driverLocation" />
            </div>
            <div class="flex flex-1 flex-col items-start gap-3 md:flex-1 md:justify-between md:gap-12">
                <div class="flex flex-col gap-2 md:gap-3">
                    <p class="inline-flex items-center justify-start gap-2">
                        <span class="text-xs leading-none text-slate-400">Created {{ trip.created_at }}</span>
                        <span class="size-1.5 rounded-full bg-blue-700"></span>
                        <span class="text-xs leading-none text-slate-400">Last updated {{ trip.created_at }}</span>
                    </p>
                    <div class="flex flex-col gap-2 md:gap-3">
                        <h3 class="text-2xl font-semibold md:text-3xl">Trip from {{ trip.origin }} to {{ trip.destination_name }}</h3>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base">Driver: {{ trip.driver_name }}</p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base">Driver's origin: {{ trip.origin }}</p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base">Driver's current location: {{ trip.driverLocation }}</p>
                    </div>
                </div>
                <button type="button" aria-disabled="false"
                        class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 text-blue-700 h-[42px] min-w-[42px] gap-2 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 p-0">
                    <div>Go Back</div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" class="size-6 stroke-inherit">
                        <path d="M11 16L15 12L11 8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </button>
            </div>
        </div>
    </section>



</template>
