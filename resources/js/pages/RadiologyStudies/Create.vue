<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { reactive, ref } from 'vue';

const loading = ref(false);
const error = ref('');
const success = ref('');

const form = reactive({
    title: '',
    description: '',
    modality: '',
    body_part: '',
    duration_minutes: 0,
    contrast_needed: false,
});

const image = ref<File | null>(null);

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        image.value = target.files[0];
    }
};

const submitForm = async () => {
    loading.value = true;
    error.value = '';
    success.value = '';

    try {
        const formData = new FormData();

        formData.append('title', form.title);
        formData.append('description', form.description);
        formData.append('modality', form.modality);
        formData.append('body_part', form.body_part);
        formData.append('duration_minutes', String(form.duration_minutes));
        formData.append('contrast_needed', form.contrast_needed ? '1' : '0');

        if (image.value) {
            formData.append('image', image.value);
        }

        await axios.post('/api/radiology-studies', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        success.value = 'Uuring lisati edukalt.';

        form.title = '';
        form.description = '';
        form.modality = '';
        form.body_part = '';
        form.duration_minutes = 0;
        form.contrast_needed = false;
        image.value = null;

        router.visit('/radiology-studies-page');
    } catch (err: any) {
        console.error(err);

        if (err.response?.data?.message) {
            error.value = err.response.data.message;
        } else {
            error.value = 'Uuringu lisamine ebaõnnestus.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <Link
                    href="/radiology-studies-page"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm hover:bg-gray-50"
                >
                    ← Tagasi uuringute juurde
                </Link>
            </div>

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Lisa uus radioloogiline uuring</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Täida vorm ja salvesta uus uuring koos pildiga.
                </p>
            </div>

            <div
                v-if="success"
                class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700"
            >
                {{ success }}
            </div>

            <div
                v-if="error"
                class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"
            >
                {{ error }}
            </div>

            <form @submit.prevent="submitForm" class="space-y-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Pealkiri</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        placeholder="Näiteks: Põlve MRT"
                        required
                    />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Pilt</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleImageChange"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Lubatud on pildifailid, näiteks JPG või PNG.
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Kirjeldus</label>
                    <textarea
                        v-model="form.description"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        rows="4"
                        placeholder="Sisesta uuringu kirjeldus"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Modaliteet</label>
                        <select
                            v-model="form.modality"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        >
                            <option value="" disabled>Vali modaliteet</option>
                            <option value="MRT">MRT</option>
                            <option value="KT">KT</option>
                            <option value="RÖNTGEN">RÖNTGEN</option>
                            <option value="ULTRAHELI">ULTRAHELI</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Kehapiirkond</label>
                        <select
                            v-model="form.body_part"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required
                        >
                            <option value="" disabled>Vali kehapiirkond</option>
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
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Kestus minutites</label>
                    <input
                        v-model.number="form.duration_minutes"
                        type="number"
                        min="1"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        placeholder="Näiteks: 30"
                        required
                    />
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3">
                    <input
                        id="contrast_needed"
                        v-model="form.contrast_needed"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300"
                    />
                    <label for="contrast_needed" class="text-sm font-medium text-gray-700">
                        Kontrastaine vajalik
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ loading ? 'Salvestan...' : 'Lisa uuring' }}
                    </button>

                    <Link
                        href="/radiology-studies-page"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tühista
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>