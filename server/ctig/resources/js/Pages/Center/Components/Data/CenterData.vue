<script setup lang="ts">
import { ref } from 'vue';
import ShowData from './ShowData.vue';
import UpdateData from './UpdateData.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';

const props = defineProps<{
    data : any
}>()

const mode = ref<string>('show')
</script>


<template>
    <div class="p-3">
        <ShowData :center="data" v-if="mode === 'show'" />
        <UpdateData :center="data" v-if="mode === 'update'"  />
        <div class="flex gap-4">

        
            <AppPrimaryButton
                text="Обновить"
                v-if="mode === 'update'"
            />
            <v-btn @click="mode = 'show'" v-if="mode === 'update'">
                Отмена
            </v-btn>
            <AppPrimaryButton
                text="Редактировать"
                @click="mode = 'update'"
                v-if="mode === 'show'"
            />
        </div>
    </div>
</template>