<script setup lang="ts">
import { useForm, useHttp } from '@inertiajs/vue3';
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import PrimaryButton from '../../../../Components/PrimaryButton/PrimaryButton.vue';
import { computed, onMounted, ref } from 'vue';
import { Exam } from '../../../../interfaces/interfaces';
import AppAutocomplete from '../../../../Components/AppAutocomplete/AppAutocomplete.vue';


const props = defineProps<{
    foreignNational:any,
    oldExam:Exam
}>()

const isOpen = defineModel<boolean>({default:false})
const exams = ref<Exam[] | []>([])
const foreignNationalId = computed(() => props.foreignNational.id)
const form = useForm({
    newExamId:null, 
    oldExamId: props.oldExam.id
})

const transfer = () => {
    form.post(`/foreign-nationals/${foreignNationalId.value}/exams/transfer`,{
        onSuccess:(page :any) =>{
            if(page.flash.success){
                window.open(page.flash.redirectUrl)
                form.resetAndClearErrors()
                isOpen.value=false
            }
            
        }
    })
}
const http = useHttp()

onMounted(() => {
    http.get(`/exams/available?examTypeId=${props.oldExam.examTypeId}&foreignNationalId=${foreignNationalId.value}`,{ 
        onSuccess:(response:any) => {
            exams.value = response
        }
    })
})
</script>

<template>
    <BaseDialog
        width="500"
        title="Перенос записи на экзамен"
        v-model="isOpen"
        @before-close="(done) => done()"
    >
        <AppAutocomplete
            v-model="form.newExamId"
            :items="exams"
            :disabled="http.processing"
            :loading="http.processing"
            item-title="beginTime"
            item-value="id"
            label="Дата и время"
        />
        <template #actions>
            <PrimaryButton
                :loading="form.processing"
                :disabled="!form.newExamId"
                text="Перенести"
                @click="transfer"
            />
        </template>
    </BaseDialog>
</template>