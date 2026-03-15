<script setup lang="ts">
import axios from 'axios';
import { modalState } from '../../../../Composables/modalState'
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';


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
            <v-menu
                    @click.stop
                    location="bottom end"
                >
                <template v-slot:activator="{ props }">
                    <v-btn
                        icon
                        v-bind="props"
                        variant="text"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item link @click="download(item.id)">
                        <v-list-title>
                            Скачать заявление
                        </v-list-title>
                    </v-list-item>

                    <v-list-item link @click="transfer">
                        <v-list-title>
                            Перенести
                        </v-list-title>
                    </v-list-item>

                    <v-list-item link @click="cancell(item.id)">
                        <v-list-title color="red">
                            Отменить запись
                        </v-list-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
    </v-data-table>
</template>