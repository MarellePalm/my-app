<script setup lang="ts">
import MapView from '@/components/MapView.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { WeatherData, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const props = defineProps<{
    weather: WeatherData;
    city: string;
    markers: {
        id: number;
        name: string;
        lat: number;
        lng: number;
        description: string | null;
        added: string | null;
        edited: string | null;
    }[];
}>();

const searchCity = ref(props.city);
function searchWeather() {
    router.get('/dashboard', {
        city: searchCity.value,
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative flex flex-col gap-4 overflow-hidden rounded-xl border border-sidebar-border/70 p-6 dark:border-sidebar-border">
                    <div class="flex h-full justify-between p-6">
                        <img class="items-center" :src="`https://openweathermap.org/img/wn/${weather.weather[0].icon}@4x.png`" alt="" />
                        <div class="flex gap-0.5">
                            <h1 class="text-lg italic">
                                City: {{ weather.name }} <br />
                                {{ weather.weather[0].description }} <br />
                                Temperature: {{ weather.main.temp }} <br />
                                Wind: {{ weather.wind.speed }} m/s <br />
                                Humidity: {{ weather.main.humidity }}% <br />
                            </h1>
                        </div>
                    </div>
                    <form @submit.prevent="searchWeather" class="flex gap-2 p-6 pt-0">
                        <input v-model="searchCity" type="text" placeholder="Sisesta linn" class="w-full rounded border px-3 py-2" />
                        <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Otsi</button>
                    </form>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div>
                        <Link
                            href="/posts"
                            class="flex h-full min-h-[280px] flex-col items-center justify-center rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-white to-gray-50 text-center transition hover:scale-[1.01] hover:shadow-lg"
                        >
                            <span class="text-4xl font-extrabold tracking-wide">BLOGI</span>
                            <span class="mt-3 text-sm text-gray-500">Vaata ja halda postitusi</span>
                        </Link>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <a href="/shop">E-pood</a>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <MapView :markers="markers" />
            </div>
        </div>
    </AppLayout>
</template>
