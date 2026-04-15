<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { Enrollment, ForeignNational } from '@interfaces/Interfaces';
import { useModals } from '@composables/useModals';
import { usePromptDialog } from '@composables/usePromptDialog';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()

const emit = defineEmits<{
    (e:'edit', value: ForeignNational):void,
    (e:'enroll', value:Enrollment):void
}>()


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
            @click="open('enrollment', {foreignNational, onEnroll:(enrollment:Enrollment) => emit('enroll', enrollment)})"
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
</template>