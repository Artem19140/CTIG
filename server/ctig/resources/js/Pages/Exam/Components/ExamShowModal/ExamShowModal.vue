<script setup lang="ts">
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import ExamActionsDropdown from './ExamActionsDropdown.vue';
import ForeignNationalsTable from './ForeignNationalsTable.vue';
import { onMounted, ref } from 'vue';
import { Exam } from '../../../../interfaces/interfaces';
import { useHttp } from '@inertiajs/vue3';
import { examStatus } from '../../../../Helpers/heplers';

const props = defineProps<{
    examId:number
}>()

const http = useHttp()

const exam = ref<Exam |null>(null)

const isOpen = defineModel<boolean>({default:false})

const examinersList = (examinersList :Array<any>) => {
    return examinersList.map(s => s.fullName).join(', ');
}


const getExam = async () => {
    http.get(`/exams/${props.examId}`,{
        onSuccess:(response : any)=>{
            exam.value = response.data
        }
    })
}

onMounted( async () => {
    if(!props.examId) return
    getExam()
})

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
                <v-chip
                    small
                    dark
                    v-if="exam"
                    :color="examStatus(exam).color.replace('text-', '')"
                >
                    {{ examStatus(exam).text }}
                </v-chip>
            </div>
        </template>
        <template #titleActions>
            <ExamActionsDropdown :exam="exam" />
        </template> 
        <v-card-text>
        <v-list>
            <div>
                <v-spacer />
            </div>
            <v-list-item>  
                <v-list-item-subtitle>Тип</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{exam?.name}}</v-list-item-title>
            </v-list-item>
            <v-list-item> 
                <v-list-item-subtitle> Дата и время</v-list-item-subtitle>
                <v-list-item-title>{{exam?.beginTime }}</v-list-item-title>
            </v-list-item>
            
            <v-list-item>  
                <v-list-item-subtitle>Адрес </v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{exam?.address}}</v-list-item-title>
            </v-list-item>
            <v-list-item>
                <v-list-item-subtitle>Экзаменаторы</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examinersList(exam?.examiners ?? [])}}</v-list-item-title>
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
                            <v-list-item-subtitle>Запись</v-list-item-subtitle>
                            <v-chip
                                small
                                :color="(exam?.foreignNationals?.length ?? 0) / (exam?.capacity ?? 1) === 1 ? 'red' : 'grey lighten-2'"
                                dark
                            >
                                {{ `${exam?.foreignNationals?.length ?? 0}/${exam?.capacity ?? 0}` }}
                            </v-chip>
                        </div>
                        <v-btn border variant="text">Результаты</v-btn>
                    </div>
                </v-list-item>
                
            </v-list>
            <v-list>
                <v-list-item  v-if="exam?.foreignNationals?.length">
                    <ForeignNationalsTable :foreignNationals="exam.foreignNationals ?? []" :exam="exam" />
                </v-list-item>
                <v-list-item  v-else class="text-center">
                    <v-list-item-subtitle>Запись пуста</v-list-item-subtitle>
                </v-list-item>
            </v-list>
        </v-card-text>
    </BaseDialog>
</template>