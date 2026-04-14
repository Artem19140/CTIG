<script setup lang="ts">
import { ref } from 'vue';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { ForeignNational } from '@interfaces/interfaces';
import EnrollmentModal from '../../EnrollmentModal.vue';
import { useModals } from '@composables/useModals';
import { usePromptDialog } from '@composables/usePromptDialog';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()

const emit = defineEmits<{
    (e:'edit', value: ForeignNational):void
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
            @click="open('foreignNationalEdit', {foreignNational, onEdit:(foreignNational : ForeignNational)=>emit('edit', foreignNational)})"
        />
        <AppListDropDownItem 
            title="Удалить"
            @click="destroy"
            color="text-red"
        />
    </ThreeDotDropdown>
    <EnrollmentModal :foreignNational="foreignNational" v-model="isOpen" />
</template>