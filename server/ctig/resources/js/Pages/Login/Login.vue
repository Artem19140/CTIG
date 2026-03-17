<script setup lang="ts">
import { ref } from 'vue';
import EmployeeEntry from './Components/EmployeeEntry.vue';
import StudentEntry from './Components/StudentEntry.vue';
import AppListDropDownItem from '../../Components/UI/AppListDropDownItem/AppListDropDownItem.vue';

const isStudentEntry = ref<boolean>(true)
</script>

<template>
    <v-container width="500">
        <v-card>
            <StudentEntry v-if="isStudentEntry" />
            <EmployeeEntry v-else  />
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
            :title="isStudentEntry ? 'Вход сотрудник' : 'Вход студент'"
            @click="isStudentEntry = !isStudentEntry" 
        />
      </v-list>
    </v-menu>
</template>