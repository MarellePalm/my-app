<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';

const props = defineProps<{
  post: {
    id: number;
    title: string;
    content: string;
    author: string;
    published: boolean;
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Post view',
    href: `/posts/${props.post.id}`, 
  },
];

</script>

<template>
    <Head title="View Post"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="mx-auto h-full w-full max-w-2xl p-4">
                <h3 class="text-lg font-medium">Post View</h3>

            </div>
        </div>
    </AppLayout>

</template>