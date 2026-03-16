<script setup lang="ts">
import { ref, watch } from 'vue'; //105
import axios from 'axios';
import { formatterDate, formatterTime } from '../../../../Helpers/heplers';
import BaseDialog from '../../../../Components/UI/BaseDialog/BaseDialog.vue';
import Dropdown from '../ExamShowModal/Dropdown.vue';
import StudentsTable from './StudentsTable.vue';

const isOpen = defineModel<boolean>()
const props = defineProps<{ id: number | undefined }>()
const examData = ref()
const loading = ref(true)

watch(isOpen, async () => {
  if (props.id) {
        if(!isOpen.value){
                return
            }
        if(props.id === examData.value?.id){
            return
        }
        loading.value = true
        const res = await axios.get(`/exams/${props.id}`)
        examData.value = res.data.data
        loading.value = false  
  }
}, { immediate: true })

const examTestersList = (testersList :Array<any>) => {
    return testersList.map(s => s.fullName).join(', ');
}

</script>

<template>
    <BaseDialog 
        width="800"
        title="Экзамен"
        :loading="loading && !examData"
        v-model="isOpen"
        :subtitle="`${examData?.sessionNumber ?? '-'} / ${examData?.group ?? '-'}`"
        @before-close="(done) =>  {done()}"
    >
        <template #titleActions>
            <Dropdown :exam-id="examData?.id" />
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
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examData?.name}}</v-list-item-title>
            </v-list-item>
            <v-list-item> 
                <v-list-item-subtitle> Дата и время</v-list-item-subtitle>
                <v-list-item-title>{{`${formatterTime(examData?.beginTime)},  ${formatterDate(examData?.beginTime)} `}}</v-list-item-title>
            </v-list-item>
            
            <v-list-item>  
                <v-list-item-subtitle>Адрес </v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examData?.address}}</v-list-item-title>
            </v-list-item>
            <v-list-item>
                <v-list-item-subtitle>Тестеры</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examTestersList(examData?.testers ?? [])}}</v-list-item-title>
            </v-list-item>
            <v-list-item>
                <v-list-item-subtitle>Комментарий</v-list-item-subtitle>
                <v-list-item-title style="white-space: normal; word-break: break-word;">{{examData?.comment ?? '-'}}</v-list-item-title>
            </v-list-item>
        </v-list>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Запись</v-list-item-subtitle>
                    <v-list-item-title>{{` ${examData.students.length }/${examData?.capacity}  `}}</v-list-item-title>
                </v-list-item>
            </v-list>
            <v-list>
                <v-list-item>
                    <StudentsTable :students="examData.students" :exam-id="examData.id" />
                </v-list-item>
            </v-list>
        </v-card-text>
        <template #actions="{close}">
            <v-btn text @click="close">Закрыть</v-btn>
        </template>
    </BaseDialog>
</template>