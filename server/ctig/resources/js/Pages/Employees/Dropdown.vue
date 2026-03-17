<script setup lang="ts">
import AppListDropDownItem from '../../Components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { router } from '@inertiajs/vue3';
import { useConfirmDialog } from '../../Composables/useConfirmDialog';

const props= defineProps<{
  employee:any
}>()

const deleteEmployee = async () => {
  const {confirmOpen} = useConfirmDialog()
  const ok = await confirmOpen(`Удалить ${props.employee?.surname} ${props.employee?.name}?
                                У сотрудника больше не будет доступа к системе`)
  if(!ok) return
  router.delete(`/employees/${props.employee?.id}`)
}
</script>

<template>
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn
            variant="text"
            v-bind="props"
            size="small"
        >
        <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list>
        <AppListDropDownItem 
          title="Редактировать" 
        />
        <AppListDropDownItem 
          title="Удалить" 
          color="text-red" 
          @click="deleteEmployee"
        />
      </v-list>
    </v-menu>
</template>