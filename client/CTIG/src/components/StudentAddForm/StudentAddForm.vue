<script setup lang="ts">
    import { ref } from 'vue';
    import Combobox from '../ui/Combobox.vue';
    import TextField from '../ui/TextField.vue';

    const items = ref(["Узбекистан", "Таджикистан", "Азербайджан"])
    const student = ref({
        citizenship:"",
        surname:"",
        name:"",
        patronymic:"",
        surnameLatin:"",
        nameLatin:"",
        patronymicLatin:"",
        passportNumber:"",
        passportSeries:""
    })
    const loading = ref(false) 
    const clickw = () => {
        console.log(student.value)
        fetch('https://ctig.local/exams', { credentials: 'include' })
        .then(r => r.json())
        .then(d => console.log(d));
    }
</script>

<template>
    <v-dialog>
    <div class="d-flex justify-sm-center">
        <form class="d-flex justify-sm-center w-50">  
            <div class="w-75"> 
                <v-card class="mx-auto pa-3" >
                    <TextField v-model="student.surname" label="Фамилия"/>
                    <TextField v-model="student.name" label="Имя"/>
                    <TextField v-model="student.patronymic" label="Отчетсво"/>
                    <TextField v-model="student.surnameLatin" label="Фамилия латиница"/>
                    <TextField v-model="student.nameLatin" label="Имя латиница"/>
                    <TextField v-model="student.patronymicLatin" label="Отчетсво латиница"/>
                    <TextField v-model="student.nameLatin" label="Серия паспорта"/>
                    <TextField v-model="student.passportSeries" label="Номер паспорта"/>
                    <TextField v-model="student.passportSeries" type="date" label="Дата рождения" />
                    <TextField v-model="student.passportSeries" label="Реквизиты миграционной карты"/>
                    <Combobox :items="items" v-model="student.citizenship"  label="Гражданство"/>
                    <div class="d-flex justify-sm-center ga-4">
                        <v-btn  color="success" :loading="loading" @click="clickw">Добавить</v-btn>
                        <v-btn  to="/migrants" color="error" variant="outlined" >Отменить</v-btn>
                    </div>
                </v-card>
            </div>
        </form>
    </div>
    </v-dialog>
</template>

<style></style>