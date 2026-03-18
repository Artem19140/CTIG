<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    exam:any,
    duration:number,
    minMark:number, 
    attempt:any,
    tasksCount : number
}>()
const page = usePage()

const name = computed(() => {
    return `${page.props?.auth?.user?.surname} ${page.props?.auth?.user?.name}`
})

const begin = () => {
    router.put(`/exam-attempts/${props.attempt.id}`)
}
</script>

<template>
    <v-card
        class="mx-auto mt-32"
        :title="`Добро пожаловать, ${name}!`"
        width="700"
    >
        <v-card-title>

        </v-card-title>
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
            
            <v-btn
                @click="begin"
                color="primary"
                variant="flat"
                size="large"
            >
                Начать экзамен
            </v-btn>
        </v-card-actions>
    </v-card>

</template>