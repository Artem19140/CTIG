<script setup lang="ts">
import BaseServerTable from '../../Components/BaseServerTable.vue';
import { useModals } from '../../Composables/useModals';

const {open} = useModals()

const props = defineProps<{
    attempts: any
}>()

const headers = [
    {title : "Экзамен",sortable: false, key: 'examName', align: 'start' },
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
    {title : "Дата рождения",sortable: false, key: 'dateBirth', align: 'center' }
]

const openAttempt =  (item : any) => {
    open('attemptChecking', {attemptId:item.id})
}
</script>

<template>
    <BaseServerTable 
        title="Проверка"
        :headers="headers"
        :elements="attempts"
        @row-click="openAttempt"
    />

</template>