<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const showConfirmationModal = ref(false)
const selectedRequest = ref(null)
const newStatus = ref(null)
const oldStatus = ref(null)
const confirmationMessage = ref('')

const props = defineProps({
    trips: {
        type: Array,
        required: true,
        default: () => []
    },
    cars: {
        type: Array,
        required: true,
        default: () => []
    },
    tripRequests: {
        type: Array,
        required: true,
        default: () => []
    }
});

const handleStatusChange = (request, newStatusValue) => {
    if (newStatusValue === request.status) return

    selectedRequest.value = request
    newStatus.value = newStatusValue
    oldStatus.value = request.status
    confirmationMessage.value = `Are you sure you want to change this trip status to ${newStatusValue === 'Accepted' ? 'Accept' : 'Reject'}?`
    showConfirmationModal.value = true
}

const confirmStatusChange = async () => {
    try {
        await router.put(route('trip.statusUpdate', selectedRequest.value.trip.id), {
            status: newStatus.value
        });
        router.reload();
    } catch (error) {
        console.error('Error updating status:', error);
        selectedRequest.value.status = oldStatus.value;
    }

    showConfirmationModal.value = false;
}

const cancelStatusChange = () => {
    selectedRequest.value.status = oldStatus.value
    showConfirmationModal.value = false
}


console.log(props.trips);
</script>

<template>
    <Head title="Trips" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div v-if="showConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full">
                <p class="mb-4">{{ confirmationMessage }}</p>
                <div class="flex justify-end space-x-3">
                    <button @click="cancelStatusChange" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-700 rounded">
                        Cancel
                    </button>
                    <button @click="confirmStatusChange" class="px-4 py-2 text-sm bg-blue-600 text-white rounded">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col">
                    <h3 class="text-center font-bold p-2">My Trips</h3>
                    <div class="flex-1 overflow-y-auto p-2">
                        <ul>
                            <li v-for="trip in trips" :key="trip.id">
                                <a :href="route('trip.show', trip.id)">{{ trip.destination_name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="relative aspect-video overflow-hidden rounded-xl border-sidebar-border/70 dark:border-sidebar-border">
                        <h3 class="text-center font-bold">My Car</h3>
                        <li v-for="car in cars" :key="car.id">
                            <a :href="route('car.show', car.id)">{{ car.model }}, {{ car.year }}</a>
                        </li>
                    </div>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col items-center justify-center gap-2">
                    <a :href="route('trip.create')"
                       class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle
                text-sm font-semibold bg-black text-white stroke-white px-6 hover:bg-gray-800 gap-2
                dark:bg-white dark:text-black dark:hover:bg-gray-300">
                        Create New Trip
                    </a>
                    <a :href="route('car.create')"
                       class="group inline-flex items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle
                text-sm font-semibold bg-black text-white stroke-white px-6 hover:bg-gray-800 gap-2
                dark:bg-white dark:text-black dark:hover:bg-gray-300">
                        Add a New Car
                    </a>
                </div>
            </div>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col">
                    <h3 class="text-center font-bold p-2">Trip Requests</h3>
                    <div class="flex-1 overflow-y-auto p-2 space-y-2">
                        <div v-for="request in tripRequests" :key="request.id" class="flex items-center justify-between border-b pb-1">
                            <span>
                                {{ request.user.name }} requested your trip to {{ request.trip.destination_name }} on {{ request.created_at }}
                            </span>
                            <select
                                @change="handleStatusChange(request, $event.target.value)"
                                :value="request.status"
                                class="border rounded px-2 py-1 text-sm dark:bg-gray-800 dark:text-white"
                            >
                                <option value="Pending">Pending</option>
                                <option value="Accepted">Accept</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
