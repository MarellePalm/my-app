<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    id: string;
}>();

const loading = ref(false);
const pageLoading = ref(false);
const error = ref('');
const success = ref('');
const image = ref<File | null>(null);

const form = reactive({
    title: '',
    image: '',
    description: '',
    modality: '',
    body_part: '',
    duration_minutes: 1,
    contrast_needed: false,
});

const getImageUrl = (imagePath: string | null) => {
    if (!imagePath) return '';

    if (
        imagePath.startsWith('http://') ||
        imagePath.startsWith('https://') ||
        imagePath.startsWith('/storage/')
    ) {
        return imagePath;
    }

    return `/storage/${imagePath.replace(/^\/+/, '')}`;
};

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        image.value = target.files[0];
    }
};

const fetchStudy = async () => {
    pageLoading.value = true;
    error.value = '';

    try {
        const response = await axios.get(`/radiology-studies/${props.id}`);
        const study = response.data.data;

        form.title = study.title;
        form.image = study.image ?? '';
        form.description = study.description;
        form.modality = study.modality;
        form.body_part = study.body_part;
        form.duration_minutes = study.duration_minutes;
        form.contrast_needed = !!study.contrast_needed;
    } catch (err) {
        error.value = 'Uuringu andmete laadimine ebaõnnestus.';
        console.error(err);
    } finally {
        pageLoading.value = false;
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
        formData.append('_method', 'PUT');

        if (image.value) {
            formData.append('image', image.value);
        }

        await axios.post(`/radiology-studies/${props.id}`, formData);

        success.value = 'Uuring uuendati edukalt.';

        setTimeout(() => {
            router.visit(`/radiology-studies-page/${props.id}`);
        }, 800);
    } catch (err: any) {
        error.value = 'Uuringu uuendamine ebaõnnestus.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchStudy();
});
</script>

<template>
    <div class="mx-auto max-w-3xl p-6">
        <div class="mb-6">
            <Link
                :href="`/radiology-studies-page/${props.id}`"
                class="text-sm text-blue-600 hover:underline"
            >
                ← Tagasi detailvaatesse
            </Link>
        </div>

        <h1 class="mb-6 text-3xl font-bold">Muuda uuringut</h1>

        <div v-if="pageLoading" class="rounded-md border bg-gray-50 p-4 text-gray-600">
            Laen andmeid...
        </div>

        <div v-else>
            <div v-if="success" class="mb-4 rounded-md border border-green-300 bg-green-50 p-4 text-green-700">
                {{ success }}
            </div>

            <div v-if="error" class="mb-4 rounded-md border border-red-300 bg-red-50 p-4 text-red-700">
                {{ error }}
            </div>

            <form @submit.prevent="submitForm" class="space-y-5 rounded-xl border bg-white p-6 shadow-sm">
                <div>
                    <label class="mb-1 block text-sm font-medium">Pealkiri</label>
                    <input v-model="form.title" type="text" class="w-full rounded-md border px-3 py-2" required />
                </div>

                <div v-if="form.image">
                    <label class="mb-1 block text-sm font-medium">Praegune pilt</label>
                    <div class="rounded-lg border bg-gray-50 p-3">
                        <img
                            :src="getImageUrl(form.image)"
                            :alt="form.title"
                            class="mx-auto max-h-[400px] w-full object-contain"
                        />
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Vali uus pilt</label>
                    <input
                        type="file"
                        accept="image/*"
                        @change="handleImageChange"
                        class="w-full rounded-md border px-3 py-2"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Kui uut pilti ei vali, jääb olemasolev pilt alles.
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Kirjeldus</label>
                    <textarea v-model="form.description" rows="4" class="w-full rounded-md border px-3 py-2" required />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Modaliteet</label>
                        <select v-model="form.modality" class="w-full rounded-md border px-3 py-2" required>
                            <option value="" disabled>Vali modality</option>
                            <option value="MRT">MRT</option>
                            <option value="KT">KT</option>
                            <option value="RÖNTGEN">RÖNTGEN</option>
                            <option value="ULTRAHELI">ULTRAHELI</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Kehaosa</label>
                        <select v-model="form.body_part" class="w-full rounded-md border px-3 py-2" required>
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
                    <label class="mb-1 block text-sm font-medium">Kestus minutites</label>
                    <input
                        v-model.number="form.duration_minutes"
                        type="number"
                        min="1"
                        class="w-full rounded-md border px-3 py-2"
                        required
                    />
                </div>

                <div class="flex items-center gap-3">
                    <input id="contrast_needed" v-model="form.contrast_needed" type="checkbox" class="h-4 w-4" />
                    <label for="contrast_needed" class="text-sm font-medium">
                        Kontrastaine vajalik
                    </label>
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ loading ? 'Salvestan...' : 'Salvesta muudatused' }}
                </button>
            </form>
        </div>
    </div>
</template>