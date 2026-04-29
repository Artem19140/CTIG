<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Attempt, Enrollment } from '@/interfaces/Interfaces';
import { computed, onMounted, ref } from 'vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { router, useHttp } from '@inertiajs/vue3';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import BaseEmptyState from '@/components/BaseComponents/BaseEmptyState/BaseEmptyState.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})
const loading = ref<boolean>(false)
const attempt = ref<Attempt | null>(null)
const speakingStarted = computed(() => props.enrollment?.attempt?.speakingStartedAt)
const checking = ref<boolean>(false)

const http = useHttp()

onMounted(() => {
    if(!speakingStarted.value) return
    getSpeaking()
})

const getSpeaking = () => {
    loading.value = true
    http.get(`/attempts/${props.enrollment.attempt?.id}/speaking`,{
        onSuccess:(response : any) => {
            attempt.value=response.data
        },
        onFinish() {
            loading.value = false
        },
    })
}


const startHttp = useHttp()

const start = () => {
    loading.value = true
    startHttp.post(`/attempts/${props.enrollment.attempt?.id}/speaking/start`,{
        onSuccess:(response : any)=>{
            getSpeaking()
            props.enrollment.attempt = {... response.data}
        },
        onFinish() {
            loading.value = false
        },
    })
}    

const finishHttp = useHttp()
const finish = () => {
    finishHttp.post(`/attempts/${props.enrollment.attempt?.id}/speaking/finish`,{
        onSuccess:()=> {
            
            isOpen.value = false
            router.reload()
        }
    })
}
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        :loading="loading"
        @before-close="(close) => close()"
        loading-text="Идет получение заданий говорения "
        :title="`Говорение ( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        <div 
            class="flex flex-column items-center gap-8 mt-2 mb-2"
            v-if="speakingStarted"
        >
            <TasksList 
                v-if="attempt" 
                :attempt="attempt" 
                :checking="checking"
            />
        </div>

        <BaseEmptyState 
            v-else
            action-text="Начать"
            icon="mdi-account-clock-outline"
            title="Говорение еще не начато"
            text="Нажмите, чтобы начать"
            @click:action="start"
        />

        <template #actions>
            <AppPrimaryButton
                v-if="!checking"
                text="Продолжить"
                @click="checking = true"
            />
            <div v-if="checking" class="flex gap-2">
            
            <AppPrimaryButton
                :loading="finishHttp.processing"
                text="Оценить позднее"
                @click="finish"
            />

            <AppPrimaryButton
                text="Завершить"
                :loading="finishHttp.processing"
                @click="finish"
            />
            </div>
            
        </template>
    </BaseDialog>
</template>