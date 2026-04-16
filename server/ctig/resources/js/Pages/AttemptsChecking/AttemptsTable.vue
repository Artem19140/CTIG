<script setup lang="ts">
import AppPaginator from '@/components/UI/AppPaginator/AppPaginator.vue';
import BaseServerTable from '@components/BaseComponents/BaseServerTable/BaseServerTable.vue';
import { useModals } from '@composables/useModals';
import { ref } from 'vue';

const {open} = useModals()

const props = defineProps<{
    attempts: any
}>()

const headers = [
    {title : "Экзамен",sortable: false, key: 'examName', align: 'start' },
    {title : "Дата",sortable: false, key: 'date', align: 'start' },
]

const openAttempt =  (item : any) => {
    open('attemptChecking', {attemptId:item.id})
}
const loading = ref<boolean>(false)
</script>

<template>
    <BaseServerTable 
        title="Проверка"
        :headers="headers"
        :elements="attempts"
        :loading="loading"
        @row-click="openAttempt"
    >
        <template #bottom>
            <AppPaginator
                :meta="attempts.meta"
                :links="attempts.links"
                v-model="loading"
            />
    </template>
    </BaseServerTable>

</template>