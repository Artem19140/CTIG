<script setup lang="ts">

const isOpen = defineModel<boolean>()
const emit = defineEmits<{ (e: 'beforeClose', done: ()=>void) :void} >()

const props = defineProps<{
    title?:string,
    width:string,
    loading?:boolean,
    subtitle?:string,
    height?:string,
    fullscreen?: boolean
    skeleton?:string
}>()

const close = () => {
    emit('beforeClose', () => {
        isOpen.value = false
    })
}
</script>

<template>
    <v-dialog
        persistent
        v-model="isOpen"  
        :width="width"
        :subtitle="subtitle"
        :height="height"
        :fullscreen="fullscreen"
        @keyup.esc="close"
        
    >
       
        <v-card class="dialog-card d-flex flex-column">
            <v-card-title v-if="$slots.title || title" class="d-flex align-center sticky-top"> 
                <slot name="title">
                    {{ title }}
                </slot>
                <v-spacer />
                <slot name="titleActions" v-if="$slots.titleActions">

                </slot>
                <v-btn icon="mdi-close"variant="text" class="ml-4" @click="close"/>
            </v-card-title>

            <v-skeleton-loader
                v-if="loading"
                :height="height"
                :width="width"
                :type="skeleton"
            />

            <v-card-text v-if="!loading" class="dialog-content pa-4 overflow-y-auto flex-grow-1">
                <slot />
            </v-card-text>

            <v-card-actions v-if="!loading" class="sticky-bottom">
                <slot name="actions" :close="close" />
                <v-btn 
                    @click="close"
                >
                Закрыть</v-btn>
            </v-card-actions>
            
        </v-card>
    </v-dialog>


</template>

<style scoped>
</style>