<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const goToShop = () => {
    router.get('/shop');
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 p-6">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg text-center">

            <!-- ICON -->
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-green-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            
            <h1 class="text-2xl font-bold text-gray-800">
                Makse õnnestus 🎉
            </h1>

            
            <p class="mt-2 text-gray-600">
                Aitäh ostu eest! Sinu tellimus on vastu võetud.
            </p>

            
            <div class="mt-6 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                <p>📦 Tellimus töödeldakse peagi</p>
                <p class="mt-1">📧 Kinnituskiri saadetakse emailile</p>
            </div>

            
            <button
                @click="goToShop"
                class="mt-6 w-full rounded-lg bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-700 transition"
            >
                Tagasi poodi
            </button>
        </div>
    </div>
</template>