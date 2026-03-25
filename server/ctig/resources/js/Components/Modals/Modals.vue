<script setup lang="ts">
import { useModals } from '../../Composables/useModals';
import StudentShowModal from '../../Pages/Students/Components/StudentShowModal.vue';
import ExamShowModal from '../../Pages/Exam/Components/ExamShowModal/ExamShowModal.vue';
import ExamCreateModal from '../../Pages/Exam/Components/ExamCreateModal.vue';
import FrdoModal from '../../Pages/Exam/Components/FrdoModal.vue';
import StudentCreateModal from '../../Pages/Students/Components/StudentCreateModal.vue';
import FlatTableModal from '../../Pages/Exam/Components/FlatTableModal.vue';
import EnrollmentModal from '../../Pages/Students/Components/EnrollmentModal.vue';
import AttemptCheckingModal from '../../Pages/AttemptsChecking/AttemptCheckingModal.vue';
import PdfDialogViewer from '../PdfDialogViewer/PdfDialogViewer.vue';
import ExamCommentModal from '../../Pages/ExamMonitoring/ExamCommentModal.vue';


const {modals, close} = useModals()

const modalComponent = (name: string) => {
    switch(name){
        case 'studentShow':
            return StudentShowModal
        case 'examShow':
            return ExamShowModal
        case 'examCreate':
            return ExamCreateModal
        case 'frdo':
            return FrdoModal
        case 'studentCreate':
            return StudentCreateModal
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