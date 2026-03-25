<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number | string;
    sku: string;
    stock_quantity: number;
    image: string | null;
}

defineProps<{
    products: Product[];
}>();
</script>

<template>
    <Head title="E-pood" />

    <AppLayout>
        <div class="p-6">
            <h1 class="mb-6 text-3xl font-bold">E-pood</h1>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="product in products" :key="product.id" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <img
                        :src="`https://picsum.photos/seed/product-${product.id}/600/400`"
                        :alt="product.name"
                        class="h-48 w-full object-cover"
                    />

                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ product.name }}</h2>

                        <p class="mt-2 line-clamp-3 text-sm text-gray-600">
                            {{ product.description }}
                        </p>

                        <div class="mt-4 space-y-1">
                            <p class="text-lg font-bold">{{ product.price }} €</p>
                            <p class="text-sm text-gray-500">SKU: {{ product.sku }}</p>
                            <p class="text-sm text-gray-500">Laos: {{ product.stock_quantity }}</p>
                        </div>

                        <div class="mt-4 flex items-center gap-2">
                            <label class="text-sm text-gray-600">Kogus:</label>
                            <input type="number" min="1" :max="product.stock_quantity" value="1" class="w-20 rounded-md border px-2 py-1 text-sm" />
                        </div>

                        <button class="mt-4 w-full rounded-lg bg-black px-4 py-2 text-white transition hover:opacity-90">Lisa ostukorvi</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
