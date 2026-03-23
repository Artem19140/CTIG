<script setup lang="ts">
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import StudentExamsList from './StudentExamsList.vue';
import StudentActionsDropdown from './StudentActionsDropdown.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import type { Student } from '../../../interfaces/interfaces';
import { useApi } from '../../../Composables/Api/useApi';

const props = defineProps<{
    studentId?:number
}>()

const isOpen = defineModel<boolean>({default:false})
const student = ref<Student | null>(null)

const {data, loading,request, error} = useApi()

onMounted(async() => {
    if(!props.studentId) return
    
    await request(() =>  axios.get(`/students/${props.studentId}?profile=true`))

    if(!error.value && data.value){
        student.value = data.value.data
    }
})

const showDocument = (url :string) => {
    if(!url) return
    window.open(`/files?path=${url}`)
}
</script>

<template>
    <BaseDialog 
        width="700"
        height="900"
        :title="`Карточка студента (ID ${student?.id ?? ''})`"
        :loading="!student || loading"
        v-model="isOpen"
        @before-close="(done) => done()"
        skeleton="avatar, heading, paragraph, paragraph, divider, list-item-two-line, list-item-two-line, list-item-two-line, list-item-two-line, divider, image, divider, table"
    >
        <template #titleActions>
            <StudentActionsDropdown 
                :student="student"
            />
        </template>

        <v-card-text>
            <v-card-text>
                <div class="flex">
                    <v-avatar color="surface-variant cursor-pointer hover:opacity-80 transition-opacity"  size="150" >
                        <v-img 
                            :src="student?.photoPath"
                            cover 
                            @click="showDocument('')"
                        />
                    </v-avatar>
                <div class="flex flex-col justify-center ml-8">
                    <div class="text-headline-small">{{`${student?.surname} ${student?.name} ${student?.patronymic}`}}</div>
                    <div class="text-subtitle-1">{{`${student?.surnameLatin} ${student?.nameLatin} ${student?.patronymicLatin}`}}</div>
                    <div class="text-subtitle-2">{{student?.dateBirth}}</div> 
                </div>
                </div>
            </v-card-text>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <v-list-item-title>{{`${student?.fullPassport ?? ''}, выдан ${student?.issuedDate ?? ''} (${student?.issuedBy ?? ''})`}}</v-list-item-title>
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
                    <v-list-item-title v-if="!student?.passportScan">-</v-list-item-title>
                    <v-img
                        v-else 
                        :width="50"
                        class="mt-4 cursor-pointer hover:opacity-80 transition-opacity"
                        src="https://cdn-icons-png.flaticon.com/512/9034/9034536.png"
                        @click="showDocument(student?.passportScan)"
                    ></v-img>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <StudentExamsList :exams="student?.exams" />
        </v-card-text>
    </BaseDialog>
</template>