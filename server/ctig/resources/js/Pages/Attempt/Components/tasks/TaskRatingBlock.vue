<script setup lang="ts">
import AppAutocomplete from '@/components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';
import { Task } from '@/interfaces/Task';
import { useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    task:Task
}>()
const mark = computed(() => props.task.attemptAnswer.mark)

const answerId = props.task?.attemptAnswer?.id

const http = useHttp({
    mark: mark.value
})

watch(() => http.mark, () => {
    if(http.mark === null) return
    rate()
})

const error = ref<boolean>(false)

const rate = () => {
    error.value = false
    http.put(`/answers/${answerId}/rate`,{
        onSuccess:(response:any)=>{

        },
        onFinish() {
            if(!http.wasSuccessful){
                error.value = true
            }
        },
    })
}

const loading = computed(() => http.processing)

const markSaved = computed(() => 
    mark.value !== null || http.wasSuccessful 
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

            <v-alert
                v-if="error"
                class="mt-2"
                type="error"
                variant="tonal"
                density="compact"
            >
                <div class="d-flex align-center justify-space-between">
                    <span>Не удалось сохранить данные</span>
                    <v-btn
                        size="small"
                        color="error"
                        variant="outlined"
                        prepend-icon="mdi-refresh"
                        :loading="loading"
                        @click="rate"
                    >
                    Повторить
                    </v-btn>
                </div>
            </v-alert>
        </div>          
    </div>
</template>