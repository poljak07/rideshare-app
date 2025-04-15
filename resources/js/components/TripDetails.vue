<script setup>
defineProps({
    trip: Object,
    driverAddress: String,
    showFullInfo: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div class="flex flex-col gap-2 md:gap-3">
        <p class="inline-flex items-center justify-start gap-2">
            <span class="text-xs leading-none text-slate-400">Created {{ trip.created_at }}</span>
            <span class="size-1.5 rounded-full bg-blue-700"></span>
            <span class="text-xs leading-none text-slate-400">Last updated {{ trip.updated_at }}</span>
        </p>
        <div class="flex flex-col gap-2 md:gap-3">
            <h3 class="text-2xl font-semibold md:text-3xl">Trip from {{ trip.origin }} to {{ trip.destination_name }}</h3>
            <p class="text-lg text-green-600 font-bold md:text-xl">Price: {{ trip.price }} €</p>
            <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">Driver: {{ trip.driver_name }}</p>
            <p class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">Driver's origin: {{ trip.origin }}</p>
            
            <template v-if="trip.isDriver || trip.requestStatus === 'Accept'">
                <p v-if="showFullInfo" class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">
                    Driver's location: {{ driverAddress || 'Loading...' }}
                </p>
                <p v-else class="max-w-xs text-sm leading-tight text-gray-800 font-bold md:max-w-xl md:text-base dark:text-gray-400">
                    <span v-if="trip.driverLocation">
                        Driver Location: {{ trip.driverLocation.lat }}, {{ trip.driverLocation.lng }}
                    </span>
                    <span v-else>
                        Location not available
                    </span>
                </p>
            </template>
        </div>
    </div>
</template>
