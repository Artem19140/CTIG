<script setup lang="ts">

const props = defineProps<{
    title?:string,
    loading:boolean,
    skeleton?:string
}>()

const isOpen = defineModel<boolean>({default:true})

const emit = defineEmits<{ (e: 'beforeClose', done: ()=>void) :void} >()

const close = () => {
    emit('beforeClose', () => {
        isOpen.value = false
    })
}

</script>

<template>
    <v-navigation-drawer 
        temporary 
        v-model="isOpen" 
        location="right" 
    >
        <v-card 
            class="dialog-card d-flex flex-column h-100 overflo-y"
            :title="title"
        >
            <v-card-title v-if="$slots.title">
                <slot name="title" />
            </v-card-title>
            <v-card-text v-if="!loading" class="flex-grow-1 overflow-y-auto">
                <slot />
            </v-card-text>
            <v-card-text v-else class="d-flex flex-column align-center justify-center flex-grow-1 ">
                <v-progress-circular 
                    indeterminate 
                    size="80"
                    width="5"
                    />
                <div class="mt-4 text-subtitle-2 text-medium-emphasis">
                    Загрузка...
                </div>
            </v-card-text>
            <v-skeleton-loader
                v-if="loading && skeleton"
                height="100%"
                width="100%"
                :type="skeleton"
            />
            <v-card-actions> 
                <slot name="actions" :close="close"/>
                <v-btn
                    @click="close"
                >
                    Закрыть
                </v-btn>
            </v-card-actions>
        </v-card>
        
    </v-navigation-drawer>
</template>