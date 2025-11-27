<script setup lang="ts">
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCaption from '@/components/ui/table/TableCaption.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/authors';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Authors',
        href: index().url,
    },
];

interface PaginatedResponse{
    data:Authors[];
}

type Authors = {
    id: number;
    first_name: string;
    last_name: string;
    date_of_birth: number;
}

defineProps<{
    authors: PaginatedResponse;
}>();


</script>
<template>
    <Head title="Authors"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <p>Tere</p>
        <Table>
                <TableCaption>A list of your recent blog posts.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">ID</TableHead>
                        <TableHead>Title</TableHead>
                        <TableHead>Author</TableHead>
                        <TableHead class="text-right">Create at</TableHead>
                        <TableHead class="text-right">Updated at</TableHead>
                        <TableHead class="text-right">Published</TableHead>
                        <TableHead>
                            <span class="sr-only">Actions</span>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(author, index) in authors.data" :key="index">
                        <TableCell class="font-medium">{{ author.id }}</TableCell>
                        <TableCell>{{ author.first_name }}</TableCell>
                        <TableCell>{{ author.last_name }}</TableCell>
                        <TableCell>{{ author.date_of_birth}}</TableCell>
                        <TableCell>
                            <div class="flex justify-end">
                        
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
    </div>
    </AppLayout>
    

    

</template>