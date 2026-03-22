<script setup lang="ts">
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../interfaces/interfaces';
import { useAlert } from '../../Composables/useAlert';
import ThreeDotDropdown from '../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';

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
    window.open(`/exams/${props.exam.id}/students/list`)
}

const formCodes = async () => {
    if(noStudents()) return
    if(!props.exam.id){
        return
    }
    window.open(`/exams/${props.exam.id}/codes`)
}
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem title="Скачать список" @click="downloadStudentsList"/>
        <AppListDropDownItem title="Скачать коды" @click="formCodes" />
    </ThreeDotDropdown>
</template>