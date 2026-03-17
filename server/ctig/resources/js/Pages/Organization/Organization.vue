<script setup lang="ts">
import { ref } from 'vue';
import ShowData from './Components/ShowData.vue';
import UpdateData from './Components/UpdateData.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

defineOptions({
  layout: EmployeeLayout,
})
const props = defineProps<{
    organization : any | null
}>()

const mode = ref<string>('show')
</script>

<template>
    <v-card
        width="600"
        class="mx-auto mt-16"
        title="Данные организации"
    >
           
        <v-card-text>
            <ShowData :organization="organization" v-if="mode === 'show'" />
            <UpdateData :organization="organization" v-if="mode === 'update'"  />
        </v-card-text>
        <v-card-actions  class="flex justify-center">
            <v-btn
                variant="flat"
                color="primary"
                v-if="mode === 'update'"
            >
                Обновить
            </v-btn>

            <v-btn @click="mode = 'show'" v-if="mode === 'update'">
                Отмена
            </v-btn>

            <v-btn 
                @click="mode = 'update'"
                v-if="mode === 'show'"
                variant="flat"
                color="primary"
            >Редактировать</v-btn>
        </v-card-actions>
    </v-card>
</template>