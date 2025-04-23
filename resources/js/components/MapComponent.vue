<template>
    <div ref="mapContainer" className="map-container"></div>
</template>

<script setup>
import {onMounted, ref, watch} from 'vue';
import {Loader} from '@googlemaps/js-api-loader';
import {usePage} from '@inertiajs/vue3';

const props = defineProps({
    trip: {
        type: Object,
        required: true,
    },
    driverLocation: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const apiKey = page.props.googleMapsApiKey;

const mapContainer = ref(null);
let map = null;
let driverMarker = null;

onMounted(() => {
    if (!apiKey) {
        console.error('Google Maps API Key is missing or user is not authenticated.');
        return;
    }

    const {destination, destination_name} = props.trip;
    const {lat: destLat, lng: destLng} = destination;
    const {lat: driverLat, lng: driverLng} = props.trip.driverLocation;

    const loader = new Loader({
        apiKey: apiKey,
        version: 'weekly',
    });

    loader.load().then(() => {
        map = new google.maps.Map(mapContainer.value, {
            center: {lat: destLat, lng: destLng},
            zoom: 8,
        });

        const destinationMarker = new google.maps.Marker({
            position: {lat: destLat, lng: destLng},
            map: map,
            title: destination_name,
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });

         driverMarker = new google.maps.Marker({
            position: {lat: driverLat, lng: driverLng},
            map: map,
            title: 'Driver Location',
            icon: 'https://maps.gstatic.com/mapfiles/ms2/micons/cabs.png'
        });

        const destinationInfoWindow = new google.maps.InfoWindow({
            content: `<b class="text-black">${destination_name}</b><br> <b class="text-black">Latitude: ${destLat}, Longitude: ${destLng}</b>`,
        });

        const driverInfoWindow = new google.maps.InfoWindow({
            content: `<b class="text-black">Driver Location</b><br> <b class="text-black">Latitude: ${driverLat}, Longitude: ${driverLng}</b>`,
        });

        destinationMarker.addListener('click', () => {
            destinationInfoWindow.open(map, destinationMarker);
        });

        driverMarker.addListener('click', () => {
            driverInfoWindow.open(map, driverMarker);
        });
    });
});

watch(() => props.driverLocation, (newLocation) => {
    if (driverMarker && newLocation) {
        const {lat, lng} = newLocation;
        driverMarker.setPosition({lat, lng});
    }
}, {deep: true});

</script>


