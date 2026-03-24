<script setup lang="ts">
import { Paginated } from '../interfaces/interfaces';
import { router } from '@inertiajs/vue3';

    const props = defineProps<{
        elements? : Paginated<any>,
        headers : Array<any>,
        title?:string
        loading?:boolean
    }>()

    const emit = defineEmits<{
        (e: 'row-click', item: any): void
    }>()

    const loadItems = ({ page, itemsPerPage, sortBy }: any) => {
        router.reload({
            data: {
                page: page,
                perPage:itemsPerPage
            },
            
        })
    }
</script>

<template>

        <v-data-table-server
            @update:options="loadItems" 
            :items="elements?.data"
            :headers="headers"
            @click:row="(event :Event, { item } : any) => emit('row-click', item)"
            :items-length="elements?.meta?.total"
            key="id"
            hover
            :loading="loading"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>
                        {{ title ?? 'Таблица' }}
                        <slot name="toolbar-left"></slot>
                    </v-toolbar-title>
                
                    <slot name="toolbar-actions" />
                </v-toolbar>
            </template>
            
            <template
                v-for="(_, slotName) in $slots"
                #[slotName]="slotProps"
            >
                <slot :name="slotName" v-bind="slotProps" />
            </template>
        </v-data-table-server>

</template>