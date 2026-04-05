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

const studies = ref<Study[]>([]);
const loading = ref(false);
const error = ref('');

const search = ref('');
const modality = ref('');
const bodyPart = ref('');
const sortBy = ref('');
const direction = ref('asc');
const limit = ref(10);

const fetchStudies = async () => {
    loading.value = true;
    error.value = '';

    try {
        const response = await axios.get<Study[]>('/radiology-studies', {
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
    limit.value = 10;
};
</script>

<template>
    <div class="mx-auto max-w-7xl p-6 py-4">
        <Link href="/dashboard" class="inline-flex items-center gap-2 rounded-md bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200">
            ←
        </Link>

        <h1 class="mb-6 text-3xl font-bold">Radioloogilised uuringud</h1>

         <Link
        href="/radiology-studies-page/create"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
    >
        Lisa uus uuring
    </Link>

        <div class="mb-6 grid grid-cols-1 gap-4 rounded-lg border bg-white p-4 md:grid-cols-2 lg:grid-cols-6">
            <div class="lg:col-span-2">
                <label class="mb-1 block text-sm font-medium">Otsi pealkirja järgi</label>
                <input v-model="search" type="text" placeholder="Näiteks: Põlve MRT" class="w-full rounded-md border px-3 py-2" />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Modaliteet</label>
                <select v-model="modality" class="w-full rounded-md border px-3 py-2">
                    <option value="">Kõik</option>
                    <option value="MRT">MRT</option>
                    <option value="KT">KT</option>
                    <option value="RÖNTGEN">RÖNTGEN</option>
                    <option value="ULTRAHELI">ULTRAHELI</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Kehaosa</label>
                <select v-model="bodyPart" class="w-full rounded-md border px-3 py-2">
                    <option value="">Kõik</option>
                    <option value="PÕLV">Põlv</option>
                    <option value="PEA">Pea</option>
                    <option value="RINDKERE">Rindkere</option>
                    <option value="KÕHT">Kõht</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Sorteeri</label>
                <select v-model="sortBy" class="w-full rounded-md border px-3 py-2">
                    <option value="">Vaikimisi</option>
                    <option value="title">Pealkiri</option>
                    <option value="duration_minutes">Kestus</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Suund</label>
                <select v-model="direction" class="w-full rounded-md border px-3 py-2">
                    <option value="asc">Kasvav</option>
                    <option value="desc">Kahanev</option>
                </select>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium">Mitu tulemust</label>
                <select v-model="limit" class="rounded-md border px-3 py-2">
                    <option :value="5">5</option>
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>

            <button @click="resetFilters" class="rounded-md bg-gray-200 px-4 py-2 text-sm hover:bg-gray-300">Lähtesta filtrid</button>
        </div>

        <div v-if="loading" class="rounded-md border bg-gray-50 p-4 text-gray-600">Laen uuringuid...</div>

        <div v-else-if="error" class="rounded-md border border-red-300 bg-red-50 p-4 text-red-600">
            {{ error }}
        </div>

        <div v-else-if="studies.length === 0" class="rounded-md border bg-gray-50 p-4 text-gray-600">Uuringuid ei leitud.</div>

        <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="study in studies" :key="study.id" class="rounded-xl border bg-white p-4 shadow-sm">
                <img v-if="study.image" :src="study.image" :alt="study.title" class="mb-4 h-48 w-full rounded-lg object-cover" />

                <h2 class="text-xl font-semibold">
                    {{ study.title }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">{{ study.modality }} • {{ study.body_part }}</p>

                <p class="mt-3 text-sm text-gray-700">
                    {{ study.description }}
                </p>

                <div class="mt-4 space-y-1 text-sm text-gray-600">
                    <p><strong>Kestus:</strong> {{ study.duration_minutes }} min</p>
                    <p>
                        <strong>Kontrastaine:</strong>
                        {{ study.contrast_needed ? 'Jah' : 'Ei' }}
                    </p>
                </div>
                <Link
                    :href="`/radiology-studies-page/${study.id}`"
                    class="mt-4 inline-block rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                >
                    Vaata lähemalt
                </Link>
            </div>
        </div>
    </div>
</template>
