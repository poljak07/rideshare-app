<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tripId: Number,
    requestStatus: String,
    showBackButton: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['request', 'cancel']);

const handleRequest = () => emit('request', props.tripId);
const handleCancel = () => emit('cancel', props.tripId);
</script>

<template>
    <div class="flex gap-3">
        <button
            v-if="!requestStatus"
            @click="handleRequest"
            class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
        >
            <div>Book a Trip</div>
        </button>

        <button
            v-if="requestStatus === 'Pending'"
            @click="handleCancel"
            class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-red-600 stroke-white px-6 text-white hover:bg-red-700 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex"
        >
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
