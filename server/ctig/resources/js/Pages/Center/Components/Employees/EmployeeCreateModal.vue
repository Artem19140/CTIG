<script setup lang="ts">
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import {router, useHttp} from '@inertiajs/vue3';
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import EmployeeForm from './EmployeeForm.vue';
import { EmployeeFormI } from '@/interfaces/Employee';

const isOpen = defineModel<boolean>({default:false})

const http = useHttp<EmployeeFormI & {password:string, password_confirmation:string}>({
    surname:'',
    name:'',
    patronymic:'',
    jobTitle:'',
    roles:[],
    email:'',
    password:'',
    password_confirmation:''
})

const canClose = async (fn: () => void) => {
    if(http.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить добавление?')
        if(!ok) return
    }
    http.resetAndClearErrors()
    fn()
}

const create = () => {
    http.post('/employees',{
        onSuccess:() => {
            router.reload()
            isOpen.value=false
            http.resetAndClearErrors()
            const {add} = useSnackbarQueue()
            add('Сотрудник добавлен', 'green')
        }
    })
}
</script>

<template>
    <BaseDialog 
        width="500"
        title='Добавить'
        v-model="isOpen"
        @before-close="(done) => canClose(done)"
    >
        <EmployeeForm 
            v-model:form="http"
            :errors="http.errors"
            :loading="http.processing"
        />

        <AppPasswordConfirmation
            v-model:password="http.password"
            v-model:password-confirmation="http.password_confirmation"
            :password-attr="{'error-messages':http?.errors?.password}"
            :password-confirmation-attr="{'error-messages':http?.errors?.password_confirmation}"
        />
        <template #actions>
            <div>
                <AppAddButton 
                    text="Добавить"
                    @click="create"
                    :loading="http.processing"
                    :disabled="http.processing"
                />
            </div>
        </template>

    </BaseDialog>
</template>