<script setup lang="ts">
import { ref } from 'vue';
const isOpen = ref(true)   
const examTypes = ref([
    {name:'ВНЖ', id:1},
    {name:'РВП', id:2},
    {name:'Гражданство', id:3}
])

const adresses = ref([
    {name:'Ижевск, ул Удмуртская 1, корпус 2, ауд 112', id:1},
    {name:'Ижевск, ул Удмуртская 1, корпус 2, ауд 114', id:1}
])
const exam = ref({
    dateTime:"",
    examTypeId:null,
    address:null
})

const show = () => {
    console.log(exam.value)
}
</script>

<template>
    <q-dialog
        no-shake
        no-backdrop-dismiss
        v-model="isOpen"
        no-esc-dismiss
        >
        <q-card style="max-width: 500px;">
            <q-card-section class="q-pa-md q-gutter-sm">
                <q-select
                    use-input
                    filled
                    emit-value
                    map-options
                    option-label="name"
                    option-value="id"
                    v-model="exam.examTypeId" 
                    :options="examTypes" 
                    label="Тип экзамена" 
                />
                
                <q-input filled v-model="exam.dateTime"  label="Дата и время" type="datetime-local" />
                <q-input filled v-model="exam.dateTime"  label="Количество студентов" type="number" />
                <q-select
                    use-input
                    filled
                    emit-value
                    map-options
                    option-label="name"
                    option-value="id"
                    v-model="exam.address" 
                    :options="adresses" 
                    label="Адрес проведения" 
                />
            </q-card-section>
            <q-card-actions class="row justify-center q-gutter-x-md">
                <q-btn color="positive" label="Создать" @click="show" />
                <q-btn label="Отменить" @click="show" />
            </q-card-actions>
        </q-card>
        
    </q-dialog>
</template>

