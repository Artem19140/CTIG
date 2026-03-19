<script setup lang="ts">
import { ref } from 'vue';
import FrdoModal from './FrdoModal.vue';
import AppListDropDownItem from '../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import ThreeDotDropdown from '../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import { useAuth } from '../../../Composables/useAuth';
import { Roles } from '../../../Constants/Roles';

const {can} = useAuth()

const isFrdoModalOpen = ref<boolean>(false)

</script>

<template>
    <ThreeDotDropdown v-if="can([Roles.EXAMINER, Roles.DIRECTOR])">
            <AppListDropDownItem title="Скачать отчет ФИС ФРДО" @click="isFrdoModalOpen = true" />
            <AppListDropDownItem title="Скачать плоскую таблицу"/>
    </ThreeDotDropdown>

    <FrdoModal v-model="isFrdoModalOpen"/>
</template>