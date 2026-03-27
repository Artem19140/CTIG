<script setup lang="ts">
import { useModals } from '../../Composables/useModals';
import ForeignNationalShowModal from '../../Pages/ForeignNationals/Components/ForeignNationalShowModal.vue';
import ExamShowModal from '../../Pages/Exam/Components/ExamShowModal/ExamShowModal.vue';
import ExamCreateModal from '../../Pages/Exam/Components/ExamCreateModal.vue';
import FrdoModal from '../../Pages/Exam/Components/FrdoModal.vue';
import ForeignNationalCreateModal from '../../Pages/ForeignNationals/Components/ForeignNationalCreateModal.vue';
import FlatTableModal from '../../Pages/Exam/Components/FlatTableModal.vue';
import EnrollmentModal from '../../Pages/ForeignNationals/Components/EnrollmentModal.vue';
import AttemptCheckingModal from '../../Pages/AttemptsChecking/AttemptCheckingModal.vue';
import PdfDialogViewer from '../PdfDialogViewer/PdfDialogViewer.vue';
import ExamCommentModal from '../../Pages/ExamMonitoring/ExamCommentModal.vue';
import EmployeeCreateModal from '../../Pages/Employees/EmployeeCreateModal.vue';


const {modals, close} = useModals()

const modalComponent = (name: string) => {
    switch(name){
        case 'foreignNationalShow':
            return ForeignNationalShowModal
        case 'examShow':
            return ExamShowModal
        case 'examCreate':
            return ExamCreateModal
        case 'frdo':
            return FrdoModal
        case 'foreignNationalCreate':
            return ForeignNationalCreateModal
        case 'flatTable':
            return FlatTableModal
        case 'enrollment':
            return EnrollmentModal
        case 'attemptChecking':
            return AttemptCheckingModal
        case 'pdf':
            return PdfDialogViewer
        case 'examComment':
            return ExamCommentModal
        case 'employeeCreate':
            return EmployeeCreateModal
    }
}

const closeModal = (id: number) => {
    close(id)
}
</script>

<template>
<component
    v-for="modal in modals"
    :key="modal.id"
    v-bind="modal.data"
    :is="modalComponent(modal.name)"
    v-model = modal.isOpen
    @update:modelValue="closeModal(modal.id)"
/>

</template>