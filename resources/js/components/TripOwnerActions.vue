<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    trip: Object,
});

const emit = defineEmits(['refresh']);


// TODO: Start & Delete Trip Route

const startTrip = () => {
    router.put(route('trip.start', { trip: props.trip.id }), {}, {
        onSuccess: () => emit('refresh'),
    });
};

const deleteTrip = () => {
    if (confirm('Are you sure you want to delete this trip?')) {
        router.delete(route('trip.destroy', { trip: props.trip.id }));
    }
};

const updateStatus = (passengerId, status) => {
    router.put(route('trip.statusUpdate', {
        trip: props.trip.id,
        passenger: passengerId
    }), { status }, {
        onSuccess: () => emit('refresh'),
    });
};
</script>

<template>
    <div class="mt-12 space-y-8">
        <div class="grid lg:grid-cols-2 gap-8 items-start">
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Trip Management</h2>

                <div class="flex flex-col gap-4">
                    <button
                        v-if="!trip.is_started"
                        @click="startTrip"
                        class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                        Start Trip
                    </button>

                    <div
                        v-else
                        class="flex items-center gap-2 bg-emerald-600 text-white px-5 py-3 rounded-xl"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                        </svg>

                        Trip is started! Have a nice ride!
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            @click="deleteTrip"
                            class="flex-1 flex items-center gap-2 bg-rose-100 hover:bg-rose-200 text-rose-700 px-5 py-3 rounded-xl transition-all"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Delete Trip
                        </button>

                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:pl-8 ">
                <h3 class="text-lg font-semibold text-gray-900">
                    Passenger Requests
                    <span class="ml-2 bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                        {{ trip.passengers.length }}
                    </span>
                </h3>

                <div v-if="trip.passengers.length === 0"
                     class="p-6 bg-white rounded-xl border border-dashed border-gray-200 text-center text-gray-500">
                    No pending requests
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="passenger in trip.passengers"
                        :key="passenger.id"
                        class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100"
                    >
                        <div class="flex items-center justify-between">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-3">
                                    <span class="font-medium text-gray-900">{{ passenger.name }}</span>
                                    <span
                                        class="text-sm px-2 py-1 rounded-full capitalize"
                                        :class="{
                                            'bg-amber-100 text-amber-700': passenger.status === 'Pending',
                                            'bg-emerald-100 text-emerald-700': passenger.status === 'Accepted',
                                            'bg-rose-100 text-rose-700': passenger.status === 'Rejected',
                                        }"
                                    >
                                        {{ passenger.status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Requested {{ passenger.created_at }}
                                </p>
                            </div>

                            <div v-if="passenger.status === 'Pending'" class="flex gap-3">
                                <button
                                    @click="updateStatus(passenger.id, 'Accepted')"
                                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Accept
                                </button>
                                <button
                                    @click="updateStatus(passenger.id, 'Rejected')"
                                    class="flex items-center gap-1.5 text-sm px-4 py-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 transition-colors"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
