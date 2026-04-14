<script setup lang="ts">
import { ref } from 'vue';
import ShowData from './Components/ShowData.vue';
import UpdateData from './Components/UpdateData.vue';
import EmployeeLayout from '@layouts/EmployeeLayout.vue';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';

defineOptions({
  layout: EmployeeLayout,
})

const props = defineProps<{
    center : any | null
}>()

const mode = ref<string>('show')

const {can} = useAuth()
</script>

<template>
    <v-card
        width="700"
        class="mx-auto mt-16"
        title="Данные центра"
    >
        <v-card-text>
            <ShowData :center="center" v-if="mode === 'show'" />
            <UpdateData :center="center" v-if="mode === 'update'"  />
        </v-card-text>
        <v-card-actions  class="flex justify-center">
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
                v-if="mode === 'show' && can([Roles.ORG_ADMIN])"
            />
        </v-card-actions>
    </v-card>
</template>