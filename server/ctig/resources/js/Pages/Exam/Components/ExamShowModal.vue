<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps<{
    exam:Object
}>()

const getExam = async () => {
    const response = await axios.get('exams/11')
    const data = response.data
    console.log(data)
}
</script>

<template>
        <!-- <v-dialog persistent max-width="500"> -->
            <v-card title="Экзамен"  class="pl-4 pr-4" @click="getExam">   
                <h1>{{ exam }}</h1>
            </v-card>
        <!-- </v-dialog> -->
</template>