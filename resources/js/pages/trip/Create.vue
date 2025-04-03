<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import LocationAutocomplete from '@/components/LocationAutocomplete.vue';

const form = useForm({
    place: '',
    startingplace: '',
    datetime: '',
    location: { lat: null, lng: null },
    startingLocation: { lat: null, lng: null },
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
                <LocationAutocomplete
                    id="place"
                    label="Where are you driving?"
                    placeholder="Enter Destination"
                    v-model="form.place"
                    :error="form.errors.place"
                    autofocus
                    :tabindex="1"
                    @place-selected="({ location }) => form.location = location"
                />

                <LocationAutocomplete
                    id="startingplace"
                    label="Where are you starting?"
                    placeholder="Enter Starting Point"
                    v-model="form.startingplace"
                    :error="form.errors.startingplace"
                    :tabindex="2"
                    @place-selected="({ location }) => form.startingLocation = location"
                />
                <Button type="submit" class="mt-2 w-full" tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Submit Ride
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
