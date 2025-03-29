<template>
    <div>
        <MapComponent :trip="trip" :driverLocation="trip.driverLocation" />
    </div>
</template>

<script setup>

import MapComponent from '../../components/MapComponent.vue';

const props = defineProps({
    trip: {
        type: Object,
        required: true,
    },
});

</script>
