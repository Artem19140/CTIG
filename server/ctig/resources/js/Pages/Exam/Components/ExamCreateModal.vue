<script setup lang="ts">
import { ref } from 'vue';
import ExamAddForm from './ExamAddForm.vue';
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';

defineProps<{
    isOpen:boolean
}>()

const examTypes = ref([{
    name:"Внж",
    id:1
}])
</script>

<template>
    <v-dialog persistent max-width="500">
        <!-- <template v-slot:activator="{ props: activatorProps }">
            <v-btn
            v-bind="activatorProps"
            color="green"
            text="Добавить"
            variant="flat"
            
            ></v-btn>
        </template> -->
        
        <template v-slot:default="{ isActive }">
            <v-card title="Добавление экзамена"  class="pl-4 pr-4">

            <ExamAddForm />

            <v-card-actions class="flex justify-center">
                <v-btn
                    text="Добавить"
                    variant="flat"
                    color="green"
                    type="submit"
                ></v-btn>
                
                <v-btn
                    text="Отменить"
                    @click="isActive.value = false"
                ></v-btn>

                
            </v-card-actions>
            </v-card>
        </template>
        
    </v-dialog>
</template>