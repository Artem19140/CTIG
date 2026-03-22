<script setup lang="ts">
import { ref } from 'vue';
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { Student } from '../../../interfaces/interfaces';
import EnrollmentModal from './EnrollmentModal.vue';
import { useModals } from '../../../Composables/useModals';

const {open} = useModals()

const props = defineProps<{
    student:Student | null
}>()
const isOpen = ref<boolean>(false)
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Записать на экзамен"
            @click="open('enrollment', {student})"
        />
        <AppListDropDownItem 
            title="Редактировать"
            @click=""
        />
        <AppListDropDownItem 
            title="Удалить"
            @click=""
            color="text-red"
        />
    </ThreeDotDropdown>
    <EnrollmentModal :student="student" v-model="isOpen" />
</template>