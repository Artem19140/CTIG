<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Employee, EmployeeFormI } from '@/interfaces/Employee';
import { router, useHttp } from '@inertiajs/vue3';
import EmployeeForm from './EmployeeForm.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';

const props = defineProps<{
    employee: Employee
}>()

const employee = props.employee

const http = useHttp <EmployeeFormI>({
    surname:employee.surname,
    name:employee.name,
    patronymic:employee.patronymic,
    jobTitle:employee.jobTitle,
    roles:employee.roles.map(r => r.id),
    email:employee.email,
})

const edit = () => {
    http.put(`/employees/${employee.id}`,{
        onSuccess() {
            isOpen.value = false
            router.reload()
        },
    })
}

const isOpen = defineModel<boolean>({default:false})
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        width="500"
        title="Редактирование данных сотрудника"
        @before-close=" async (close) => {
            if(http.isDirty){
                const {confirmOpen} = useConfirmDialog()
                const ok = await confirmOpen('Отменить добавление?')
                if(!ok) return
            }
            http.resetAndClearErrors()
            close()
        }"
    >
        <EmployeeForm 
            v-model:form="http"
            :errors="http.errors"
            :loading="http.processing"
        />
        <template #actions>
            <AppPrimaryButton
                @click="edit"
                text="Сохранить"
                :loading="http.processing"
                :disabled="http.processing || !http.isDirty"
            />
        </template>
    </BaseDialog>
</template>