<script setup lang="ts">
const props = defineProps<{
    title?:string,
    toolbarColor?:string
}>()

const emit = defineEmits<{
    (e: 'row-click', item: any): void
}>()
</script>

<template>
    <v-data-table 
        hover 
        hide-default-footer
        @click:row="(event :Event, { item } : any) => emit('row-click', item)"
    >
        <template v-slot:top >
                <v-toolbar flat :color="toolbarColor">
                    <v-toolbar-title>
                        {{ title ?? '' }}
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
    </v-data-table>
</template>