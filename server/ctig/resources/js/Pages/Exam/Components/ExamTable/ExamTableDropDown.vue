<script setup lang="ts">
import BaseThreeDotDropdown from '@components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useAuth } from '@composables/useAuth';
import { Roles } from '@constants/Roles';
import { useModals } from '@composables/useModals';
import BaseListItem from '@/components/BaseComponents/BaseList/BaseListItem.vue';

const auth = useAuth()
const modals = useModals()

</script>

<template>
    <BaseThreeDotDropdown v-if="auth.can([Roles.DIRECTOR, Roles.OPERATOR])">
        <BaseListItem 
            title="ФИС ФРДО" 
            @click="modals.open('frdo')" 
        />
        <BaseListItem 
            title="Плоская таблица" 
            v-if="auth.can([Roles.DIRECTOR])"
            @click="modals.open('flatTable')"
        />
    </BaseThreeDotDropdown>
</template>