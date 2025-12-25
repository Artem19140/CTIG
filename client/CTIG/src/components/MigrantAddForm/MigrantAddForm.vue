<script setup lang="ts">
    import { ref } from 'vue';
    import Combobox from '../ui/Combobox.vue';
    import TextField from '../ui/TextField.vue';

    const items = ref(["Узбекистан", "Таджикистан", "Азербайджан"])
    const migrantData = ref({
        regNumber:0,
        citizenship:"",
        surname:"",
        name:"",
        patronymic:"",
        surnameLatin:"",
        nameLatin:"",
        patronymicLatin:"",
        comment:"",
        passportNumber:"",
        passportSeries:""
    })
    const loading = ref(false) 
    const clickw = () => {
        console.log(migrantData.value)
    }
</script>

<template>
    <div class="d-flex justify-sm-center">
        <form class="d-flex justify-sm-center w-50">  
            <div class="w-75"> 
                <v-card class="mx-auto pa-3" >
                    <TextField v-model="migrantData.regNumber" :rules="['requried']" label="Регистрационный номер"/>
                    <TextField v-model="migrantData.surname" label="Фамилия"/>
                    <TextField v-model="migrantData.name" label="Имя"/>
                    <TextField v-model="migrantData.patronymic" label="Отчетсво"/>
                    <TextField v-model="migrantData.surnameLatin" label="Фамилия латиница"/>
                    <TextField v-model="migrantData.nameLatin" label="Имя латиница"/>
                    <TextField v-model="migrantData.patronymicLatin" label="Отчетсво латиница"/>
                    <TextField v-model="migrantData.nameLatin" label="Серия паспорта"/>
                    <TextField v-model="migrantData.passportSeries" label="Номер паспорта"/>
                    <TextField v-model="migrantData.passportSeries" type="date" label="Дата рождения" />
                    <TextField v-model="migrantData.passportSeries" label="Реквизиты миграционной карты"/>
                    <TextField v-model="migrantData.passportSeries" type="time" label="Время экзамена" />
                    <Combobox :items="items" v-model="migrantData.citizenship"  label="Гражданство"/>
                    <v-textarea label="Комментарий" v-model="migrantData.comment" />
                    <div class="d-flex justify-sm-center ga-4">
                        <v-btn  color="success" :loading="loading" @click="clickw">Добавить</v-btn>
                        <v-btn  to="/migrants" color="error" variant="outlined" >Отменить</v-btn>
                    </div>
                </v-card>
            </div>
        </form>
    </div>
</template>

<style></style>