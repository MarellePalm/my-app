<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, watch } from 'vue';

interface Study {
    id: number;
    title: string;
    image: string | null;
    description: string;
    modality: string;
    body_part: string;
    duration_minutes: number;
    contrast_needed: boolean;
}

interface ApiResponse {
    success: boolean;
    data: Study[];
}

const studies = ref<Study[]>([]);
const loading = ref(false);
const error = ref('');

const search = ref('');
const modality = ref('');
const bodyPart = ref('');
const sortBy = ref('');
const direction = ref('asc');
const limit = ref('');

const fetchStudies = async () => {
    loading.value = true;
    error.value = '';

    try {
        const response = await axios.get<ApiResponse>('/radiology-studies', {
            params: {
                search: search.value,
                modality: modality.value,
                body_part: bodyPart.value,
                sort_by: sortBy.value,
                direction: direction.value,
                limit: limit.value,
            },
        });

        studies.value = response.data.data;
    } catch (err) {
        error.value = 'Andmete laadimine ebaõnnestus.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchStudies();
});

watch([search, modality, bodyPart, sortBy, direction, limit], () => {
    fetchStudies();
});

const resetFilters = () => {
    search.value = '';
    modality.value = '';
    bodyPart.value = '';
    sortBy.value = '';
    direction.value = 'asc';
    limit.value = '';
};
const getImageUrl = (image: string | null) => {
    if (!image) return '';

    if (image.startsWith('http://') || image.startsWith('https://') || image.startsWith('/storage/')) {
        return image;
    }

    return `/storage/${image}`;
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Päis -->
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-3">
                    <Link
                        href="/dashboard"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 shadow-sm transition hover:bg-gray-100"
                    >
                        ←
                    </Link>

                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Radioloogilised uuringud</h1>
                        <p class="mt-1 text-sm text-gray-600 py-5">Sirvi, filtreeri ja halda radioloogiliste uuringute infot.</p>
                        <a
                            href="/api/radiology-studies"
                            
                            class="inline-block rounded-lg bg-blue-600 px-10 py-2 text-white hover:bg-blue-700"
                        >
                            Ava JSON API
                        </a>
                    </div>
                </div>

                <Link
                    href="/radiology-studies-page/create"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700"
                >
                    Lisa uus uuring
                </Link>
            </div>

            <!-- Filtrid -->
            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Otsing ja filtrid</h2>
                    <p class="text-sm text-gray-500">Kasuta filtreid, et leida sobiv uuring kiiremini.</p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                    <div class="xl:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Otsi pealkirja järgi </label>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Näiteks: Põlve MRT"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Modaliteet </label>
                        <select
                            v-model="modality"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Kõik</option>
                            <option value="MRT">MRT</option>
                            <option value="KT">KT</option>
                            <option value="RÖNTGEN">RÖNTGEN</option>
                            <option value="ULTRAHELI">ULTRAHELI</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Kehaosa </label>
                        <select
                            v-model="bodyPart"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Kõik</option>
                            <option value="PEA">PEA</option>
                            <option value="KAEL">KAEL</option>
                            <option value="RINDKERE">RINDKERE</option>
                            <option value="KÕHT">KÕHT</option>
                            <option value="VAAGEN">VAAGEN</option>
                            <option value="LÜLISAMMAS">LÜLISAMMAS</option>
                            <option value="ÜLAJÄSE">ÜLAJÄSE</option>
                            <option value="ALAJÄSE">ALAJÄSE</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Sorteeri </label>
                        <select
                            v-model="sortBy"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Vaikimisi</option>
                            <option value="title">Pealkiri</option>
                            <option value="duration_minutes">Kestus</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Suund </label>
                        <select
                            v-model="direction"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="asc">Kasvav</option>
                            <option value="desc">Kahanev</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-4 border-t border-gray-100 pt-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700"> Mitu tulemust </label>
                        <select
                            v-model="limit"
                            class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm transition outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Kõik</option>
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </div>

                    <button
                        @click="resetFilters"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        Lähtesta filtrid
                    </button>
                </div>
            </div>

            <!-- Tulemuste info -->
            <div v-if="!loading && !error" class="mb-4 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Leitud uuringuid:
                    <span class="font-semibold text-gray-900">{{ studies.length }}</span>
                </p>
            </div>

            <!-- Seisundid -->
            <div v-if="loading" class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm">Laen uuringuid...</div>

            <div v-else-if="error" class="rounded-xl border border-red-200 bg-red-50 p-6 text-sm text-red-700 shadow-sm">
                {{ error }}
            </div>

            <div v-else-if="studies.length === 0" class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm">
                Uuringuid ei leitud.
            </div>

            <!-- Kaardid -->
            <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="study in studies"
                    :key="study.id"
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div v-if="study.image" class="h-52 w-full overflow-hidden bg-gray-100">
                        <img v-if="study.image" :src="getImageUrl(study.image)" :alt="study.title" class="h-full w-full object-cover" />
                    </div>

                    <div v-else class="flex h-52 items-center justify-center bg-gray-100 text-sm text-gray-400">Pilti pole lisatud</div>

                    <div class="p-5">
                        <div class="mb-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                {{ study.modality }}
                            </span>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                {{ study.body_part }}
                            </span>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ study.title }}
                        </h2>

                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                            {{ study.description }}
                        </p>

                        <div class="mt-4 space-y-2 text-sm text-gray-700">
                            <p>
                                <span class="font-medium text-gray-900">Kestus:</span>
                                {{ study.duration_minutes }} min
                            </p>
                            <p>
                                <span class="font-medium text-gray-900">Kontrastaine:</span>
                                {{ study.contrast_needed ? 'Jah' : 'Ei' }}
                            </p>
                        </div>

                        <div class="mt-5 flex gap-3">
                            <Link
                                :href="`/radiology-studies-page/${study.id}`"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Vaata
                            </Link>

                            <Link
                                :href="`/radiology-studies-page/${study.id}/edit`"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                Muuda
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
