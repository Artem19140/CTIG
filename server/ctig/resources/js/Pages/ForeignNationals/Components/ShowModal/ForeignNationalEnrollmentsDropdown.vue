<script setup lang="ts">
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useModals } from '../../../../Composables/useModals';
import { Enrollment, ForeignNational } from '../../../../interfaces/interfaces';

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

    <ThreeDotDropdown>
        <AppListDropDownItem 
            title="Заявление"
            @click="() => download('statements')"
        />
        <AppListDropDownItem 
            title="Повторить запись"
            @click="modals.open('enrollment', {foreignNational:foreignNational.id, examTypeId:enrollment.exam.examTypeId})"
        />
    </ThreeDotDropdown>
</template>