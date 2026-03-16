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
    <pre>
        {{ attempts }}
    </pre>
    <v-container class="d-flex flex-column gap-5 align-center justify-center">
        <v-card
            v-for="attempt in attempts.data"
            @click="open(attempt.id)"
            width="500"
        >
            <v-title>
                {{ attempt.id }}
            </v-title>
            <v-card-text>

            </v-card-text>
        </v-card>
    </v-container>
    <AttemptCheckingModal  />
</template>