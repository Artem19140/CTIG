<script setup lang="ts">
import AttemptCheckingModal from './AttemptCheckingModal.vue';
import EmployeeLayout from '../../Layout/EmployeeLayout.vue';

defineOptions({
  layout: EmployeeLayout,
})

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
            <v-card-title>
                {{ attempt.id }}
            </v-card-title>
            <v-card-text>

            </v-card-text>
        </v-card>
    </v-container>
    <AttemptCheckingModal  />
</template>