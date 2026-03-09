import { reactive } from 'vue'

export const modalState = reactive({
  studentId: null as number | null,
  examId: null as number | null,
  fileUrl: null as string | null,
  fileType: null as string | null
})

