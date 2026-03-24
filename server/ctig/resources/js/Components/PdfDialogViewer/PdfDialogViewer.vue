<script setup lang="ts">
const props = defineProps<{
    url:string
}>()

const isOpen = defineModel<boolean>({default:false})
</script>

<template>
    <v-dialog
        v-model="isOpen"

        height="100%"
        width="100%"
    >
        <v-card>
            <div class="flex justify-end">
                <span class="mr-5 cursor-pointer" @click="isOpen=false">x</span>
            </div>
                
            <iframe
                height="100%"
                width="100%"
                :src="url"
            />
        </v-card>
    </v-dialog>
</template>