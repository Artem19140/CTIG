<script setup lang="ts">
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';
import AppRetryAlert from '@/components/UI/AppRetryAlert/AppRetryAlert.vue';
import { AttemptAnswer, Task } from '@/interfaces/Task';
import { useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    task:Task
}>()
const emit = defineEmits<{
    (e:'rated', value:AttemptAnswer):void
}>()

const answerId = props.task?.attemptAnswer?.id

const http = useHttp<{mark:number | null}, {attemptAnswer:AttemptAnswer}>({
    mark: props.task.attemptAnswer.mark
})

watch(() => http.mark, () => {
    if(http.mark === null) return
    rate()
})

const rate = () => {
    http.put(`/answers/${answerId}/rate`,{
        onSuccess:(response)=>{
            emit('rated', response.attemptAnswer)
        }
    })
}

const loading = computed(() => http.processing)

const markSaved = computed(() => 
    props.task.attemptAnswer.checkedAt !== null 
)

const marks = ref<Array<number>>([])

onMounted(() => {
    for(let i=0;i<=props.task.mark; i++){
        marks.value.push(i)
    }
})
</script>

<template>
    <div class="d-flex flex-column">
        <AppAutocomplete
            :label="`Выберите балл от 0 до ${task.mark}`"
            :items="marks"
            v-model="http.mark"
            item-title="mark"
            :disabled="loading"
            :base-color="markSaved ? 'green' : ''"
            :error-messages="http.errors.mark"
        />

        <div class="d-flex align-center ga-2" v-if="loading">
            <AppProgressCircular size="18" width="2" />
            <span>Сохранение...</span>
        </div>
        <div v-else>
            <v-alert
                v-if="markSaved"
                type="success"
                density="compact"
                variant="tonal"
            >
                Результат успешно сохранен
            </v-alert>
            <AppRetryAlert
                v-if="!http.wasSuccessful"
                text="Не удалось сохранить балл"
                :onRetry="rate"
            />
        </div>          
    </div>
</template>