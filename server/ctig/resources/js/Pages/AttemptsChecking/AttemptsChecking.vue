<script lang="ts">
import { useAttemptCheckingModal } from '../../Composables/modalWindows/useAttemptCheckingModal';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';
import AttemptCheckingModal from './AttemptCheckingModal.vue';

export default {
    layout: EmployeeLayout,
}

</script>

<script setup lang="ts">

const {open} = useAttemptCheckingModal()

const props = defineProps<{
    attempts: any
}>()


</script>


<template>
    <!-- <pre>
        {{ attempts }}
    </pre> -->
    <v-card
        v-for="attempt in attempts.data"
        @click="open(attempt.id)"
    >
        <v-title>
            {{ attempt.id }}
        </v-title>
    </v-card>

    <AttemptCheckingModal  />
</template>