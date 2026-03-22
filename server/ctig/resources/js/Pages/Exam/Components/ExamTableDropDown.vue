<script setup lang="ts">
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useAuth } from '../../../Composables/useAuth';
import { Roles } from '../../../Constants/Roles';
import { useModals } from '../../../Composables/useModals';

const {can} = useAuth()
const {open} = useModals()

</script>

<template>
    <ThreeDotDropdown v-if="can([Roles.EXAMINER, Roles.DIRECTOR])">
            <AppListDropDownItem title="Скачать отчет ФИС ФРДО" @click="open('frdo')" />
            <AppListDropDownItem title="Скачать плоскую таблицу" @click="open('flatTable')"/>
    </ThreeDotDropdown>
</template>