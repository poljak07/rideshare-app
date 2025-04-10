<script setup>

import { ref, watch } from 'vue';
import MapComponent from '@/components/MapComponent.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppNavbar from "../../components/AppNavbar.vue";
import { router } from '@inertiajs/vue3';

const props = defineProps({
    trip: {
        type: Object,
        required: true,
    },
    userRequestStatus: {
        type: String,
        default: null,
    },
});

const requestStatus = ref(props.userRequestStatus);
const errorMessage = ref(null);

watch(
    () => props.userRequestStatus,
    (newValue) => {
        requestStatus.value = newValue;
    }
);

const requestTrip = (tripId) => {
    router.post(route('trip.request', { trip: tripId }), {}, {
        onSuccess: () => {
            console.log('Trip requested successfully!');
            requestStatus.value = 'Pending';
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const cancelRequest = (tripId) => {
    router.delete(route('trip.cancel', { trip: tripId }), {
        onSuccess: () => {
            console.log('Trip canceled successfully!');
            requestStatus.value = null;
            errorMessage.value = null;
        },
        onError: (errors) => {
            console.error(errors);
            errorMessage.value = errors?.message || 'An error occurred while canceling the trip.';
        }
    });
};

</script>

<template>


    <AppNavbar />

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
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">Driver: {{ trip.driver_name }}</p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">Driver's origin: {{ trip.origin }}</p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">Driver's current location: {{ trip.driverLocation }}</p>
                    </div>
                </div>
                <div v-if="errorMessage" class="text-red-600 font-semibold text-sm mt-2">
                    {{ errorMessage }}
                </div>
                <div class="flex gap-3">
                    <button
                        v-if="!requestStatus"
                        @click="requestTrip(trip.id)"
                        class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
                    >
                        <div>Book a Trip</div>
                    </button>

                    <button
                        v-if="requestStatus === 'Pending'"
                        @click="cancelRequest(trip.id)"
                        class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-red-600 stroke-white px-6 text-white hover:bg-red-700 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"                    >
                        <div>Cancel Trip Request</div>
                    </button>

                    <div v-if="requestStatus === 'Accepted'"
                         class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-green-600 stroke-white px-6 text-white hover:bg-green-700 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
                    >
                        You are in! Driver accepted your request.
                    </div>

                    <div v-if="requestStatus === 'Rejected'"
                         class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-red-600 stroke-white px-6 text-white hover:bg-red-700 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
                    >
                        Driver rejected your request.
                    </div>



                    <a
                        :href="route('trip.index')"
                        aria-disabled="false"
                        class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 text-blue-700 h-[42px] min-w-[42px] gap-2 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 p-0"
                    >
                        <div>Go Back</div>
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#1D4ED8"
                            stroke-width="1.5"
                            xmlns="http://www.w3.org/2000/svg"
                            class="size-6 stroke-inherit"
                        >
                            <path d="M11 16L15 12L11 8" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </a>

                </div>
            </div>
        </div>
    </section>



</template>
