<script setup lang="ts">
import { ref, watch } from 'vue';
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import axios from 'axios';
import { formatterDate } from '../../../Helpers/heplers';

const isOpen = defineModel<boolean>()
const props = defineProps<{ id: number | undefined }>()
const studentData = ref()
const loading = ref(true)

const close = () => {
    isOpen.value = false
    loading.value = false
    studentData.value = null 
}

watch(isOpen, async () => {
  if (props.id) {
        if(!isOpen.value){
            return
        }
        loading.value = true
        studentData.value = await fetchData(props.id)
        loading.value = false  
  }
}, { immediate: true })

async function fetchData(id: number) {
  const res = await axios.get(`/students/${id}`)
  return res.data.data
}

</script>

<template>
        <v-dialog persistent v-model="isOpen"  max-width="700">
            <v-skeleton-loader
                type="avatar, heading, paragraph, paragraph"
                v-if="loading"
                height="100%"
                max-width="700"
            ></v-skeleton-loader>
            <v-card class="pl-4 pr-4" >
                <div v-if="studentData && !loading">     
                <v-card-text>
                    <v-card-title class="text-h6">
                        Карточка студента (ID {{ studentData?.id }})
                    </v-card-title>
                    <v-card-text>
                        <div class="flex">
                            <v-avatar color="surface-variant" size="150">
                                <v-img src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg" cover />
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
                            <v-list-item-title>{{`${studentData?.passportSeries} ${studentData?.passportNumber}, выдан ${formatterDate(studentData?.issuedDate)} (${studentData?.issuedBy})`}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item>  
                            <v-list-item-subtitle>Миграционная карта</v-list-item-subtitle>
                            <v-list-item-title>{{studentData?.migrationCardRequisite}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item>  
                            <v-list-item-subtitle>Адрес регистрации</v-list-item-subtitle>
                            <v-list-item-title>{{studentData?.addressReg}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-subtitle>Номер телефона</v-list-item-subtitle>
                            <v-list-item-title>{{studentData?.phone}}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-text>
                    
                </v-card-text>
                </div>
                <v-card-actions>
                    <v-btn text @click="close">Закрыть</v-btn>
                </v-card-actions>
                
            </v-card>
        </v-dialog>
</template>