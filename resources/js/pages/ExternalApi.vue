<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    apiData: {
        data: any[];
    };
}>();
</script>

<template>
    <div class="p-6">
        <Link href="/dashboard"> ← Tagasi algusesse</Link>
        <h1 class="mb-6 text-3xl font-bold">Andmed</h1>

        <div v-if="apiData.data.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="item in apiData.data"
                :key="item.id"
                class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
            >
                <img v-if="item.image" :src="item.image" :alt="item.title || item.name" class="h-56 w-full object-cover" />

                <div class="space-y-2 p-4">
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ item.title || item.name }}
                    </h2>

                    <p v-if="item.description" class="text-sm leading-6 text-gray-600">
                        {{ item.description }}
                    </p>

                    <p v-if="item.genre" class="text-sm text-gray-700"><span class="font-semibold">Žanr:</span> {{ item.genre }}</p>

                    <p v-if="item.release_year" class="text-sm text-gray-700"><span class="font-semibold">Aasta:</span> {{ item.release_year }}</p>
                </div>
            </div>
        </div>

        <p v-else class="text-gray-500">Andmeid ei leitud.</p>
    </div>
</template>
