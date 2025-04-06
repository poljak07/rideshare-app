<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import MapComponent from '@/components/MapComponent.vue';
import LocationAutocomplete from '@/components/LocationAutocomplete.vue';
import { useForm } from '@inertiajs/vue3';
import AppNavbar from '@/Components/AppNavbar.vue';

const form = useForm({
    place: '',
    location: { lat: null, lng: null },
    startingplace: '',
    startingLocation: { lat: null, lng: null },
});

const props = defineProps({
    trips: Object
});

const tripsData = ref(props.trips.data);

watch(() => props.trips, (newTrips) => {
    tripsData.value = newTrips.data;
});

const requestTrip = (tripId) => {
    router.post(route('trip.request', { trip: tripId }), {}, {
        preserveScroll: true,
        onSuccess: () => {
            const index = tripsData.value.findIndex(t => t.id === tripId);
            if (index !== -1) {
                tripsData.value[index] = {
                    ...tripsData.value[index],
                    already_requested: true
                };
            }
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const goToPage = (url) => {
    if (url) {
        router.get(url);
    }
};

const searchTrips = () => {
    form.get(route('trip.search'), {
        preserveState: true,
    });
};

console.log(props.trips.links);
</script>

<template>
    <Head title="Trips" />
    <AppNavbar />

    <div class="flex items-center justify-center gap-4 mb-6 flex-row">
        <form class="flex items-end gap-4 mb-6" @submit.prevent="searchTrips">
            <div class="flex-1">
                <LocationAutocomplete
                    id="startingplace"
                    label="Where are you starting?"
                    placeholder="Enter Starting Point"
                    v-model="form.startingplace"
                    :error="form.errors.startingplace"
                    :tabindex="1"
                    @place-selected="({ location }) => form.startingLocation = location"
                />
            </div>

            <div class="flex-1">
                <LocationAutocomplete
                    id="destination"
                    label="Where are you going?"
                    placeholder="Enter Destination"
                    v-model="form.place"
                    :error="form.errors.place"
                    :tabindex="2"
                    @place-selected="({ location }) => form.location = location"
                />
            </div>

            <div class="flex-none justify-center ">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-900 transition-all h-[42px]"
                >
                    Search
                </button>
            </div>
        </form>
    </div>


    <section
        class="flex flex-col gap-6 py-12 md:gap-16 2xl:py-16 max-w-screen-2xl m-auto w-full px-3 sm:px-8 lg:px-16 xl:px-32"
    >
        <div
            v-for="trip in props.trips.data"
            :key="trip.id"
            class="flex flex-1 flex-col justify-center gap-6 overflow-hidden md:flex-row"
        >
            <div
                class="flex h-[280px] w-full items-center justify-center overflow-hidden rounded-3xl bg-slate-100 md:h-auto md:flex-1 center"
            >
                <MapComponent
                    class="h-full w-full"
                    :trip="trip"
                    :driverLocation="trip.driver_location"
                />
            </div>
            <div class="flex flex-1 flex-col items-start gap-3 md:flex-1 md:justify-between md:gap-52">
                <div class="flex flex-col gap-3 md:gap-4">
                    <p class="inline-flex items-center justify-start gap-2">
                    <span class="text-xs leading-none text-slate-400">
                        Created {{ trip.created_at }}
                    </span>
                        <span class="size-1.5 rounded-full bg-blue-700"></span>
                        <span class="text-xs leading-none text-slate-400">
                        Last updated {{ trip.updated_at }}
                    </span>
                    </p>
                    <div class="flex flex-col gap-2 md:gap-4">
                        <h3 class="text-2xl font-semibold md:text-3xl">
                            Trip from {{ trip.origin }} to {{ trip.destination_name }}
                        </h3>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">
                            Driver: {{ trip.driver_name }}
                        </p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">
                            Driver's origin: {{ trip.origin }}
                        </p>
                        <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">
                            <span v-if="trip.driver_location">
                                {{ trip.driver_location.lat }}, {{ trip.driver_location.lng }}
                            </span>
                            <span v-else>
                                Location not available
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button
                        @click="requestTrip(trip.id)"
                        :disabled="trip.alreadyRequested"
                        class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
                    >
                        <div>{{ trip.alreadyRequested ? 'You already requested this Trip' : 'Book a Trip' }}</div>
                    </button>
                    <a
                        :href="route('trip.show', trip.id)"
                        aria-disabled="false"
                        class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 text-blue-700 h-[42px] min-w-[42px] gap-2 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 p-0"
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

                </div>
            </div>

        </div>
        <div class="flex justify-center space-x-2 mt-6">
            <button
                v-for="link in props.trips.links"
                :key="link.label"
                @click="goToPage(link.url)"
                v-html="link.label"
                class="px-4 py-2 border rounded"
                :class="{ 'bg-blue-600 text-white': link.active, 'text-gray-600': !link.url }"
                :disabled="!link.url"
            />
        </div>
    </section>
</template>
