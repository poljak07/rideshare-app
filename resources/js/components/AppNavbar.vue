<script setup>
import AppLogoIcon from '@/Components/AppLogoIcon.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const { props: { auth } } = usePage();

const isOpen = ref(false)

function toggleDropdown() {
    isOpen.value = !isOpen.value
}

function logout() {
    router.post(route('logout'))
}
</script>

<template>
    <div class="max-w-screen-2xl m-auto w-full px-3 sm:px-8 lg:px-16 xl:px-32 flex items-center justify-between py-4">
        <div class="flex items-center justify-start gap-2 min-[375px]:gap-4 lg:gap-0 2xl:flex-1">
            <button type="button" aria-disabled="false"
                    class="group inline-flex items-center justify-center whitespace-nowrap align-middle font-semibold transition-all duration-300 ease-in-out disabled:cursor-not-allowed stroke-blue-700 text-blue-700 p-0 disabled:stroke-slate-400 disabled:text-slate-400 hover:stroke-blue-950 hover:text-blue-950 h-6 min-w-[24px] gap-1.5 rounded-md text-xs lg:hidden"
                    aria-label="Menu">
                <div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stroke-inherit">
                        <path d="M4 6H20"></path>
                        <path d="M4 12H20"></path>
                        <path d="M4 18H20"></path>
                    </svg>
                </div>
            </button>
            <AppLogoIcon class="w-10 h-10"/>
        </div>
        <div class="flex justify-end gap-2 2xl:flex-1">
            <div class="flex flex-1 items-center justify-end gap-4" v-if="$page.props.auth.user">
                <div class="relative flex items-center gap-2">
                    <!-- Avatar -->
                    <div class="box-content flex items-center justify-center overflow-hidden rounded-full size-8 border-white drop-shadow-md border-2">
                        <img src="https://avatar.iran.liara.run/public" alt="" class="aspect-square">
                    </div>

                    <!-- User Label -->
                    <span class="hidden text-sm font-semibold md:inline">{{ $page.props.auth.user.name }}</span>

                    <!-- Dropdown Button -->
                    <button @click="toggleDropdown"
                            type="button"
                            aria-label="Toggle Menu"
                            class="group items-center justify-center transition-all duration-300 ease-in-out stroke-blue-700 text-blue-700 p-1 hover:stroke-blue-950 hover:text-blue-950 rounded-md text-xs hidden md:inline-flex">
                        <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"
                             stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5.83325 8.33325L9.99992 12.4999L14.1666 8.33325"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div v-if="isOpen"
                         class="absolute top-full right-0 mt-2 w-40 min-w-max bg-white border border-gray-200 rounded-md shadow-lg z-50 ">
                        <a :href="route('profile.settings')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                        <form method="POST" :action="route('logout')" @submit.prevent="logout">
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div v-else class="flex gap-2">
                <a :href="route('register')" aria-disabled="false"
                   class="group gap-6 items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex">
                    <div>Register</div>
                </a>
                <a :href="route('login')" aria-disabled="false"
                   class="group items-center justify-center whitespace-nowrap rounded-lg py-2 align-middle text-sm font-semibold leading-none transition-all duration-300 ease-in-out disabled:cursor-not-allowed bg-blue-700 stroke-white px-6 text-white hover:bg-blue-950 h-[38px] min-w-[38px] gap-2 disabled:bg-slate-100 disabled:stroke-slate-400 disabled:text-slate-400 disabled:hover:bg-slate-100 hidden min-[375px]:inline-flex">
                    <div>Log In</div>
                </a>
            </div>
        </div>
    </div>

</template>
