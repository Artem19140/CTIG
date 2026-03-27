<script setup lang="ts">
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { Exam } from '../../interfaces/interfaces';
import { useAlert } from '../../Composables/useAlert';
import ThreeDotDropdown from '../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';

const props = defineProps<{
    exam: Exam
}>()

const noForeignNationals = () => {
    if(!props.exam.foreignNationalsCount){
        const {open} = useAlert()
        open('На экзамен не записано ни одного человека!')
        return true
    }
    return false
}

const downloadForeignNationalsList = () => {
    if(noForeignNationals()) return
    window.open(`/exams/${props.exam.id}/foreign-nationals/list`)
}

const formCodes = async () => {
    if(noForeignNationals()) return
    if(!props.exam.id){
        return
    }
    window.open(`/exams/${props.exam.id}/codes`)
}
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem title="Скачать список" @click="downloadForeignNationalsList"/>
        <AppListDropDownItem title="Скачать коды" @click="formCodes" />
    </ThreeDotDropdown>
</template>