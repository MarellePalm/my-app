<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index, show } from '@/routes/posts';
import { BreadcrumbItem } from '@/types';
import { Post } from './Index.vue';
import { useForm } from '@inertiajs/vue3';
import { add } from '@/routes/comments';
import { Textarea } from '@/components/ui/textarea';
import Button from '@/components/ui/button/Button.vue';

const props = defineProps<{ post: Post }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: index().url,
    },
    {
        title: props.post.title,
        href: show(props.post.id).url,
    },
];

const form= useForm({
  content: "",
});

const submit= ()=>{
  form.post(add(props.post.id).url,{
    preserveScroll:true,
    onSuccess:()=>{
      form.reset();
    },
  });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Full width container -->
        <div class="w-full px-6 py-8">
            <!-- Back to posts -->
            <div class="mb-4">
                <a
                    :href="index().url"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    ← Back to Posts
                </a>
            </div>

            <!-- Post container – now FULL width -->
            <div class="w-full rounded-xl border border-gray-100 bg-white p-8 shadow">
                <!-- Title -->
                <h1 class="mb-4 text-4xl font-semibold text-gray-900">
                    {{ props.post.title }}
                </h1>

                <!-- Dates -->
                <div class="mb-6 space-y-1 text-sm text-gray-500">
                    <p>Created: {{ new Date(props.post.created_at).toLocaleDateString() }}</p>
                    <p>Updated: {{ new Date(props.post.updated_at).toLocaleDateString() }}</p>
                </div>

                <!-- Author -->
                <p class="mb-6 text-sm text-gray-600">
                    Author:
                    <span class="font-medium text-gray-800"> {{ props.post.author?.first_name }} {{ props.post.author?.last_name }} </span>
                </p>

                <!-- Content -->
                <div class="prose prose-gray mb-6 max-w-none">
                    {{ props.post.content }}
                </div>

                <!-- Published status -->
                <div>
                    <span
                        class="inline-block rounded-full px-3 py-1 text-sm font-medium"
                        :class="props.post.published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600'"
                    >
                        {{ props.post.published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <ul class="mt-6 space-y-4 p-5">
                    <li
                        v-for="comment in post.comments"
                        :key="comment.id"
                        class="rounded-lg border border-gray-200 bg-white p-4 shadow dark:border-gray-700 dark:bg-gray-800"
                    >
                        <!-- Top row: avatar + name + date -->
                        <div class="mb-2 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar placeholder -->
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                >
                                    <span class="text-sm font-semibold">
                                        {{ comment.user?.name?.[0] || 'U' }}
                                    </span>
                                </div>

                                <!-- Name -->
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-50">
                                        {{ comment.user?.name || 'Anonüümne' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Date -->
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ new Date(comment.created_at_formated).toLocaleString() }}
                            </span>
                        </div>

                        <!-- Comment content -->
                        <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                            {{comment.content }}
                        </p>
                    </li>
                </ul>
                <div class="pb-6">
                  Comment
                  <form id="comment-form" @submit.prevent="submit">
                    <Textarea v-model="form.content"></Textarea>
                  </form>
                  <div class="flex mt-4 justify-end">
                    <Button type="submit" form="comment-form">Submit</Button>
                  </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
