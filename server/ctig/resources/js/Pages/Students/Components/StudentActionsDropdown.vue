<script setup lang="ts">
import { ref } from 'vue';
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { Student } from '../../../interfaces/interfaces';
import EnrollmentModal from './EnrollmentModal.vue';

const props = defineProps<{
    student:Student
}>()
const isOpen = ref<boolean>(false)
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Записать на экзамен"
            @click="isOpen=true"
            :student="student"
        />
    </ThreeDotDropdown>
    <EnrollmentModal :student="student" v-model="isOpen" />
</template>