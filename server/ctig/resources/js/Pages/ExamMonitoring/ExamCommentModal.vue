<script setup lang="ts">
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useForm } from '@inertiajs/vue3';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Exam } from '@interfaces/Interfaces';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';

const props = defineProps<{
    exam: Exam
}>()

const isOpen = defineModel<boolean>({default:false})

const form = useForm({
    protocolComment:props.exam.protocolComment ?? ''
})

const send = () => {
    form.put(`/exams/${props.exam.id}/monitoring/protocol-comments`,{
        onSuccess:(page) => {

        }
    })
}

const beforeClose = async (fn:() => void ) => {
    if(form.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Закрыть окно? Изменения не сохранятся')
        if(!ok) return
    }
    form.resetAndClearErrors()
    isOpen.value = false
}
</script>

<template>
    <BaseDialog 
        width="500"
        v-model="isOpen"
        @before-close="(done) => beforeClose(done)"
        title="Комментарий"
    >
        <AppTextarea
            v-model="form.protocolComment"
            maxlength="1000"
            :error-messages="form.errors.protocolComment"
            label="Введите комментарий или нарушение"
            hint="Поле автоматически увеличится"
        />
        <template #actions>
            <AppPrimaryButton
                text="Добавить"
                :disabled="form.processing"
                :loading="form.processing"
                @click="send"
            />
        </template>
    </BaseDialog>
</template>