<script setup lang="ts">
import axios from 'axios';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { router } from '@inertiajs/vue3';
import { Exam } from '../../../../interfaces/interfaces';
import { useModals } from '../../../../Composables/useModals';

const props = defineProps<{
    students : any,
    exam: Exam
}>()

function studentShowModal(event:Event, {item}: any) {
    const {open} = useModals()
    open('studentShow', {studentId:item.id})  
}

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
    {title : "",sortable: false, key: 'actions', align: 'end' },
]

const download = (studentId :number) => {
    window.open(`students/${studentId}/application-forms?examId=${props.exam?.id}`)
}

const transfer = () => {
    axios.post(``)
}
const {confirmOpen} = useConfirmDialog()
const cancell = async (studentId :number) => {
    const ok = await confirmOpen('Отменить запись на экзамен?')
    if(!ok){
        return
    }
    router.delete(`exams/${props.exam?.id}/students/${studentId}`,{
        onSuccess: (page) =>{
            const success = page.flash.success
        }
    })
}
</script>

<template>
    <v-data-table 
        :items="students"
        hide-default-footer
        :headers="headers"
        fixed-header
        hover
        @click:row="studentShowModal"
    >
        <template #item.actions="{item}">
            <ThreeDotDropdown>
                <AppListDropDownItem 
                    title="Скачать заявление" 
                    @click="download(item.id)"
                />
                <AppListDropDownItem 
                    title="Перенести запись" 
                    @click="transfer"
                />
                <AppListDropDownItem 
                    title="Отменить запись" 
                    @click="cancell(item.id)"
                    color="text-red"
                />
            </ThreeDotDropdown>
        </template>
    </v-data-table>
</template>