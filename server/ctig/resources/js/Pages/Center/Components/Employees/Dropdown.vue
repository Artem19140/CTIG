<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { router } from '@inertiajs/vue3';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import BaseThreeDotDropdown from '@/components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';

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
  <BaseThreeDotDropdown>
    <AppListDropDownItem 
      title="Редактировать" 
    />
    <AppListDropDownItem 
      title="Удалить" 
      color="text-red" 
      @click="deleteEmployee"
    />
  </BaseThreeDotDropdown>
</template>