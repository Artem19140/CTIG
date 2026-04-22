<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { Enrollment, ForeignNational } from '@interfaces/Interfaces';
import { useModals } from '@composables/useModals';
import { router, useHttp } from '@inertiajs/vue3';
import { useLoadingSnackbar } from '@/composables/useLoadingSnackBar';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';

const {open} = useModals()

const props = defineProps<{
    foreignNational:ForeignNational | null
}>()

const emit = defineEmits<{
    (e:'edit', value: ForeignNational):void,
    (e:'enroll', value:Enrollment):void,
    (e:'delete', value:ForeignNational):void
}>()

const destroy = async () => {
    if(!props.foreignNational) return
    const {open} = useConfirmationOptionsDialog()
    const ok = await open('Удалить ИГ из системы? Также будут удалены все связанные с ним данные')
    if(!ok) return
    const loading = useLoadingSnackbar()
    loading.open('Идет удаление...')
    router.delete(`/foreign-nationals/${props.foreignNational.id}`,{
        onSuccess:(response:any)=>{
            if(!props.foreignNational)return
            emit('delete', props.foreignNational)
        },
        onFinish:() => {
            loading.close()
        }
    })
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