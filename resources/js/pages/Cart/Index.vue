<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

interface CartItem {
    id: number;
    name: string;
    price: number | string;
    image: string | null;
    quantity: number;
}

const props = defineProps<{
    cart: CartItem[];
}>();

const total = computed(() => {
    return props.cart.reduce((sum, item) => {
        return sum + Number(item.price) * item.quantity;
    }, 0);
});

const updateQuantity = (productId: number, quantity: number) => {
    router.post(`/cart/update/${productId}`, {
        quantity,
    }, {
        preserveScroll: true,
        replace: true,
    });
};

const removeFromCart = (productId: number) => {
    router.post(`/cart/remove/${productId}`, {}, {
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Ostukorv" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Ostukorv</h1>
                <Link href="/shop" class="rounded-lg border px-4 py-2 text-sm"> Tagasi poodi </Link>
            </div>

            <div v-if="cart.length === 0" class="rounded-xl border bg-white p-6 shadow-sm">
                <p class="text-gray-600">Ostukorv on tühi.</p>
            </div>

            <div v-else class="space-y-4">
                <div v-for="item in cart" :key="item.id" class="flex items-center gap-4 rounded-xl border bg-white p-4 shadow-sm">
                    <img :src="`https://picsum.photos/seed/product-${item.id}/600/400`" :alt="item.name" class="h-24 w-24 rounded-lg object-cover" />

                    <div class="flex-1">
                        <h2 class="text-lg font-semibold">{{ item.name }}</h2>
                        <div class="mt-2 flex items-center gap-2">
                            <label class="text-sm text-gray-500">Kogus:</label>
                            <input
                                :value="item.quantity"
                                type="number"
                                min="1"
                                class="w-20 rounded-md border px-2 py-1 text-sm"
                                @change="updateQuantity(item.id, Number(($event.target as HTMLInputElement).value))"
                            />
                        </div>
                        <p class="text-sm text-gray-500">Hind: {{ item.price }} €</p>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold">{{ (Number(item.price) * item.quantity).toFixed(2) }} €</p>

                        <button @click="removeFromCart(item.id)" class="mt-2 rounded-md bg-red-400 px-3 py-1 text-sm text-white">Eemalda</button>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold">Kokku:</span>
                        <span class="text-2xl font-bold">{{ total.toFixed(2) }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
