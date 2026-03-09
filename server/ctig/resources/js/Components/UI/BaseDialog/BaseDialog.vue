<script setup lang="ts">

const isOpen = defineModel<boolean>()
const emit = defineEmits<{ (e: 'beforeClose', done: ()=>void) :void} >()

const props = defineProps<{
    title:string,
    width:string,
    loading?:boolean,
    subtitle?:string,
    height?:string,
    fullscreen?: boolean
}>()

const close = () => {
    emit('beforeClose', () => {
        isOpen.value = false
    })
}
     //
</script>

<template>
    <v-dialog
        persistent 
        v-model="isOpen"  
        :max-width="width"
        :subtitle="subtitle"
        :height="height"
        :fullscreen="fullscreen"
        @keyup.esc="close"
        
    >
       
        <v-card>
            <v-card-title v-if="$slots.title || title" class="d-flex align-center"> 
                <slot name="title">
                    {{ title }}
                </slot>
                <v-spacer />
                <slot name="titleActions" v-if="$slots.titleActions">

                </slot>
                <v-btn icon="mdi-close"variant="text" class="ml-16" @click="close"/>
            </v-card-title>

            <slot name="skeleton" v-if="loading">
            </slot>

            <v-card-text v-if="!loading">
                <slot />
            </v-card-text>

            <v-card-actions v-if="$slots.actions && !loading">
                <slot name="actions" :close="close" />
            </v-card-actions>
            
        </v-card>
    </v-dialog>


</template>