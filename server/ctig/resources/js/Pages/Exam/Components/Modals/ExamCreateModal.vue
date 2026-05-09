<script setup lang="ts">
import { router, useHttp } from '@inertiajs/vue3'
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import ExamCreateForm from './ExamCreateForm.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import { ExamForm } from '@/interfaces/Exam';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';
import { ref } from 'vue';

const props = defineProps<{
    date?:string
}>()
const isOpen = defineModel<boolean>({default:false})

const http = useHttp<ExamForm>({
    examTypeId: null,
    addressId:null,
    comment:'',
    examiners:[],
    time:null,
    date:props.date ?? null,
    capacity:null
})
const form = ref()
const create = async () => {
    const {valid} = await  form.value.validate()
    if(!valid) return

    http.post('/exams', {
    onSuccess: (response:any) => {
        http.resetAndClearErrors()
        isOpen.value = false
        router.reload()
        const {add} = useSnackbarQueue()
        add('Экзамен создан', 'green')
    },
    })   
}
</script>

<template>
    <BaseDialog 
        width="500"
        v-model="isOpen"
        @before-close="async (close) => {
            if(http.isDirty){
                const {confirmOpen} = useConfirmDialog()
                if(! await confirmOpen('Отменить добавление экзамена?') ){
                    return
                }
            }
            http.resetAndClearErrors()
            http.cancel()
            close()
        }"
    >
        <template #title>
            <div class="flex gap-2">
                Добавление экзамена
                <AppTooltip 
                    text="Создать экзамен возможно минимум за 3 часа до его начала"
                />
            </div>
        </template>
        <v-form ref="form">
            <ExamCreateForm :form="http" />
        </v-form>
        <template #actions >
            <AppAddButton  
                text="Добавить"
                @click="create"
                :disabled="http.processing"
                :loading="http.processing"
            />
        </template>
    </BaseDialog>
</template>