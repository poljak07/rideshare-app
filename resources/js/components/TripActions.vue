<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tripId: Number,
    requestStatus: String,
    showBackButton: {
        type: Boolean,
        default: true
    },
    isDriver: Boolean,
});

const emit = defineEmits(['request', 'cancel']);

const handleRequest = () => emit('request', props.tripId);
const handleCancel = () => emit('cancel', props.tripId);

const baseClasses = "group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed px-6 text-white h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex";
</script>

<template>
    <div class="flex gap-3">
        <div
            v-if="isDriver"
            :class="`${baseClasses} bg-purple-600 stroke-white hover:bg-purple-700 inline-flex`"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <div>You are the driver of this trip</div>
        </div>

        <button
            v-if="!requestStatus && !isDriver"
            @click="handleRequest"
            :class="`${baseClasses} bg-blue-700 stroke-white hover:bg-blue-950`"
        >
            <div>Book a Trip</div>
        </button>

        <button
            v-if="requestStatus === 'Pending' && !isDriver"
            @click="handleCancel"
            :class="`${baseClasses} bg-red-600 stroke-white hover:bg-red-700`"
        >
            <div>Cancel Trip Request</div>
        </button>

        <div
            v-if="requestStatus === 'Accepted' && !isDriver"
            :class="`${baseClasses} bg-green-600 stroke-white hover:bg-green-700`"
        >
            You are in! Driver accepted your request.
        </div>

        <div
            v-if="requestStatus === 'Rejected' && !isDriver"
            :class="`${baseClasses} bg-red-600 stroke-white hover:bg-red-700`"
        >
            Driver rejected your request.
        </div>

        <slot></slot>

        <a
            v-if="showBackButton"
            :href="route('trip.index')"
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
</template>
