<script setup lang="ts">
import { formatterDate, formatterTime } from '../../../../Helpers/heplers';
import BaseDialog from '../../../../Components/BaseDialog/BaseDialog.vue';
import ExamActionsDropdown from './ExamActionsDropdown.vue';
import StudentsTable from './StudentsTable.vue';
import { useExamShowModal } from '../../../../Composables/modalWindows/useExamShowModal';

const {isOpen, close , exam, loading} = useExamShowModal()

const examinersList = (examinersList :Array<any>) => {
    return examinersList.map(s => s.fullName).join(', ');
}

</script>

<template>
    <BaseDialog 
        width="800"
        :loading="loading && !exam"
        v-model="isOpen"
        :subtitle="`${exam?.sessionNumber ?? '-'} / ${exam?.group ?? '-'}`"
        @before-close="(done) =>  close()"
    >
        <template #title>
            Экзамен <span v-if="exam?.isCancelled" class="text-red-500 ml-2">
                        (отменён)
                    </span>
        </template>
        <template #titleActions>
            <ExamActionsDropdown :exam="exam" />
        </template>
        <template #skeleton>
             <v-skeleton-loader
                type="avatar, heading, paragraph, paragraph"
                height="100%"
                max-width="800"
            ></v-skeleton-loader>
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
                <v-list-item-title>{{`${formatterTime(exam?.beginTime)},  ${formatterDate(exam?.beginTime)} `}}</v-list-item-title>
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
                    <v-list-item-subtitle>Запись</v-list-item-subtitle>
                    <v-list-item-title>{{` ${exam?.students?.length }/${exam?.capacity}  `}}</v-list-item-title>
                </v-list-item>
            </v-list>
            <v-list>
                <v-list-item  v-if="exam?.students?.length">
                    <StudentsTable :students="exam.students ?? []" :exam-id="exam.id" />
                </v-list-item>
                <v-list-item  v-else class="text-center">
                    <v-list-item-subtitle>Запись пуста</v-list-item-subtitle>
                </v-list-item>
            </v-list>
        </v-card-text>
        <template #actions="{close}">
            <v-btn text @click="close">Закрыть</v-btn>
        </template>
    </BaseDialog>
</template>