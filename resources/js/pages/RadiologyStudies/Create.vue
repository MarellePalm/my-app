<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'
import { Link, router } from '@inertiajs/vue3'

const loading = ref(false)
const error = ref('')
const success = ref('')

const form = reactive({
    title: '',
    image: '',
    description: '',
    modality: '',
    body_part: '',
    duration_minutes: 0,
    contrast_needed: false,
})

const submitForm = async () => {
    loading.value = true
    error.value = ''
    success.value = ''

    try {
        await axios.post('/radiology-studies', form)

        success.value = 'Uuring lisati edukalt.'

        form.title = ''
        form.image = ''
        form.description = ''
        form.modality = ''
        form.body_part = ''
        form.duration_minutes = 0
        form.contrast_needed = false

        setTimeout(() => {
            router.visit('/radiology-studies-page')
        }, 800)
    } catch (err: any) {
        if (err.response?.data?.message) {
            error.value = err.response.data.message
        } else {
            error.value = 'Uuringu lisamine ebaõnnestus.'
        }
        console.error(err)
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="mx-auto max-w-3xl p-6">
        <div class="mb-6 flex items-center justify-between">
            <Link
                href="/radiology-studies-page"
                class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline"
            >
                ← Tagasi uuringute juurde
            </Link>
        </div>

        <h1 class="mb-6 text-3xl font-bold">Lisa uus radioloogiline uuring</h1>

        <div v-if="success" class="mb-4 rounded-md border border-green-300 bg-green-50 p-4 text-green-700">
            {{ success }}
        </div>

        <div v-if="error" class="mb-4 rounded-md border border-red-300 bg-red-50 p-4 text-red-700">
            {{ error }}
        </div>

        <form @submit.prevent="submitForm" class="space-y-5 rounded-xl border bg-white p-6 shadow-sm">
            <div>
                <label class="mb-1 block text-sm font-medium">Pealkiri</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded-md border px-3 py-2"
                    placeholder="Näiteks: Põlve MRT"
                    required
                />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Pildi aadress</label>
                <input
                    v-model="form.image"
                    type="text"
                    class="w-full rounded-md border px-3 py-2"
                    placeholder="Näiteks: images/knee-mri.jpg"
                />
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Kirjeldus</label>
                <textarea
                    v-model="form.description"
                    class="w-full rounded-md border px-3 py-2"
                    rows="4"
                    placeholder="Sisesta uuringu kirjeldus"
                    required
                />
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">Modaliteet</label>
                    <select v-model="form.modality" class="w-full rounded-md border px-3 py-2" required>
                        <option value="" disabled>Vali modaliteet</option>
                        <option value="MRT">MRT</option>
                        <option value="KT">KT</option>
                        <option value="RÖNTGEN">RÖNTGEN</option>
                        <option value="ULTRAHELI">ULTRAHELI</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Kehapiirkond</label>
                    <select v-model="form.body_part" class="w-full rounded-md border px-3 py-2" required>
                        <option value="" disabled>Vali kehapiirkond</option>
                        <option value="PEA">Pea</option>
                        <option value="RINDKERE">Rindkere</option>
                        <option value="PÕLV">Põlv</option>
                        <option value="KÕHT">Kõht</option>
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
                    placeholder="Näiteks: 30"
                    required
                />
            </div>

            <div class="flex items-center gap-3">
                <input
                    id="contrast_needed"
                    v-model="form.contrast_needed"
                    type="checkbox"
                    class="h-4 w-4"
                />
                <label for="contrast_needed" class="text-sm font-medium">
                    Kontrastaine vajalik
                </label>
            </div>

            <button
                type="submit"
                :disabled="loading"
                class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
            >
                {{ loading ? 'Salvestan...' : 'Lisa uuring' }}
            </button>
        </form>
    </div>
</template>