<script setup lang="ts">
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { modalState } from '../../Composables/modalState';
import { Exam } from '../../interfaces/interfaces';
import { useAlert } from '../../Composables/useAlert';

const props = defineProps<{
    exam: Exam
}>()

const noStudents = () => {
    if(!props.exam.studentsCount){
        const {open} = useAlert()
        open('На экзамен не записано ни одного студента!')
        return true
    }
    return false
}

const downloadStudentsList = () => {
    if(noStudents()) return
    modalState.fileUrl = `/exams/${props.exam.id}/students/list`
}

const formCodes = async () => {
    if(noStudents()) return
    if(!props.exam.id){
        return
    }
    modalState.fileUrl = `/exams/${props.exam.id}/codes`
}
</script>

<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-btn icon
                variant="text"
                v-bind="props"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
        <v-list>
            <AppListDropDownItem title="Скачать список" @click="downloadStudentsList"/>
            <AppListDropDownItem title="Скачать коды" @click="formCodes" />
        </v-list>
    </v-menu>
</template>