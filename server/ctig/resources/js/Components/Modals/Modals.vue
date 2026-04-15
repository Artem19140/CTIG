<script setup lang="ts">
import { useModals } from '@composables/useModals';
import ExamShowModal from '@pages/Exam/Components/Modals/ExamShowModal/ExamShowModal.vue';
import ExamCreateModal from '@pages/Exam/Components/Modals/ExamCreateModal.vue';
import FrdoModal from '@pages/Exam/Components/ExamTable/FrdoModal.vue';
import ForeignNationalCreateModal from '@pages/ForeignNationals/Components/Modals/ForeignNationalCreateModal.vue';
import FlatTableModal from '@pages/Exam/Components/ExamTable/FlatTableModal.vue';
import EnrollmentModal from '@pages/ForeignNationals/Components/EnrollmentModal.vue';
import AttemptCheckingModal from '@pages/AttemptsChecking/AttemptCheckingModal.vue';
import PdfDialogViewer from '../PdfDialogViewer/PdfDialogViewer.vue';
import ExamCommentModal from '@pages/ExamMonitoring/ExamCommentModal.vue';
import EmployeeCreateModal from '@pages/Employees/EmployeeCreateModal.vue';
import RescheduleModal from '@components/Enrollment/RescheduleModal.vue';
import ForeignNationalEditModal from '@pages/ForeignNationals/Components/Modals/ForeignNationalEditModal.vue';
import ExamEditModal from '@pages/Exam/Components/Modals/ExamEditModal.vue';
import ForeignNationalShowModal from '@pages/ForeignNationals/Components/Modals/ShowModal/ForeignNationalShowModal.vue';
import ForeignNationalExportModal from '@pages/ForeignNationals/Components/ForeignNationalTable/ForeignNationalExportModal.vue';
import StatisticsModal from '@pages/ForeignNationals/Components/ForeignNationalTable/StatisticsModal.vue';


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
        case 'reschedule':
            return RescheduleModal
        case 'foreignNationalEdit':
            return ForeignNationalEditModal
        case 'examEdit':
            return ExamEditModal
        case 'foreignNationalExport':
            return ForeignNationalExportModal
        case 'statistics':
            return StatisticsModal
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