<script setup lang="ts">
    import { ref } from 'vue';
    import contries from '../../../contries.json'
    const student = ref({
        surname: '',
        name: '',
        patronymic: '',          
        dateBirth: '',           
        surnameLatin: '',
        nameLatin: '',
        patronymicLatin: '',     
        passportNumber: '',
        passportSeries: '',
        issuedBy: '',
        issuesDate: '',          
        addressReg: '',
        migrationCardRequisite: '',
        citizenship: '',         
        phone: '',
        noPatronymic:false
    })
    const isOpen = ref(true)

    const send = async () => {
        console.log(1)
        const token = '1|ou7UhAtGpkTlK9Ab77AOtYqFlmzlkUjJmiIgHC442b88747a'
        try {
            const response = await fetch('https://ctig.local/api/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(student.value)
            })
            const data = await response.json()
            console.log(data)
            alert('Студент успешно сохранён!')
        } catch (err) {
            console.error('Ошибка при сохранении студента', err)
        }
    }

    const contriesList = ref(contries)
    const filteredContries = ref([...contriesList.value])

    const filterFn = (val:string, update:(callback: () => void) => void) => {
        update(() => {
            const needle = val.toLocaleLowerCase()
            filteredContries.value = contriesList.value.filter(
                v => v.name.toLocaleLowerCase().includes(needle)
            )
        })
    }
</script>

<template>
    <q-dialog
        no-shake
        no-backdrop-dismiss
        v-model="isOpen"
        no-esc-dismiss
        >
            <q-card >
                <q-space />
                <q-btn flat v-close-popup round dense icon="close" />
                <q-card-section style="min-width: 500px" class="q-pa-md q-gutter-sm">
                    <q-input filled v-model="student.surname" label="Фамилия"/>
                    <q-input filled v-model="student.name" label="Имя"/>
                    <q-input filled v-model="student.patronymic" label="Отчество"/>
                    <q-checkbox v-model="student.noPatronymic" label="Нет отчества" />
                    <q-input filled v-model="student.surnameLatin" label="Фамилия(лат)"/>
                    <q-input filled v-model="student.nameLatin" label="Имя(лат)"/>
                    <q-input filled v-model="student.patronymicLatin" label="Отчество(лат)"/>
                    <q-input filled v-model="student.dateBirth" label="Дата рождения" type="date" />
                    <q-select
                        filled
                        v-model="student.citizenship"
                        label="Гражданство" 
                        :options="filteredContries"
                        input-debounce="0"
                        clearable
                        use-input
                        emit-value
                        map-options
                        option-label="name"
                        autocomplete="name"
                        option-value="code"
                        @filter="filterFn"
                    />
                    <q-input filled v-model="student.passportSeries" label="Серия паспорта" />
                    <q-input filled v-model="student.passportNumber" label="Номер паспорта" />
                    <q-input filled v-model="student.issuedBy" label="Кем выдан" />
                    <q-input filled v-model="student.issuesDate" label="Дата выдачи" type="date" />
                    <q-input filled v-model="student.addressReg" label="Адрес регистрации" />
                    <q-input filled v-model="student.migrationCardRequisite" label="Реквизиты миграционной карты" />
                    
                    <q-input filled v-model="student.phone" label="Телефон" />
                    
                </q-card-section>
                <q-card-section class="row justify-center q-gutter-x-md">
                    <q-btn color="positive" label="Сохранить" @click="send" />
                    <q-btn label="Отменить" v-close-popup />
                </q-card-section>
           
            </q-card>
           
    </q-dialog>
    
</template>