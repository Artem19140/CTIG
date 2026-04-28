<script setup lang="ts">
import AppListDropDownItem from '@components/UI/AppListDropDownItem/AppListDropDownItem.vue';
import { router, useHttp } from '@inertiajs/vue3';
import BaseThreeDotDropdown from '@/components/BaseComponents/BaseThreeDotDropdown/BaseThreeDotDropdown.vue';
import { useLoadingSnackbar } from '@/composables/useLoadingSnackBar';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';

const props= defineProps<{
  employee:any
}>()

const deleteHttp = useHttp()
const deleteEmployee = async () => {
  const useConfirmation = useConfirmationOptionsDialog()
  const ok = await useConfirmation.open(
    `Удалить ${props.employee?.surname} ${props.employee?.name}?
    У сотрудника больше не будет доступа к системе`
  )
  if(!ok) return
  const {open, close} = useLoadingSnackbar()
  open('Идет удаление...')
  await deleteHttp.delete(`/employees/${props.employee?.id}`, {
    onSuccess:() => {
      router.reload()
    },
    onFinish:()=> {
      close()
    }
  })
  
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