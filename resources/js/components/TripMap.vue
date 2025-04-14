<script setup>
import MapComponent from '@/components/MapComponent.vue';

defineProps({
    trip: Object,
    driverLocation: Object
});
</script>

<template>
    <div class="flex h-[280px] w-full items-center justify-center overflow-hidden rounded-3xl bg-slate-100 md:h-auto md:flex-1">
        <MapComponent class="h-full w-full" :trip="trip" :driverLocation="driverLocation" />
    </div>
</template>
