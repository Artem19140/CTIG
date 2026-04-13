import { ref } from "vue";
const modals = ref<Modal[]>([])

export const useModals = () => {
  
  const open = <T>(name: string, data:T = {} as T) => {
    modals.value.push({
      name, 
      data, 
      id:Date.now(),
      isOpen:true
    })
  }

  const close = (id:number) => {
    // const modal = modals.value.find(m => m.id === id)
    // if (modal) modal.isOpen = false
    modals.value = modals.value.filter(modal => modal.id !== id)
  }

  return {open, close, modals}
}

type Modal<T = any> = {
  id: number
  name: string
  data: T
  isOpen: boolean
}
