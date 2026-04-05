<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { Link, router } from '@inertiajs/vue3'

interface Study {
    id: number
    title: string
    image: string | null
    description: string
    modality: string
    body_part: string
    duration_minutes: number
    contrast_needed: boolean
}

const props = defineProps<{
    id: string
}>()

const study = ref<Study | null>(null)
const loading = ref(false)
const error = ref('')

const fetchStudy = async () => {
    loading.value = true
    error.value = ''

    try {
        const response = await axios.get<Study>(`/radiology-studies/${props.id}`)
        study.value = response.data.data
    } catch (err) {
        error.value = 'Uuringu laadimine ebaõnnestus.'
        console.error(err)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchStudy()
})

const deleteStudy = async () => {
    const confirmed = confirm('Kas oled kindel, et soovid selle uuringu kustutada?')

    if (!confirmed) return

    try {
        await axios.delete(`/radiology-studies/${props.id}`)
        router.visit('/radiology-studies-page')
    } catch (err) {
        error.value = 'Uuringu kustutamine ebaõnnestus.'
        console.error(err)
    }
}
</script>

<template>
    <div class="mx-auto max-w-4xl p-6">
        <div class="mb-6 flex items-center justify-between">
    <Link
        href="/radiology-studies-page"
        class="text-sm text-blue-600 hover:underline"
    >
        ← Tagasi uuringute juurde
    </Link>

    <div class="flex gap-2">
        <Link
            :href="`/radiology-studies-page/${props.id}/edit`"
            class="rounded-md bg-yellow-500 px-4 py-2 text-sm text-white hover:bg-yellow-600"
        >
            Muuda
        </Link>

        <button
            @click="deleteStudy"
            class="rounded-md bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700"
        >
            Kustuta
        </button>
    </div>
</div>

        <div v-if="loading" class="rounded-md border bg-gray-50 p-4 text-gray-600">
            Laen uuringut...
        </div>

        <div v-else-if="error" class="rounded-md border border-red-300 bg-red-50 p-4 text-red-600">
            {{ error }}
        </div>

        <div v-else-if="study" class="rounded-xl border bg-white p-6 shadow-sm">
            <img
                v-if="study.image"
                :src="`/storage/${study.image}`"
                :alt="study.title"
                class="mx-auto max-h-[500px] w-full object-contain"
            />

            <h1 class="text-3xl font-bold">
                {{ study.title }}
            </h1>

            <p class="mt-2 text-gray-600">
                {{ study.modality }} • {{ study.body_part }}
            </p>

            <p class="mt-6 text-base leading-7 text-gray-700">
                {{ study.description }}
            </p>

            <div class="mt-6 grid grid-cols-1 gap-4 rounded-lg bg-gray-50 p-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Modaliteet</p>
                    <p class="font-medium">{{ study.modality }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kehaosa</p>
                    <p class="font-medium">{{ study.body_part }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kestus</p>
                    <p class="font-medium">{{ study.duration_minutes }} min</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Kontrastaine</p>
                    <p class="font-medium">
                        {{ study.contrast_needed ? 'Jah' : 'Ei' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>