<script setup lang="ts">
import { formatterDate, attemptStatus } from '../../../Helpers/heplers';
import BaseDialog from '../../../Components/UI/BaseDialog/BaseDialog.vue';
import { modalState } from '../../../Composables/modalState';
import ExamEnrollmentMenu from '../../Exam/Components/ExamEnrollmentMenu.vue';
import { useStudentShowModal } from '../../../Composables/modalWindows/useStudentShowModal';

const {isOpen, loading,close, student} = useStudentShowModal()

const showDocument = (url :string) => {
    modalState.fileUrl = url
}
</script>

<template>
    <BaseDialog 
        width="700"
        :title="`Карточка студента (ID ${student?.id ?? ''})`"
        :loading="loading && !student"
        v-model="isOpen"
        
        @before-close="() => close()"
    >
        <template #titleActions>
            <ExamEnrollmentMenu :student="student" />
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
                    <div class="text-headline-small">{{`${student?.surname} ${student?.name} ${student?.patronymic}`}}</div>
                    <div class="text-subtitle-1">{{`${student?.surnameLatin} ${student?.nameLatin} ${student?.patronymicLatin}`}}</div>
                    <div class="text-subtitle-2">{{formatterDate(student?.dateBirth)}}</div>
                    
                </div>
                </div>
            </v-card-text>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <v-list-item-title>{{`${student?.fullPassport ?? ''}, выдан ${formatterDate(student?.issuedDate ?? '')} (${student?.issuedBy ?? ''})`}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Миграционная карта</v-list-item-subtitle>
                    <v-list-item-title>{{student?.migrationCardRequisite ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Адрес регистрации</v-list-item-subtitle>
                    <v-list-item-title>{{student?.addressReg ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-subtitle>Номер телефона</v-list-item-subtitle>
                    <v-list-item-title>{{student?.phone ?? ''}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Скан паспорта</v-list-item-subtitle>
                    <v-list-item-title v-if="student?.passportScanPath">-</v-list-item-title>
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
                    <v-list-item-title v-if="student?.attempts.length === 0">-</v-list-item-title>
                            <v-list 
                                scrollable 
                                max-height="200"
                            >
                                <v-list-item 
                                    :class="['px-2', Number(index) % 2 === 0 ?  '' : 'bg-grey-lighten-4']"
                                    v-for="(attempt, index) in student?.attempts" :key="attempt.id"
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