<script setup lang="ts">
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { Attempt, Exam } from '@/interfaces/Interfaces';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useAuth } from '@/composables/useAuth';


const props = defineProps<{
    exam:{
        data:Exam
    },
    duration:number,
    minMark:number, 
    attempt:Attempt,
    tasksCount : number
}>()

const { user } = useAuth()

const name = computed(() => {
    return `${user.surname} ${user.name}`
})

const form = useForm()

const begin = () => {
    form.put(`/exam-attempts/${props.attempt.id}`)
}


</script>

<template>
    <v-card
        class="mx-auto mt-32"
        :title="`Добро пожаловать, ${name}!`"
        width="700"
    >
        <v-card-text>
                <div class="mb-2 ">Название экзамена: <strong>{{ exam.data?.name }}</strong></div>
                <div class="mb-2 ">Количество попыток: <strong>1</strong></div>
                <div class="mb-2 ">Количество заданий: <strong>{{ tasksCount }}</strong></div>
                <div class="mb-2">Время экзамена: <strong>{{ duration }}</strong> минут</div>
                <div>Минимальный балл: <strong>{{ minMark }}</strong></div>
        </v-card-text>
        <v-card-text>
                <div class="text-error font-weight-bold mb-2"><strong>Внимание!</strong></div>
                <div class="text-wrap">За нарушение правил проведения экзамена, Вы будете <strong>удалены</strong> без права пересдачи!</div>
        </v-card-text>
        <v-card-actions class="flex justify-center">
            <AppPrimaryButton
                @click="begin"
                :disabled="form.processing"
                :loading="form.processing"
                variant="flat"
                size="large"
                text="Начать экзамен"
            />
        </v-card-actions>
    </v-card>
</template>