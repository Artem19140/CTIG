<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Attempt, Enrollment } from '@/interfaces/Interfaces';
import { onMounted, ref } from 'vue';
import TasksList from '../Attempt/Components/tasks/TasksList.vue';
import { useHttp } from '@inertiajs/vue3';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})
const attempt = ref<Attempt | null>(null)

const http = useHttp()
onMounted(() => {
    http.get(`/attempts/${props.enrollment.attempt?.id}/tasks/speaking`,{
        onSuccess:(response : any) => {
            attempt.value=response.data
        }
    })
})
</script>

<template>
    <BaseDialog
        fullscreen
        v-model="isOpen"
        @before-close="(close) => close()"
        :title="`Говорение ( ${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport} )`"
    >
        <div class="flex flex-column gap-16 items-center ">
            <TasksList v-if="attempt" :attempt="attempt" />
        </div>
        
    </BaseDialog>
</template>