<script setup lang="ts">
import { useLoadingSnackbar } from '@/composables/useLoadingSnackBar';
import AppListDropDownItem from '../UI/AppListDropDownItem/AppListDropDownItem.vue';
import { useAuth } from '@/composables/useAuth';
import { useForm } from '@inertiajs/vue3';
import { Exam } from '@/interfaces/Exam';
import { useExamStatus } from '@/composables/useExamStatus';
import { computed } from 'vue';
import { Roles } from '@/constants/Roles';

const props = defineProps<{exam : Exam | null}>()

const auth = useAuth()

const loadingSnackbar = useLoadingSnackbar()
const download = (document :string) => {
    if(!props.exam?.id || !document){
        return
    }
    const form = useForm()
    loadingSnackbar.open('Скачивание')
    form.get(`/exams/${props.exam.id}/documents/${document}/available`,{
      onSuccess:(page) => {
        if(page.flash.redirectUrl){
          //modals.open('pdf', {url:page.flash.redirectUrl})
          window.open(String(page.flash.redirectUrl))
        }
      },
      onFinish:()=>{
        loadingSnackbar.close()
      }
    })
}

const hasEnrollment  = computed(() => {
  return Boolean(props.exam?.enrollmentsCount)
})

const {isFinished, isCancelled} = useExamStatus(props.exam)

const downloadStatementlDisabled  = !isFinished.value || isCancelled.value || !hasEnrollment.value 
const downloadProtocolDisabled = !isFinished.value || isCancelled.value || !hasEnrollment.value 
const downloadListDisabled =  !hasEnrollment.value
const downloadCodesDisabled  =  isCancelled.value || !hasEnrollment.value || !(props.exam?.codesAvailable ?? false)
</script>

<template>
    <AppListDropDownItem 
        title="Кода" 
        :disabled="downloadCodesDisabled"
        @click="() => download('codes')" 
        v-if="auth.can([Roles.EXAMINER])"
    />

    <AppListDropDownItem 
        title="Список"
        :disabled="downloadListDisabled"  
        @click="download('list')" 
    />

    <AppListDropDownItem 
        title="Результаты"
        :disabled="downloadStatementlDisabled" 
        v-if="auth.can([Roles.EXAMINER])"
        @click="() => download('results')" 
    />

    <AppListDropDownItem 
        title="Протокол" 
        v-if="auth.can([Roles.EXAMINER])"
        :disabled="downloadProtocolDisabled"
        @click="() => download('protocol')" 
    />
</template>