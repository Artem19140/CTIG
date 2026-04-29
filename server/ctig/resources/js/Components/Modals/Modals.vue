<script setup lang="ts">
import { useModals } from '@composables/useModals';
import ExamShowModal from '@pages/Exam/Components/Modals/ExamShowModal/ExamShowModal.vue';
import ExamCreateModal from '@pages/Exam/Components/Modals/ExamCreateModal.vue';
import FrdoModal from '@pages/Exam/Components/ExamTable/FrdoModal.vue';
import ForeignNationalCreateModal from '@pages/ForeignNationals/Components/Modals/ForeignNationalCreateModal.vue';
import FlatTableModal from '@pages/Exam/Components/ExamTable/FlatTableModal.vue';
import EnrollmentModal from '@pages/ForeignNationals/Components/EnrollmentModal.vue';
import AttemptCheckingModal from '@pages/ExamsChecking/Components/AttemptCheckingModal.vue';
import PdfDialogViewer from '../PdfDialogViewer/PdfDialogViewer.vue';
import ExamCommentModal from '@pages/ExamMonitoring/ExamCommentModal.vue';
import EmployeeCreateModal from '@pages/Center/Components/Employees/EmployeeCreateModal.vue';
import ForeignNationalEditModal from '@pages/ForeignNationals/Components/Modals/ForeignNationalEditModal.vue';
import ExamEditModal from '@pages/Exam/Components/Modals/ExamEditModal.vue';
import ForeignNationalShowModal from '@pages/ForeignNationals/Components/Modals/ShowModal/ForeignNationalShowModal.vue';
import ForeignNationalExportModal from '@pages/ForeignNationals/Components/ForeignNationalTable/ForeignNationalExportModal.vue';
import StatisticsModal from '@pages/ForeignNationals/Components/ForeignNationalTable/StatisticsModal.vue';
import SpeakingTasksModal from '@/pages/ExamMonitoring/SpeakingTasksModal.vue';
import AddressCreateModal from '@/pages/Center/Components/Addresses/AddressCreateModal.vue';
import MinistryEducationReportModal from '@/pages/ForeignNationals/Components/ForeignNationalTable/MinistryEducationReportModal.vue';
import ViolationModal from '@/pages/ExamMonitoring/ViolationModal.vue';

const {modals, close} = useModals()

export type ModalName = keyof typeof modalMap

const modalComponent = (name: ModalName) => modalMap[name] ?? null

const modalMap = {
    foreignNationalShow: ForeignNationalShowModal,
    examShow: ExamShowModal,
    examCreate: ExamCreateModal,
    frdo: FrdoModal,
    foreignNationalCreate: ForeignNationalCreateModal,
    flatTable: FlatTableModal,
    enrollment: EnrollmentModal,
    attemptChecking: AttemptCheckingModal,
    pdf: PdfDialogViewer,
    examComment: ExamCommentModal,
    employeeCreate: EmployeeCreateModal,
    foreignNationalEdit: ForeignNationalEditModal,
    examEdit: ExamEditModal,
    foreignNationalExport: ForeignNationalExportModal,
    statistics: StatisticsModal,
    speaking: SpeakingTasksModal,
    addressCreate: AddressCreateModal,
    ministryEducationReport: MinistryEducationReportModal,
    violation: ViolationModal,
} as const

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