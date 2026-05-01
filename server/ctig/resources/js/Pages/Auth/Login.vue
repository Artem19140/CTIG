<script setup lang="ts">
import { ref } from 'vue';
import EmployeeEntryForm from './Components/EmployeeEntryForm.vue';
import ForeignNationalEntryForm from './Components/ForeignNationalEntryForm.vue';
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import BaseEntryContainer from '@/components/BaseComponents/BaseEntryForm/BaseEntryContainer.vue';
import BaseLayout from '@/layouts/BaseLayout.vue';

const isForeignNationalEntry = ref<boolean>(true)

</script>

<template>
    <BaseLayout>
        <BaseEntryContainer
        >
            <v-card-subtitle class="mb-6 text-h6">
                {{isForeignNationalEntry ? 'Введите код из 6 цифр' : 'Войдите в свой аккаунт'}}
            </v-card-subtitle>
            <ForeignNationalEntryForm v-if="isForeignNationalEntry" />
            <EmployeeEntryForm v-else  />
        </BaseEntryContainer>
        <v-menu location="top start" width="200">
        <template v-slot:activator="{ props }">
            <v-btn
                icon
                variant="text"
                color="grey"
                v-bind="props"
                class="position-fixed bottom-0 left-0 ma-4"
            >
                <v-icon>mdi-chevron-up</v-icon>
            </v-btn>
        </template>
        <v-list>
            <AppListDropDownItem 
                :title="isForeignNationalEntry ? 'Вход сотрудник' : 'Вход ИГ'"
                @click="isForeignNationalEntry = !isForeignNationalEntry" 
            />
        </v-list>
        </v-menu>
    </BaseLayout>
</template>