<script setup lang="ts">
import BaseServerTable from '../../Components/BaseServerTable.vue';
import { useModals } from '../../Composables/useModals';

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
</script>

<template>
    <!-- <pre>
    {{ attempts }}
    </pre> -->
    <BaseServerTable 
        title="Проверка"
        :headers="headers"
        :elements="attempts"
        @row-click="openAttempt"
    >
    </BaseServerTable>

</template>