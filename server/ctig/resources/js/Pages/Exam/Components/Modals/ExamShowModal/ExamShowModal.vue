<script setup lang="ts">
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import ExamActionsDropdown from '@components/Exam/ExamActionsDropdown.vue';
import EnrollmentsTable from './EnrollmentsTable.vue';
import { computed, onMounted, ref } from 'vue';
import { Enrollment, Exam } from '@interfaces/Interfaces';
import { useHttp } from '@inertiajs/vue3';
import ExamStatusChip from '@components/Exam/ExamStatusChip.vue';
import { DateFormatter } from '@helpers/DateFormatter';
import { ExamStatus } from '@/constants/ExamStatus';
import ExamCapacityChip from '@/components/Exam/ExamCapacityChip.vue';

const props = defineProps<{
    examId:number
}>()

const http = useHttp()

const exam = ref<Exam |null>(null)

const isOpen = defineModel<boolean>({default:false})

const examiners = computed(() =>{
    return exam.value?.examiners.map(s => s.fullName).join(', ');
})

const getExam = async () => {
    await http.get(`/exams/${props.examId}`,{
        onSuccess:(response : any)=>{
            exam.value = response.data
        },
    })
}

onMounted( async () => {
    getExam()
})


const rechedule = (value: Enrollment) => {
    if(!exam.value) return
    exam.value.enrollments =exam.value.enrollments.filter(e => e.id !== value.id)
}
const cancel =(reason : string) => {
    if(!exam.value) return
    exam.value.status = ExamStatus.CANCELLED
    exam.value.cancelledReason = reason
}

</script>

<template>
    <BaseDialog 
        width="800"
        height="800"
        :loading="http.processing"
        v-model="isOpen"
        :error="http.hasErrors"
        :onRetry="getExam"
        :subtitle="`${exam?.sessionNumber ?? '-'} / ${exam?.group ?? '-'}`"
        @before-close="(done) =>  done()"
        skeleton="heading, list-item-two-line, list-item-two-line, list-item-three-line, divider, table"
    >
        <template #title>
            <div class="flex gap-2">
                Экзамен
                <ExamStatusChip
                    :exam="exam"
                />
            </div>
        </template>
        <template #titleActions>
            <ExamActionsDropdown
                @cancel="cancel"
            :exam="exam" />
        </template> 
        <v-card-text>
        <v-list>
            <div>
                <v-spacer />
            </div>
            <v-list-item v-if="exam?.status === ExamStatus.CANCELLED">
                <v-list-item-subtitle class="text-red">Причина отмены</v-list-item-subtitle>
                <v-list-item-title class="text-red" style="white-space: normal; word-break: break-word;">{{exam?.cancelledReason ?? '-'}}</v-list-item-title>
            </v-list-item>
            <v-list-item>  
                <v-list-item-subtitle>Тип</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{exam?.name}}</v-list-item-title>
            </v-list-item>
            <v-list-item> 
                <v-list-item-subtitle> Дата</v-list-item-subtitle>
                <v-list-item-title>{{new DateFormatter(exam?.beginTime ?? '').format('H:i, d.m.Y') }}</v-list-item-title>
            </v-list-item>
            
            <v-list-item>  
                <v-list-item-subtitle>Адрес </v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{exam?.address}}</v-list-item-title>
            </v-list-item>
            <v-list-item>
                <v-list-item-subtitle>Экзаменаторы</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examiners}}</v-list-item-title>
            </v-list-item>
            <v-list-item>
                <v-list-item-subtitle>Комментарий</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{exam?.comment ?? '-'}}</v-list-item-title>
            </v-list-item>
            
        </v-list>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
            <v-list>
                <v-list-item>
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span>Запись</span>
                            <ExamCapacityChip :exam="exam" />
                        </div>
                    </div>
                </v-list-item>
                
            </v-list>
            <v-list>
                <v-list-item  v-if="exam?.enrollments.length">
                    <EnrollmentsTable @reschedule="rechedule" :exam="exam" />
                </v-list-item>
                <v-list-item  v-else class="text-center">
                    <v-list-item-subtitle>Запись пуста</v-list-item-subtitle>
                </v-list-item>
            </v-list>
        </v-card-text>
    </BaseDialog>
</template>