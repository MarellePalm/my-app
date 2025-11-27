<script setup lang="ts">

import { BreadcrumbItem } from '@/types';
import { Post } from './Index.vue';
import { index, show } from '@/routes/posts';
import AppLayout from '@/layouts/AppLayout.vue';

const props =defineProps <{ post:Post}>();

const breadcrumbs: BreadcrumbItem[]= [
  {
    title: 'Posts',
    href: index().url
  },
  {
    title: props.post.title,
    href: show(props.post.id).url,
  }
]


</script>

<template>

  <AppLayout :breadcrumbs="breadcrumbs">

    <!-- Full width container -->
    <div class="w-full px-6 py-8">

      <!-- Back to posts -->
      <div class="mb-4">
        <a
          :href="index().url"
          class="inline-flex items-center text-sm px-3 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50"
        >
          ← Back to Posts
        </a>
      </div>

      <!-- Post container – now FULL width -->
      <div class="bg-white shadow rounded-xl p-8 border border-gray-100 w-full">

        <!-- Title -->
        <h1 class="text-4xl font-semibold text-gray-900 mb-4">
          {{ props.post.title }}
        </h1>

        <!-- Dates -->
        <div class="text-sm text-gray-500 mb-6 space-y-1">
          <p>Created: {{ new Date(props.post.created_at).toLocaleDateString() }}</p>
          <p>Updated: {{ new Date(props.post.updated_at).toLocaleDateString() }}</p>
        </div>

        <!-- Author -->
        <p class="text-sm text-gray-600 mb-6">
          Author: 
          <span class="font-medium text-gray-800">
            {{ props.post.author?.first_name }} {{ props.post.author?.last_name }}
          </span>
        </p>

        <!-- Content -->
        <div class="prose prose-gray max-w-none mb-6">
          {{ props.post.content }}
        </div>

        <!-- Published status -->
        <div>
          <span
            class="inline-block px-3 py-1 rounded-full text-sm font-medium"
            :class="props.post.published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600'"
          >
            {{ props.post.published ? 'Published' : 'Draft' }}
          </span>
        </div>
        <ul>
          <li v-for="comment in post.comments" :key="comment.id">
           ID {{comment.id}} {{ comment.content }}
          </li>
        </ul>

      </div>

    </div>

  </AppLayout>

</template>
