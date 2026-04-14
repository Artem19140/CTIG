<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useModals } from '@composables/useModals';
import { Enrollment, ForeignNational } from '@interfaces/interfaces';

const modals = useModals()
const props= defineProps<{
    enrollment:Enrollment,
    foreignNational:ForeignNational
}>()

const download = (document : string) => {
    window.open(`enrollments/${props.enrollment.id}/${document}`)
}
</script>

<template>

    <BaseThreeDotDropdown>
        <AppListDropDownItem 
            title="Заявление"
            @click="() => download('statements')"
        />
        <AppListDropDownItem 
            title="Повторить запись"
            @click="modals.open('enrollment', {foreignNational:foreignNational, examTypeId:enrollment.exam.examTypeId})"
        />
    </BaseThreeDotDropdown>
</template>