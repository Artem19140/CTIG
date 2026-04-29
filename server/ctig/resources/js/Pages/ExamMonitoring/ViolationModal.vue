<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import { Enrollment, Violation } from '@/interfaces/Interfaces';
import { useHttp } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})

const violations = ref<Violation[]>()

const http = useHttp()

onMounted(() => {
    if(!props.enrollment.attempt) return 
    http.get(`/attempts/${props.enrollment.attempt.id}/violations`, {
        onSuccess(response :any, httpResponse) {
            violations.value = response.data
        },
    })
})
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        :title="`Нарушения (${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport})`"
        width="800"
        @before-close="(close) => close()"
    >
        <div v-if="enrollment.attempt?.violations && enrollment.attempt.violations.length > 0 ">
            <span
            
            ></span>
        </div>
        <v-empty-state
            v-else
            action-text="Добавить"
            icon="mdi-clipboard-text-off-outline"
            title="Нарушений пока нет"
            @click:action=""
        />
    </BaseDialog>
</template>