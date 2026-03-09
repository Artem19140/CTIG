<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import { formatterDate, attemptStatus } from '../../../Helpers/heplers';
import BaseDialog from '../../../Components/UI/BaseDialog/BaseDialog.vue';
import { modalState } from '../../../Composables/modalState';
import ExamEnrollmentMenu from '../../Exam/Components/ExamEnrollmentMenu.vue';

const isOpen = defineModel<boolean>()
const studentData = ref<any | null>(null)
const loading = ref(true)

watch(() => modalState.studentId, async () => {
  if (modalState.studentId) {
        isOpen.value = true
        if(studentData.value?.id === modalState.studentId){
           return 
        }
        studentData.value = null 
        loading.value = true
        const res = await axios.get(`/students/${modalState.studentId}`)
        studentData.value =  res.data.data
        loading.value = false  
  }
}, { immediate: true })

const showDocument = (url :string) => {
    modalState.fileUrl = url
}


</script>

<template>
    <BaseDialog 
        width="700"
        :title="`Карточка студента (ID ${studentData?.id ?? ''})`"
        :loading="loading && !studentData"
        v-model="isOpen"
        
        @before-close="(done) =>  { modalState.studentId = null; done()}"
    >
        <template #titleActions>
            <ExamEnrollmentMenu :student="studentData" />
        </template>
        <template #skeleton>
            <v-skeleton-loader
                type="avatar, heading, paragraph, paragraph"
                height="100%"
                max-width="100%"
            ></v-skeleton-loader>
        </template>

        <v-card-text>
            <v-card-text>
                <div class="flex">
                    <v-avatar color="surface-variant cursor-pointer hover:opacity-80 transition-opacity"  size="150" >
                        <v-img 
                            
                            src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                            cover 
                            @click="showDocument('https://cdn.vuetifyjs.com/images/profiles/marcus.jpg')"
                        />
                    </v-avatar>
                <div class="flex flex-col justify-center ml-8">
                    <div class="text-headline-small">{{`${studentData?.surname} ${studentData?.name} ${studentData?.patronymic}`}}</div>
                    <div class="text-subtitle-1">{{`${studentData?.surnameLatin} ${studentData?.nameLatin} ${studentData?.patronymicLatin}`}}</div>
                    <div class="text-subtitle-2">{{formatterDate(studentData?.dateBirth)}}</div>
                    
                </div>
                </div>
            </v-card-text>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <v-list-item-title>{{`${studentData?.passportSeries ?? ''} ${studentData?.passportNumber ?? ''}, выдан ${formatterDate(studentData?.issuedDate ?? '')} (${studentData?.issuedBy ?? ''})`}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Миграционная карта</v-list-item-subtitle>
                    <v-list-item-title>{{studentData?.migrationCardRequisite ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Адрес регистрации</v-list-item-subtitle>
                    <v-list-item-title>{{studentData?.addressReg ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-subtitle>Номер телефона</v-list-item-subtitle>
                    <v-list-item-title>{{studentData?.phone ?? ''}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Скан паспорта</v-list-item-subtitle>
                    <v-list-item-title v-if="studentData?.passportScanPath">-</v-list-item-title>
                    <v-img
                        v-else 
                        :width="50"
                        class="mt-4 cursor-pointer hover:opacity-80 transition-opacity"
                        src="https://cdn-icons-png.flaticon.com/512/9034/9034536.png"
                        @click="showDocument('storage/Spetsifikatsiya_ekzamen_Inostr_grazhd_1-3 (1).pdf')"
                    ></v-img>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle class="mb-4">Экзамены</v-list-item-subtitle>
                    <v-list-item-title v-if="studentData?.attempts.length === 0">-</v-list-item-title>
                            <v-list 
                                scrollable 
                                max-height="200"
                            >
                                <v-list-item 
                                    :class="['px-2', Number(index) % 2 === 0 ?  '' : 'bg-grey-lighten-4']"
                                    v-for="(attempt, index) in studentData.attempts" :key="attempt.id"
                                    link
                                    >
                                        <v-list-item-title>{{attempt.exam.name}}</v-list-item-title>
                                        <v-list-item-subtitle> {{formatterDate(attempt.exam.date)}} </v-list-item-subtitle>
                                        <!-- <v-list-item-subtitle> {{formatterTime(attempt.startedAt)}} - {{formatterTime(attempt.finishedAt)}}</v-list-item-subtitle> -->
                                        <v-list-item-subtitle>{{attemptStatus(attempt)}}</v-list-item-subtitle>
                                </v-list-item>
                            </v-list>
                    
                </v-list-item>
            </v-list>
        </v-card-text>

        <template #actions="{close}">
            <v-btn text @click="close">Закрыть</v-btn>
        </template>
    </BaseDialog>
</template>