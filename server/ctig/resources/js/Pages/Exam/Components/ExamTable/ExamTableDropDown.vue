<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { useModals } from '@composables/useModals';

const auth = useAuth()
const modals = useModals()

</script>

<template>
    <BaseThreeDotDropdown v-if="auth.can([Roles.EXAMINER, Roles.DIRECTOR])">
            <AppListDropDownItem title="Скачать отчет ФИС ФРДО" @click="modals.open('frdo')" />
            <AppListDropDownItem title="Скачать плоскую таблицу" @click="modals.open('flatTable')"/>
    </BaseThreeDotDropdown>
</template>