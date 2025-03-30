<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { Loader } from '@googlemaps/js-api-loader';
import { usePage } from '@inertiajs/vue3';

const form = useForm({
    place: '',
    startingplace: '',
    datetime: '',
    location: { lat: null, lng: null },
    startingLocation: { lat: null, lng: null },
});

const page = usePage();
const apiKey = page.props.googleMapsApiKey;

onMounted(async () => {
    const loader = new Loader({
        apiKey: apiKey,
        version: "weekly",
    });

    const Places = await loader.importLibrary('places');
    const center = { lat: 34.082298, lng: -82.284777 };
    const defaultBounds = {
        north: center.lat + 0.1,
        south: center.lat - 0.1,
        east: center.lng + 0.1,
        west: center.lng - 0.1,
    };

    // Common options for both autocomplete instances
    const options = {
        bounds: defaultBounds,
        types: ["establishment"],
        componentRestrictions: { country: "hr" },
        fields: ["address_components", "geometry", "icon", "name"],
        strictBounds: false,
    };

    // Autocomplete for destination input
    const destinationInput = document.getElementById("place");
    const destinationAutocomplete = new Places.Autocomplete(destinationInput, options);

    destinationAutocomplete.addListener('place_changed', () => {
        const place = destinationAutocomplete.getPlace();
        if (place?.name) {
            form.place = place.name;
            form.location = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            };
        }
    });

    // Autocomplete for starting point input
    const startingPointInput = document.getElementById("startingplace");
    const startingPointAutocomplete = new Places.Autocomplete(startingPointInput, options);

    startingPointAutocomplete.addListener('place_changed', () => {
        const place = startingPointAutocomplete.getPlace();
        if (place?.name) {
            form.startingplace = place.name;
            form.startingLocation = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            };
        }
    });
});

const submit = () => {
    form.post(route('trip.store'), {
        onFinish: () => form.reset('place', 'startingplace', 'datetime', 'location', 'startingLocation'),
    });
};
</script>

<template>
    <AuthBase title="Create a ride" description="Enter details below to create a ride!">
        <Head title="Create a ride" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="place">Where are you driving?</Label>
                    <Input
                        id="place"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="off"
                        v-model="form.place"
                        placeholder="Enter Destination"
                    />
                    <InputError :message="form.errors.place" />
                </div>

                <div class="grid gap-2">
                    <Label for="startingplace">Where are you starting?</Label>
                    <Input
                        id="startingplace"
                        type="text"
                        required
                        :tabindex="2"
                        autocomplete="off"
                        v-model="form.startingplace"
                        placeholder="Enter Starting Point"
                    />
                    <InputError :message="form.errors.startingplace" />
                </div>

            <!--   TODO: <div class="grid gap-2">
                    <Label for="datetime">When are you driving?</Label>
                    <Input
                        id="datetime"
                        type="datetime-local"
                        required
                        :tabindex="3"
                        v-model="form.datetime"
                    />
                    <InputError :message="form.errors.datetime" />
                </div> -->

                <Button type="submit" class="mt-2 w-full" tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Submit Ride
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
