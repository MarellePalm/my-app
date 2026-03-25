<script setup lang="ts">

import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import { edit, update } from '@/routes/posts';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Post } from './Index.vue';

const props = defineProps<{
    post: Post;
    authors: Record<number, string>;
    
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
                       
                        <div class="mt-4 flex items-center space-x-2">
                            <Switch v-model="form.published" id="published" />
                            <Label for="published">Published</Label>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <Button type="submit">Update</Button>
                    </div>
                </form>
                
            </div>
        </div>
    </AppLayout>
</template>
