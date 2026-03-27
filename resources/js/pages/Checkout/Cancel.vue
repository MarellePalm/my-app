<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const goToCheckout = () => {
    router.get('/checkout');
};

const goToShop = () => {
    router.get('/shop');
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 p-6">
        <div class="w-full max-w-md rounded-xl bg-white p-8 text-center shadow-lg">
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-red-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="3"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-gray-800">
                Makse katkestati
            </h1>

            <p class="mt-2 text-gray-600">
                Midagi hullu ei juhtunud. Sinu tooted jäid ostukorvi alles ja saad uuesti proovida.
            </p>


            <div class="mt-6 space-y-3">
                <button
                    @click="goToCheckout"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700"
                >
                    Tagasi kassasse
                </button>

                <button
                    @click="goToShop"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Tagasi poodi
                </button>
            </div>
        </div>
    </div>
</template>