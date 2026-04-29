import { ModalName } from "@/components/Modals/Modals.vue";
import { ref } from "vue";
const modals = ref<Modal[]>([])

export const useModals = () => {
  
  const open = <T>(name: ModalName, data:T = {} as T) => {
    modals.value.push({
      name, 
      data, 
      id:Date.now(),
      isOpen:true
    })
  }

  const close = (id:number) => {
    modals.value = modals.value.filter(modal => modal.id !== id)
  }

  return {open, close, modals}
}

type Modal<T = any> = {
  id: number
  name: ModalName
  data: T
  isOpen: boolean
}
