<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppAddButton from '@/components/UI/AppAddButton/AppAddButton.vue';
import { useHttp } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import ViolatonListItem from './ViolatonListItem.vue';
import ViolationForm from './ViolationForm.vue';
import { Enrollment } from '@/interfaces/Enrollment';
import { Violation } from '@/interfaces/Violation';
import BaseEmptyState from '@/components/BaseComponents/BaseEmptyState/BaseEmptyState.vue';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';

const props = defineProps<{
    enrollment:Enrollment
}>()

const isOpen = defineModel<boolean>({default:false})
const adding = ref<boolean>(false)
const violations = ref<Violation[]>()

const violationsExists = computed(() => violations.value?.length)

const attemptId = computed(() => props.enrollment.attempt?.id)

const http = useHttp()

onMounted(() => {
    if(!attemptId.value) return 
    http.get(`/attempts/${attemptId.value}/violations`, {
        onSuccess(response :any, httpResponse) {
            violations.value = response.data
        },
    })
})

const addHttp = useHttp({
    comment:null
})

const add = () => {
    if(!attemptId.value) return 
    addHttp.post(`/attempts/${attemptId.value}/violations`,{
        onSuccess(response:any, httpResponse) {
            violations.value?.push(response.data)
            adding.value = false
            addHttp.resetAndClearErrors()
        },
    })
}

const deleteViolation = (id: number) => {
    violations.value = violations.value?.filter(v => v.id !== id)
}

const editViolation = (updatedViolation: Violation) => {
    if (!violations.value) return

    const index = violations.value.findIndex(v => v.id === updatedViolation.id)
    if (index === -1) return

    violations.value[index] = updatedViolation
}
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        :title="`Нарушения (${enrollment.foreignNational.fullName}, ${enrollment.foreignNational.fullPassport})`"
        width="600"
        :loading="http.processing"
        @before-close="(close) => {
            adding = false
            close()
        }"
    >
        <template #title>
            <span class="mr-2">{{enrollment.foreignNational.fullName}}, ({{enrollment.foreignNational.fullPassport}})</span>
            <AppTooltip 
                text="Фиксирование нарушений доступно во время и в течении всего дня после экзамена"
            />
        </template>
        <div class="mb-4">
            <div v-if="violationsExists && !adding" class="text-right">
                <AppAddButton
                    @click="adding = true"
                    text="Добавить"
                />
            </div>

            <div v-if="adding" >
                <ViolationForm
                    :loading="addHttp.processing"
                    v-model="addHttp.comment"
                   
                    @cancel="adding = false"
                    :error-messages="addHttp.errors.comment"
                    @save="add"
                />
            </div>
        </div>

        <v-card 
            v-if="violationsExists" 
            variant="outlined" 
            class="mb-4"
        >
            <v-list max-height="300">
                <ViolatonListItem
                    @edit="editViolation"
                    @delete="deleteViolation"
                    v-if="enrollment.attempt"
                    v-for="(violation, index) in violations"
                    :key="violation.id"
                    :violation="violation"
                    :index="index"
                    :attempt="enrollment.attempt"
                />
            </v-list>
        </v-card>

        <BaseEmptyState
            v-if="!violationsExists && !adding"
            action-text="Добавить"
            icon="mdi-clipboard-text-off-outline"
            title="Нарушений пока нет"
            @click:action="adding = true"
        />
        
        
    </BaseDialog>
</template>