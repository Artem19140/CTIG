<script setup lang="ts">
import { router, useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { AttemptAnswer } from '@/interfaces/Task';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { AttemptChecking } from '@/interfaces/Attempt';
import AttemptCheckingPanel from '@/components/Attempt/AttemptCheckingPanel.vue';

const isOpen = defineModel<boolean>({default:false})

const props = defineProps<{
    attemptId:number | null
}>()

const attempt = ref<AttemptChecking | null>(null)

const update = (value:AttemptAnswer) => {
    if(!attempt.value) return
    const task = attempt.value?.tasks.find(t => t.attemptAnswer.id === value.id)
    if(!task) return
    task.attemptAnswer = {...value}
}

const http = useHttp()

const getAttemptTasks = () => {
    http.get(`/attempts/${props.attemptId}/checking`,{
        onSuccess:(response :any) => {
            attempt.value = response.data
        }
    })
}

const finishChecking = () => {
    http.post(`/attempts/${props.attemptId}/checking/finish`,{
        onSuccess:()=>{
            router.reload()
            isOpen.value = false
        }
    })
}

onMounted(() => {
    getAttemptTasks()
})

</script>

<template>
    <BaseDialog
        v-model="isOpen"
        fullscreen
        :error="!http"
        :loading="http.processing"
        :onRetry="getAttemptTasks"
        @before-close="(close) => {
            http.cancel()
            close()
        } "
    >
        <AttemptCheckingPanel 
            v-if="attempt"
            :attempt="attempt"
            @rated="update"
        />

        <template #actions>
            <span class="bg-red p-1.5 rounded" v-if="attempt?.checkedAt">Попытка проверена. Изменения недоступны.</span>
            <AppPrimaryButton 
                :loading="http.processing"
                :disabled="http.processing || attempt?.checkedAt"
                @click="finishChecking"
                text="Завершить проверку"
            />
        </template>
    </BaseDialog>
</template>