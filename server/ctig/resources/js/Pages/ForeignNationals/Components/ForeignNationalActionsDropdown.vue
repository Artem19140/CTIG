<script setup lang="ts">
import { ref } from 'vue';
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { ForeignNational } from '../../../interfaces/interfaces';
import EnrollmentModal from './EnrollmentModal.vue';
import { useModals } from '../../../Composables/useModals';
import { usePromptDialog } from '../../../Composables/usePromptDialog';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()
const isOpen = ref<boolean>(false)

const destroy = async () => {
    const {open, canClose, errorMessages} = usePromptDialog()
    const confirmationWord = await open("Введите слово 'УДАЛИТЬ' для подтверждения действия")
    if(confirmationWord !== 'УДАЛИТЬ'){
        canClose.value = false
        errorMessages.value = "Введите слово 'УДАЛИТЬ'"
        return
    }
}
</script>

<template>
    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Записать на экзамен"
            @click="open('enrollment', {foreignNational})"
        />
        <AppListDropDownItem 
            title="Редактировать"
            @click="open('foreignNationalEdit', {foreignNational})"
        />
        <AppListDropDownItem 
            title="Удалить"
            @click="destroy"
            color="text-red"
        />
    </ThreeDotDropdown>
    <EnrollmentModal :foreignNational="foreignNational" v-model="isOpen" />
</template>