<script setup lang="ts">
import { ref } from 'vue';
import AddButton from '../../Components/AddButton/AddButton.vue';
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import AppInput from '../../Components/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3';
import { useConfirmDialog } from '../../Composables/useConfirmDialog';

const isOpen = ref<boolean>(false)

const form = useForm({
    surname:'',
    name:'',
    patronymic:'',
    jobTitle:'',
    roles:[],
    email:'',
    password:'',

})

const checkEmpty = async (fn: () => void) => {
    if(form.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить добавление?')
        if(!ok) return
    }
    form.resetAndClearErrors()
    fn()
}

const create = () => {
    form.post('/employees',{
        onSuccess:(page) => {
            isOpen.value=false
            form.resetAndClearErrors()
        }
    })
}
</script>

<template>
    <AddButton text="Добавить" @click="isOpen = true" />
    <BaseDialog 
        width="500"
        title='Добавить'
        v-model="isOpen"
        @before-close="(done) => checkEmpty(done)"
    >
        <AppInput 
            label="Фамилия"
            v-model="form.surname"
            :error-messages="form?.errors?.surname"
        />
        <AppInput 
            label="Имя"
            v-model="form.name"
            :error-messages="form?.errors?.name"
        />
        <AppInput 
            label="Отчество"
            v-model="form.patronymic"
            :error-messages="form?.errors?.patronymic"
        />

        <AppInput 
            label="Должность"
            v-model="form.jobTitle"
            :error-messages="form?.errors?.jobTitle"
        />

        <v-select 
            label="Роли"
            v-model="form.roles"
            :error-messages="form?.errors?.roles"
        />

        <AppInput 
            label="Логин"
            v-model="form.email"
            :error-messages="form?.errors?.email"
        />

        <AppInput 
            label="Пароль"
            v-model="form.password"
            :error-messages="form?.errors?.password"
        />
        <template #actions="{ close }">
            <div>
                <AddButton 
                    text="Добавить"
                    @click="create"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
                <v-btn
                    @click="close"
                >
                    Отменить
                </v-btn>
            </div>
        </template>

    </BaseDialog>
</template>