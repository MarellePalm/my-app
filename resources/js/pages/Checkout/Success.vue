<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    order: {
        id: number
        order_number: number | string
        total: number | string
        payment_status: string
        paid_at: string | null
    }
}>()
</script>

<template>
    <Head title="Tellimus kinnitatud" />

    <AppLayout>
        <div class="flex min-h-[60vh] items-center justify-center p-6">
            <div class="w-full max-w-xl rounded-xl border bg-white p-8 shadow-sm text-center">

                <h1 class="text-2xl font-bold mb-4">
                    🎉 Aitäh tellimuse eest!
                </h1>

                <p class="mb-6 text-gray-600">
                    Sinu makse on edukalt vastu võetud.
                </p>

                <div class="text-left space-y-2 mb-6">
                    <p><strong>Tellimuse number:</strong> {{ order.order_number }}</p>
                    <p><strong>Summa:</strong> {{ order.total }} €</p>
                    
                    <p v-if="order.paid_at">
                        <strong>Tasutud:</strong> {{ order.paid_at }}
                    </p>
                </div>

                <Link
                    href="/shop"
                    class="inline-block rounded-lg bg-black px-6 py-2 text-white hover:opacity-90"
                >
                    Tagasi poodi
                </Link>
            </div>
        </div>
    </AppLayout>
</template>