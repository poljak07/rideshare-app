<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppNavbar from '@/Components/AppNavbar.vue';
import TripMap from '@/Components/TripMap.vue';
import TripDetails from '@/Components/TripDetails.vue';
import TripActions from '@/Components/TripActions.vue';
import SearchForm from '@/Components/SearchForm.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({ trips: Object });
const tripsData = ref(props.trips.data);

watch(() => props.trips, (newTrips) => tripsData.value = newTrips.data);

const requestTrip = (tripId) => {
    router.post(route('trip.request', { trip: tripId }), {}, {
        preserveScroll: true,
        onSuccess: () => updateRequestStatus(tripId, 'Pending'),
    });
};

const cancelRequest = (tripId) => {
    router.delete(route('trip.cancel', { trip: tripId }), {
        preserveScroll: true,
        onSuccess: () => updateRequestStatus(tripId, null),
    });
};

const updateRequestStatus = (tripId, status) => {
    const trip = tripsData.value.find(t => t.id === tripId);
    if (trip) trip.requestStatus = status;
};

const goToPage = (url) => url && router.get(url);
const handleSearch = (form) => form.get(route('trip.search'), { preserveState: true });
</script>

<template>
    <Head title="Trips" />
    <AppNavbar />

    <div class="flex items-center justify-center gap-4 mb-6 flex-row">
        <SearchForm @search="handleSearch" />
    </div>

    <section class="flex flex-col gap-6 py-12 md:gap-16 2xl:py-16 max-w-screen-2xl m-auto w-full px-3 sm:px-8 lg:px-16 xl:px-32">
        <div v-for="trip in tripsData" :key="trip.id" class="flex flex-1 flex-col justify-center gap-6 overflow-hidden md:flex-row">
            <TripMap :trip="trip" :driverLocation="trip.driverLocation" />

            <div class="flex flex-1 flex-col items-start gap-3 md:flex-1 md:justify-between md:gap-52">
                <TripDetails :trip="trip" />

                <TripActions
                    :tripId="trip.id"
                    :requestStatus="trip.requestStatus"
                    :showBackButton="false"
                    @request="requestTrip"
                    @cancel="cancelRequest"
                >
                    <a
                        :href="route('trip.show', trip.id)"
                        class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 text-blue-700 h-[42px] min-w-[42px] gap-2 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 p-0 hidden min-[375px]:inline-flex"
                    >
                        <div>Read More</div>
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
                </TripActions>
            </div>
        </div>

        <Pagination
            :links="props.trips.links"
            @page-change="goToPage"
        />
    </section>
</template>
