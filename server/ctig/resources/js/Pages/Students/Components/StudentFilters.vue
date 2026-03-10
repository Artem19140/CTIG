<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue';

const loading = defineModel<boolean>()

const form = useForm({
    search: '', 
})


let timer: number | null = null

watch(() => form.search, (value) => {
    if(timer){
        clearTimeout(timer)
    }
    
    timer = window.setTimeout(() => {
        loading.value = true
        form.get(`students?search=${value}`, {
            preserveState:true,
            preserveScroll:true,
            onFinish: () => loading.value = false
        })
        
    }, 600)
    
})

</script>

<template>
    <v-text-field
        prepend-inner-icon="mdi-magnify"
        v-model="form.search"
        variant="outlined"
        hide-details
        single-line
    />
</template>