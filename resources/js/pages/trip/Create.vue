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
    seats: '',
    price: '',
    location: { lat: null, lng: null },
    startingLocation: { lat: null, lng: null },
});

const submit = () => {
    form.post(route('trip.store'), {
        onFinish: () => form.reset('place', 'startingplace', 'seats', 'datetime', 'location', 'startingLocation', 'price'),
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

                <div class="grid grid-cols-2 gap-4">

                    <div class="grid gap-2">
                        <Label for="seats">Available Seats</Label>
                        <input
                            id="seats"
                            type="number"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            required
                            v-model.number="form.seats"
                            placeholder="3" />
                        <InputError :message="form.errors.seats" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="price">Price</Label>
                        <input
                            id="price"
                            type="number"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            required
                            v-model.number="form.price"
                            placeholder="20€" />
                        <InputError :message="form.errors.price" />
                    </div>
                </div>
                <Button type="submit" class="mt-2 w-full" tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Submit Ride
                </Button>
            </div>
        </form>

    </AuthBase>
</template>
