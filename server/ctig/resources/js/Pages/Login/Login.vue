<script setup lang="ts">
import { ref } from 'vue';
import EmployeeEntry from './Components/EmployeeEntry.vue';
import ForeignNationalEntry from './Components/ForeignNationalEntry.vue';
import AppListDropDownItem from '../../Components/AppListDropDownItem/AppListDropDownItem.vue';

const isForeignNationalEntry = ref<boolean>(true)
</script>

<template>
    <v-container width="450">
        <v-card>
            <v-card-text>
                <ForeignNationalEntry v-if="isForeignNationalEntry" />
                <EmployeeEntry v-else  />
            </v-card-text>
        </v-card>
    </v-container>
    
    
    
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
</template>