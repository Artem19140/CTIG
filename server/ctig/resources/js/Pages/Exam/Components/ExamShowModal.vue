<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';
import { formatterDate } from '../../../Helpers/heplers';
import { formatterTime } from '../../../Helpers/heplers';
import StudentTable from '../../Students/Components/StudentTable.vue';

const isOpen = defineModel<boolean>()
const props = defineProps<{ id: number | undefined }>()
const examData = ref()
const loading = ref(true)

const close = () => {
    isOpen.value = false
    loading.value = false
    examData.value = null 
}

watch(isOpen, async () => {
  if (props.id) {
        if(!isOpen.value){
                return
            }
        loading.value = true
        examData.value = await fetchData(props.id)
        loading.value = false  
  }
}, { immediate: true })

async function fetchData(id: number) {
  const res = await axios.get(`/exams/${id}`)
  return res.data.data
}

</script>

<template>
        <v-dialog persistent v-model="isOpen"  max-width="800">
            <v-skeleton-loader
                type="avatar, heading, paragraph, paragraph"
                v-if="loading"
                height="100%"
                max-width="700"
            ></v-skeleton-loader>
            <v-card class="pl-4 pr-4" >
                <div v-if="examData && !loading">    
                    <v-card-text>
                        <v-card-title class="text-h6">
                            Экзамен  
                        </v-card-title>
                        <v-card-subtitle>
                            {{ `${examData?.sessionNumber ?? '-'} / ${examData?.group ?? '-'}` }}
                        </v-card-subtitle>
                    </v-card-text> 
                    <v-card-text>
                    <v-list>
                        <v-list-item>  
                            <v-list-item-subtitle>Тип</v-list-item-subtitle>
                            <v-list-item-title>{{examData?.name}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item> 
                            <v-list-item-subtitle> Дата и время</v-list-item-subtitle>
                            <v-list-item-title>{{`${formatterTime(examData?.beginTime)},  ${formatterDate(examData?.beginTime)} `}}</v-list-item-title>
                        </v-list-item>
                        
                        <v-list-item>  
                            <v-list-item-subtitle>Адрес </v-list-item-subtitle>
                            <v-list-item-title>{{examData?.address}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-subtitle>Тестеры</v-list-item-subtitle>
                            <v-list-item-title>{{examData?.testers}}</v-list-item-title>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-subtitle>Комментарий</v-list-item-subtitle>
                            <v-list-item-title>{{examData?.comment ?? '-'}}</v-list-item-title>
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
                                <StudentTable :students="examData.students" width="500" />
                            </v-list-item>
                        </v-list>
                    </v-card-text>

                </div>
                
                <v-card-actions>
                    <v-btn text @click="close">Закрыть</v-btn>
                </v-card-actions>
                
            </v-card>
        </v-dialog>
</template>