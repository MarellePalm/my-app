<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit, update } from '@/routes/posts';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

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
        title: 'Edit Post',
        href: edit(props.post.id).url,
    },
];

const form = useForm({
    title: props.post.title,
    content: props.post.content,
    author: props.post.author,
    published: props.post.published,
});

const submit = () => {
    form.put(update(props.post.id).url);
};
</script>

<template>
    <Head title="Edit Post" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="mx-auto h-full w-full max-w-2xl p-4">
                <h3 class="text-lg font-medium">Edit Post</h3>
                <form @submit.prevent="submit">
                    <div class="grid gap-4 mt-6">
                        <div>
                            <Label for="title">Title</Label>
                            <Input class="mt-1" id="title" v-model="form.title" />
                            <InputError :message="form.errors.title"/>
                        </div>
                        <div>
                            <Label for="content">Content</Label>
                            <Textarea class="mt-1" id="content" v-model="form.content" />
                            <InputError :message="form.errors.content" />
                        </div>
                        <div>
                            <Label for="author">Author</Label>
                            <Input class="mt-1" id="author" v-model="form.author" />
                            <InputError :message="form.errors.author" />
                        </div>
                        <div class="mt-4 flex items-center space-x-2">
                            <Switch v-model="form.published" id="published" />
                            <Label for="published">Published</Label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <Button type="submit">Update</Button>
                    </div>
                </form>
                <pre>{{ form }}</pre>
            </div>
        </div>
    </AppLayout>
</template>
