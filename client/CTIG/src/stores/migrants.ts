import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { IMigrant } from '@/interfaces/interfaces'

export const migrantsStore = defineStore('migrants', () => {
  const migrants = ref<IMigrant[]>([])
  
  return { migrants }
})
