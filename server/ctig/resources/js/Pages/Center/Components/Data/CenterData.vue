<script setup lang="ts">
import { ref } from 'vue';
import ShowData from './ShowData.vue';
import UpdateData from './UpdateData.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Center } from '@/interfaces/Center';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    data : Center
}>()

const mode = ref<string>('show')
</script>


<template>
    <Head>
        <title>Данные центра</title>
    </Head>
    <div class="p-3">
        <ShowData :center="data" v-if="mode === 'show'" />
        <UpdateData 
            @mode-show="mode = 'show'" 
            :center="data" 
            v-if="mode === 'update'"  
        />
        <div class="flex gap-4 mt-4">
            
            <AppPrimaryButton
                text="Редактировать"
                @click="mode = 'update'"
                v-if="mode === 'show'"
            />
        </div>
    </div>
</template>