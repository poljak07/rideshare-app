<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    model: '',
    color: '',
    licenseplate: '',
    year: '',
});

const submit = () => {
    form.post(route('car.store'), {
        onFinish: () => form.reset('model', 'color', 'licenseplate', 'year'),
    });
};
</script>

<template>
    <AuthBase title="Add a Car" description="Enter your details below to add a car">
        <Head title="Add a Car" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="model">Car Model</Label>
                    <Input id="model" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.model" placeholder="BMW 5 Series" />
                    <InputError :message="form.errors.model" />
                </div>

                <div class="grid gap-2">
                    <Label for="color">Color</Label>
                    <Input id="color" type="text" required :tabindex="2" v-model="form.color" placeholder="Navy Blue" />
                    <InputError :message="form.errors.color" />
                </div>

                <div class="grid gap-2">
                    <Label for="licenseplate">License Plate</Label>
                    <Input id="licenseplate" type="text" required :tabindex="2" v-model="form.licenseplate" placeholder="ZG-1000-DP" />
                    <InputError :message="form.errors.licenseplate" />
                </div>

                <div class="grid gap-2">
                    <Label for="year">Year</Label>
                    <Input id="year" type="number" required :tabindex="2" autocomplete="year" v-model="form.year" placeholder="2014" />
                    <InputError :message="form.errors.year" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Add a Car
                </Button>
            </div>

        </form>
    </AuthBase>
</template>
