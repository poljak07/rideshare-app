<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';

const props = defineProps<{
    trips: Array<{ id: number; destination_name: string }>;
    cars: Array<{ id: number; model:string; year: number }>;
}>();


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

console.log(props.trips);
</script>

<template>
    <Head title="Trips" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <h3 class="text-center font-bold">My Trips</h3>
                    <div class="relative aspect-video overflow-hidden rounded-xl  border-sidebar-border/70 dark:border-sidebar-border">
                        <li v-for="trip in trips" :key="trip.id">
                            <a :href="route('trip.show', trip.id)">{{ trip.destination_name }}</a>
                        </li>
                    </div>

                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="relative aspect-video overflow-hidden rounded-xl  border-sidebar-border/70 dark:border-sidebar-border">
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
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
