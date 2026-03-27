<script setup lang="ts">
import { ref } from 'vue';
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { ForeignNational } from '../../../interfaces/interfaces';
import EnrollmentModal from './EnrollmentModal.vue';
import { useModals } from '../../../Composables/useModals';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()
const isOpen = ref<boolean>(false)
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Записать на экзамен"
            @click="open('enrollment', {foreignNational})"
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
    <EnrollmentModal :foreignNational="foreignNational" v-model="isOpen" />
</template>