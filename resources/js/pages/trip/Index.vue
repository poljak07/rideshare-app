<script setup>
import { ref, watch, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppNavbar from '@/Components/AppNavbar.vue';
import TripMap from '@/Components/TripMap.vue';
import TripDetails from '@/Components/TripDetails.vue';
import TripActions from '@/Components/TripActions.vue';
import SearchForm from '@/Components/SearchForm.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({ trips: Object });
const tripsData = ref([]);

const showMyTrips = ref(true);

const filteredTrips = computed(() => {
    if (showMyTrips.value) return tripsData.value;

    return tripsData.value.filter(
        trip => !(trip.requestStatus === 'Accepted' || trip.isDriver)
    );
});

const sortTrips = (trips) => {
    return [...trips].sort((a, b) => {
        const aIsMine = a.requestStatus === 'Accepted' || a.isDriver;
        const bIsMine = b.requestStatus === 'Accepted' || b.isDriver;

        return (bIsMine ? 1 : 0) - (aIsMine ? 1 : 0);
    });
};

watch(() => props.trips, (newTrips) => {
    tripsData.value = sortTrips(newTrips.data);
}, { immediate: true });

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
    <div>
        <Head title="Trips" />
        <AppNavbar />

        <div class="flex items-center justify-center gap-4 mb-6 flex-row">
            <SearchForm
                @search="handleSearch"
                :startingplace="$page.props.startingplace"
                :destination="$page.props.destination"
                :datetime="$page.props.datetime"
            />
            <div class="flex justify-end px-4 sm:px-6 lg:px-8">
                <label class="inline-flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <input type="checkbox" v-model="showMyTrips" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" />
                    <span>Show My Trips</span>
                </label>
            </div>
        </div>



        <section class="space-y-6 py-12 h- max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                v-for="trip in filteredTrips"
                :key="trip.id"
                class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm transition-all duration-200 ease-out"
                :class="{
                    'border-l-4 border-emerald-500': trip.requestStatus === 'Accepted' || trip.isDriver,
                    'hover:shadow-md hover:border-gray-300': !(trip.requestStatus === 'Accepted' || trip.isDriver),
                    'border-l-4 border-transparent': !(trip.requestStatus === 'Accepted' || trip.isDriver)
                }"
            >
                <div
                    v-if="trip.requestStatus === 'Accepted' || trip.isDriver"
                    class="absolute -top-3 right-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Your Trip</span>
                </div>

                <div class="flex flex-col md:flex-row gap-6 p-6">
                    <TripMap :trip="trip" :driverLocation="trip.driverLocation" class="md:w-1/2" />

                    <div class="flex flex-col justify-between md:w-1/2">
                        <TripDetails :trip="trip" />

                        <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <TripActions
                                :tripId="trip.id"
                                :requestStatus="trip.requestStatus"
                                :showBackButton="false"
                                :isDriver="trip.isDriver"
                                @request="requestTrip"
                                @cancel="cancelRequest"
                            >
                                <a
                                    :href="route('trip.show', trip.id)"
                                    class="inline-flex items-center text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium transition-colors"
                                >
                                    <span>View Details</span>
                                    <svg
                                        class="w-5 h-5 ml-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                                        />
                                    </svg>
                                </a>
                            </TripActions>
                        </div>
                    </div>
                </div>
            </div>

            <Pagination
                :links="props.trips.links"
                @page-change="goToPage"
                class="mt-8"
            />
        </section>
    </div>
</template>
