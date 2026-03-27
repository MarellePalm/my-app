<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
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

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
});

const total = computed(() => {
    return props.cart.reduce((sum, item) => {
        return sum + Number(item.price) * item.quantity;
    }, 0);
});

const payWithStripe = () => {
    form.clearErrors();

    router.post('/checkout/stripe', {
        first_name: form.first_name,
        last_name: form.last_name,
        email: form.email,
        phone: form.phone,
    });
};
</script>

<template>
    <Head title="Checkout" />

    <AppLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Kassa</h1>

                <Link href="/cart" class="rounded-lg border px-4 py-2 text-sm">
                    Tagasi ostukorvi
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-xl font-semibold">Kliendi andmed</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium">Eesnimi</label>
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="w-full rounded-lg border px-3 py-2"
                            />
                            <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-500">
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Perenimi</label>
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="w-full rounded-lg border px-3 py-2"
                            />
                            <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-500">
                                {{ form.errors.last_name }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full rounded-lg border px-3 py-2"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium">Telefon</label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="w-full rounded-lg border px-3 py-2"
                            />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-500">
                                {{ form.errors.phone }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-xl font-semibold">Tellimuse kokkuvõte</h2>

                    <div class="space-y-4">
                        <div
                            v-for="item in cart"
                            :key="item.id"
                            class="flex items-center justify-between border-b pb-3"
                        >
                            <div>
                                <p class="font-medium">{{ item.name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ item.quantity }} x {{ Number(item.price).toFixed(2) }} €
                                </p>
                            </div>

                            <p class="font-semibold">
                                {{ (Number(item.price) * item.quantity).toFixed(2) }} €
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <span class="text-lg font-semibold">Kokku:</span>
                            <span class="text-2xl font-bold">{{ total.toFixed(2) }} €</span>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="payWithStripe"
                        class="mt-6 w-full rounded-lg bg-indigo-600 px-4 py-3 font-semibold text-white transition hover:bg-indigo-700"
                        :disabled="form.processing"
                    >
                        Maksa Stripe'iga
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>