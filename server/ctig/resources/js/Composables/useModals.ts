import { ref } from "vue";
const modals = ref<any[]>([])

export const useModals = () => {
  
  const open = (name: string, data:Object = {}) => {
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

