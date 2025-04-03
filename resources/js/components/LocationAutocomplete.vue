<script setup lang="ts">
import { ref, onMounted, defineProps, defineEmits } from 'vue';
import { Loader } from '@googlemaps/js-api-loader';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    id: String,
    label: String,
    placeholder: String,
    error: String,
    modelValue: String,
    center: {
        type: Object,
        default: () => ({ lat: 34.082298, lng: -82.284777 }),
    },
    autocompleteOptions: {
        type: Object,
        default: () => ({
            types: ["establishment"],
            componentRestrictions: { country: "hr" },
            fields: ["address_components", "geometry", "icon", "name"],
            strictBounds: false,
        }),
    },
});

const emit = defineEmits(['update:modelValue', 'place-selected']);

const inputRef = ref<HTMLInputElement | null>(null);
const page = usePage();
const apiKey = page.props.googleMapsApiKey;

onMounted(async () => {
    const loader = new Loader({
        apiKey,
        version: "weekly",
    });

    const Places = await loader.importLibrary('places');

    const defaultBounds = {
        north: props.center.lat + 0.1,
        south: props.center.lat - 0.1,
        east: props.center.lng + 0.1,
        west: props.center.lng - 0.1,
    };

    const options = {
        bounds: defaultBounds,
        ...props.autocompleteOptions,
    };

    if (inputRef.value) {
        const autocomplete = new Places.Autocomplete(inputRef.value, options);

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (place?.name) {
                emit('update:modelValue', place.name);
                emit('place-selected', {
                    name: place.name,
                    location: {
                        lat: place.geometry?.location?.lat(),
                        lng: place.geometry?.location?.lng(),
                    },
                });
            }
        });
    }
});
</script>

<template>
    <div class="grid gap-2">
        <Label :for="id">{{ label }}</Label>
        <Input
            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
            :id="id"
            ref="inputRef"
            type="text"
            required
            autocomplete="off"
            :placeholder="placeholder"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="$attrs"
        />
        <InputError v-if="error" :message="error" />
    </div>
</template>
