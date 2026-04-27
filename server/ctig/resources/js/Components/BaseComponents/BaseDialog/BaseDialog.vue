<script setup lang="ts">
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';


const isOpen = defineModel<boolean>()
const emit = defineEmits<{ (e: 'beforeClose', done: ()=>void) :void} >()

const props = defineProps<{
    title?:string,
    width?:string,
    loading?:boolean,
    subtitle?:string,
    height?:string,
    skeleton?:string,
    error?:boolean,
    onRetry?:Function
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
        @keyup.esc="close"
        :height="height"
        scroll-strategy="none"
    >

        <v-card class="dialog-card d-flex flex-column">
            <v-card-title v-if="$slots.title || title" class="d-flex align-center sticky-top pb-0" > 
                <slot name="title">
                    {{ title }}
                </slot>
                <v-spacer />
                <slot name="titleActions" v-if="$slots.titleActions && !error && !loading">

                </slot>
                <v-btn icon="mdi-close"variant="text" class="ml-4" @click="close"/>
            </v-card-title>

            <v-skeleton-loader
                v-if="loading && skeleton"
                :height="height"
                :width="width"
                :type="skeleton"
            />

            <v-container class="fill-height d-flex align-center justify-center" v-if="!skeleton && loading">
                <div class="text-center">
                <AppProgressCircular size="56" width="5" />
                <div class="mt-4">Загрузка</div>
                </div>
            </v-container>

            <v-card-text 
                v-if="!loading && !error" 
                class="dialog-content pa-4 overflow-y-auto flex-grow-1"
            >
                <slot />
            </v-card-text>

            <v-card-text 
                v-if="error && !loading" 
                class="flex justify-center items-center flex-column"
                
                >
                Повторить
                <v-btn 
                    @click="onRetry"
                    icon
                    variant="text"
                    size="large"
                >
                    <v-icon size="36">mdi-refresh</v-icon>
                </v-btn>
                
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