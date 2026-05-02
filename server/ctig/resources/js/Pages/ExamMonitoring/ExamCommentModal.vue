<script setup lang="ts">
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { useHttp } from '@inertiajs/vue3';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';
import { ExamMonitoring } from '@/interfaces/Exam';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';

const props = defineProps<{
    exam: ExamMonitoring
}>()

const isOpen = defineModel<boolean>({default:false})

const http = useHttp({
    protocolComment:props.exam.protocolComment ?? ''
})

const send = () => {
    http.put(`/exams/${props.exam.id}/monitoring/protocol-comments`,{
        onSuccess:() => {
            const {add} = useSnackbarQueue()
            add('Комментарий добавлен', 'green')
        }
    })
}

const beforeClose = async (fn:() => void ) => {
    if(http.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Закрыть окно? Изменения не сохранятся')
        if(!ok) return
    }
    http.resetAndClearErrors()
    isOpen.value = false
    fn()
}
</script>

<template>
    <BaseDialog 
        width="500"
        v-model="isOpen"
        @before-close="(close) => beforeClose(close)"
        title="Комментарий"
    >
        <AppTextarea
            v-model="http.protocolComment"
            maxlength="1000"
            :error-messages="http.errors.protocolComment"
            label="Введите комментарий или нарушение"
            hint="Поле автоматически увеличится"
        />
        <template #actions>
            <AppPrimaryButton
                text="Добавить"
                :disabled="http.processing || !http.isDirty"
                :loading="http.processing"
                @click="send"
            />
        </template>
    </BaseDialog>
</template>