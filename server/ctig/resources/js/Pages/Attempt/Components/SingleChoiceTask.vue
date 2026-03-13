<script setup lang="ts">
import axios from 'axios'
import { ref } from 'vue';

const props = defineProps<{
    task:any
}>()

// const studentAnswer = ref<number>(props.task.studentAnswer.id)

// const send = async () => {
//     console.log(studentAnswer.value)
//     await axios.put(`/student-answers/${props.task.id}`, {
//         studentAnswer: studentAnswer.value
//     })
// }
// v-model="studentAnswer"
//                 @update:modelValue="send"
</script>

<template>
    {{ task }}
    <v-card
        :subtitle = "`Номер ${task.order}`"
        width="600"
    >
        <v-card-text>
            <v-img 
                v-if="task.file_path"
                :src="task.file_path"
            />
                

            {{ task.content }}
        </v-card-text>
        <v-card-text>
            <div class="mb-4">Выберите один вариант ответа</div>
            <v-radio-group 
                
            >
                <v-radio 
                    v-for="answer in props.task.answers"
                    :label="answer.content" 
                    :key="answer.id"
                    :value="answer.id">
                </v-radio>
            
            </v-radio-group>
        </v-card-text>
    </v-card>
</template>