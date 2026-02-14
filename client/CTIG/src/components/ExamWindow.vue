<script setup lang="ts">
import { ref } from 'vue';
//import students from './students.json';
import examData from './exam.json';
import type { QTableColumn } from 'quasar'
const isOpen = ref(true)
const exam = ref(examData)
const columns: QTableColumn[] = [
    {
    name: 'id',
    label: 'ID',
    field: 'id',
    align:'center'
  },
  {
    name: 'surname',
    label: 'Фамилия',
    field: 'surname',
    sortable: true,
    align:'left'
  },
  {
    name: 'name',
    label: 'Имя',
    field: 'name',
    sortable: true,
    align:'left'
  },
  {
    name: 'passportSeries',
    label: 'Серия паспорта',
    field: 'passportSeries',
    align:'center'
  },
  {
    name: 'passportNumber',
    label: 'Номер паспорта',
    field: 'passportNumber',
    align:'center'
  },
  
]

const formatDateTime = (dateStr:string) => {
    return new Date(dateStr).toLocaleString('ru-RU', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  }

const examStatus = [
    {name:"expected", color:"secondary", label:"Ожидается"}
]
</script>

<template>
    <q-dialog
    
        no-shake
        no-backdrop-dismiss
        v-model="isOpen"
        no-esc-dismiss
    >
        <q-card style="min-width: 1000px;"  class="q-pa-sm">
            <q-card-section>
                <div><strong>Сессия</strong> {{ exam.sessionNumber }} / <strong>группа</strong> {{ exam.group }}</div>
                <q-badge :color="examStatus.find(s => s.name === exam.status)?.color" 
                        class="q-mb-xs">
                {{ examStatus.find(s => s.name === exam.status)?.label }}
                </q-badge>
                <div><strong>{{ exam.name }}</strong></div>
                <q-separator />
                <div><strong>Дата и время:</strong> {{formatDateTime(exam.beginTime)}}</div>
                <div><strong>Место:</strong> {{ exam.address }}</div>
                <div><strong>Тестеры:</strong> {{ exam.testers.map(t => `${t.surname} ${t.name[0]}.${t.patronymic[0]}.`).join(', ') }}</div>
                <div><strong>Комментарий:</strong> {{ exam.comment }}</div>
                <div>{{ exam.students.length }}/{{ exam.capacity }}</div>
                
            </q-card-section>
            <q-card-section>
                <q-table
                    flat bordered
                    title="Записанные студенты"
                    :rows="exam.students"
                    :columns="columns"
                    row-key="id"
                    hide-bottom
                    :pagination="{ rowsPerPage: 0 }"
                />
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

