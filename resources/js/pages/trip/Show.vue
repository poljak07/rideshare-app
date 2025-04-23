<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';
import AppNavbar from "../../components/AppNavbar.vue";
import TripMap from '@/Components/TripMap.vue';
import TripDetails from '@/Components/TripDetails.vue';
import TripActions from '@/Components/TripActions.vue';
import TripOwnerActions from '@/Components/TripOwnerActions.vue';

const props = defineProps({
    trip: Object,
    userRequestStatus: String,
});

let locationInterval = null;

const updateDriverLocation = async (position) => {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    try {
        await fetch(route('trip.updateLocation', { trip: props.trip.id }), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                lat,
                lng
            }),
        });

        router.reload();

    } catch (error) {
        console.error("Location update failed:", error);
    }
};

const startLocationTracking = () => {
    if (!props.trip.isDriver || !navigator.geolocation) return;

    locationInterval = setInterval(() => {
        navigator.geolocation.getCurrentPosition(updateDriverLocation, (err) => {
            console.error("Geolocation error:", err);
        });
    }, 10000);
};

onMounted(startLocationTracking);
onUnmounted(() => clearInterval(locationInterval));

const requestStatus = ref(props.userRequestStatus);
const errorMessage = ref(null);
const driverAddress = ref(null);

const fetchDriverAddress = async () => {
    if (!props.trip.driverLocation) return;

    try {
        const response = await fetch(`/reverse-geocode?lat=${props.trip.driverLocation.lat}&lng=${props.trip.driverLocation.lng}`);
        const data = await response.json();
        driverAddress.value = data.address;
    } catch (error) {
        driverAddress.value = 'Unable to fetch location';
    }
};

watch(() => props.userRequestStatus, (newValue) => requestStatus.value = newValue);
watch(() => props.trip.driverLocation, fetchDriverAddress, { immediate: true });

const requestTrip = (tripId) => {
    router.post(route('trip.request', { trip: tripId }), {}, {
        onSuccess: () => requestStatus.value = 'Pending',
        onError: (errors) => console.error(errors)
    });
};

const refresh = () => {
    router.reload();
};

const cancelRequest = (tripId) => {
    router.delete(route('trip.cancel', { trip: tripId }), {
        onSuccess: () => {
            requestStatus.value = null;
            errorMessage.value = null;
        },
        onError: (errors) => errorMessage.value = errors?.message || 'An error occurred'
    });
};
</script>

<template>
    <AppNavbar />
    <section class="flex flex-col gap-3 py-16 md:gap-6 2xl:py-32 m-auto w-full px-3 sm:px-4 lg:px-6 xl:px-8 min-h-[80vh] items-center justify-center">
        <div class="flex flex-1 flex-col justify-center gap-3 overflow-hidden md:flex-row w-full max-w-screen-xl">
            <TripMap :trip="trip" :driverLocation="trip.driverLocation" />

            <div class="flex flex-1 flex-col items-start gap-3 md:flex-1 md:justify-between md:gap-12">
                <TripDetails
                    :trip="trip"
                    :driverAddress="driverAddress"
                    :requestStatus="requestStatus"
                    showFullInfo />

                <div v-if="errorMessage" class="text-red-600 font-semibold text-sm mt-2">
                    {{ errorMessage }}
                </div>

                <TripActions
                    :tripId="trip.id"
                    :requestStatus="requestStatus"
                    :isDriver="trip.isDriver"
                    @request="requestTrip"
                    @cancel="cancelRequest"
                />
            </div>

        </div>
        <TripOwnerActions
            v-if="trip.isDriver"
            :trip="trip"
            @refresh="refresh"
        />
    </section>



</template>
