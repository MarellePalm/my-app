<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number | string;
    sku: string;
    stock_quantity: number;
    image: string | null;
}

const props = defineProps<{
    products: Product[];
}>();

const quantities = reactive<Record<number, number>>({});
props.products.forEach((product) => {
    quantities[product.id] = 1;
});

const addToCart = (productId: number) => {
    console.log('adding product', productId);

    router.post(
        `/cart/add/${productId}`,
        {
            quantity: quantities[productId],
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                console.log('added successfully');
            },
            onError: (errors) => {
                console.log('add to cart error', errors);
            },
        },
    );
};

const page = usePage();
const cartCount = computed(() => Number(page.props.cartCount ?? 0));
</script>

<template>
    <Head title="E-pood" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">E-pood</h1>

                <Link href="/cart" class="relative inline-flex items-center">
                    <span class="text-3xl">🛒</span>

                    <span
                        v-if="cartCount > 0"
                        class="absolute -top-2 -right-2 flex h-6 min-w-6 items-center justify-center rounded-full bg-red-500 px-1 text-xs font-bold text-white shadow"
                    >
                        {{ cartCount }}
                    </span>
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="product in products" :key="product.id" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <img :src="`https://picsum.photos/seed/product-${product.id}/600/400`" :alt="product.name" class="h-48 w-full object-cover" />

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
                            <input v-model="quantities[product.id]" type="number" min="1" :max="product.stock_quantity" />
                        </div>

                        <button
                            type="button"
                            @click="addToCart(product.id)"
                            class="mt-4 w-full rounded-lg bg-black px-4 py-2 text-white transition hover:opacity-90"
                        >
                            Lisa ostukorvi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
