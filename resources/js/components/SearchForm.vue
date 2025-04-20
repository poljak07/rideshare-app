<script setup>
import { useForm } from '@inertiajs/vue3';
import LocationAutocomplete from '@/components/LocationAutocomplete.vue';
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const form = useForm({
    destination: '',
    location: { lat: null, lng: null },
    startingplace: '',
    startingLocation: { lat: null, lng: null },
    datetime: null,
});

const emit = defineEmits(['search']);

const searchTrips = () => {
    emit('search', {
        startingplace: form.startingplace,
        destination: form.destination,
        datetime: form.datetime,
    });
};

const now = new Date()
const oneYearFromNow = new Date()
oneYearFromNow.setFullYear(now.getFullYear() + 1)

form.datetime = null

</script>

<template>
    <form class="flex items-end gap-4 mb-6" @submit.prevent="searchTrips">
        <div class="flex-1">
            <LocationAutocomplete
                id="startingplace"
                label="Where are you starting?"
                placeholder="Enter Starting Point"
                v-model="form.startingplace"
                :error="form.errors.startingplace"
                :tabindex="1"
                @place-selected="({ location }) => form.startingLocation = location"
            />
        </div>

        <div class="flex-1">
            <LocationAutocomplete
                id="destination"
                label="Where are you going?"
                placeholder="Enter Destination"
                v-model="form.destination"
                :error="form.errors.destination"
                :tabindex="2"
                @place-selected="({ location }) => form.location = location"
            />
        </div>

        <div class="flex-1 grid gap-2">
            <label for="datetime">When are you going?</label>
            <Datepicker
                v-model="form.datetime"
                :enable-time-picker="false"
                :min-date="now"
                :max-date="oneYearFromNow"
                placeholder="Pick a date"
                id="datetime"
                class="w-full"
            />
        </div>
        <div class="flex-none justify-center ">
            <button
                type="submit"
                class="px-6 py-2 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-900 transition-all h-[42px]"
            >
                Search
            </button>
        </div>
    </form>
</template>
