<script setup lang="ts">
import axios from 'axios';
import { modalState } from '../../../../Composables/modalState'
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';


const props = defineProps<{
    students : any,
    examId: number
}>()

function studentShowModal(event:Event, {item}: any) {
    modalState.studentId = item.id  
}

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
    {title : "",sortable: false, key: 'actions', align: 'end' },
]

const download = (studentId :number) => {
    modalState.fileUrl = `students/${studentId}/application-forms?examId=${props.examId}`
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
    const res = await axios.delete(`exams/${props.examId}/students/${studentId}`)
    if( res.status === 204 ){
        
    }
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