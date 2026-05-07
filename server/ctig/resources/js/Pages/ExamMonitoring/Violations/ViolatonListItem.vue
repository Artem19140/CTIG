<script setup lang="ts">
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';
import ViolationForm from './ViolationForm.vue';
import { Violation } from '@/interfaces/Violation';
import { Attempt } from '@/interfaces/Attempt';

const props = defineProps<{
    index:number,
    violation:Violation,
    attempt:Attempt
}>()

const emit = defineEmits<{
    (e:'delete', value:number):void,
    (e:'edit', value:Violation):void
}>()

const deleteHttp = useHttp()
const deleteViolation = async () => {
    const {confirmOpen} = useConfirmDialog()
    const ok = await confirmOpen('Удалить нарушение?')
    if(!ok) return
    deleteHttp.delete(`/attempts/${props.attempt.id}/violations/${props.violation.id}`,{
        onSuccess() {
            emit('delete', props.violation.id)
        },
    })
}

const editting = ref<boolean>(false)

const editHttp = useHttp<{comment:string}, {data:Violation}>({
    comment:props.violation.comment
})

const editViolation = async () => {
    editHttp.patch(`/attempts/${props.attempt.id}/violations/${props.violation.id}`,{
        onSuccess(response) {
            emit('edit', response.data)
            editting.value = false
        },
    })
}
</script>

<template>
    <v-list-item
        lines="two"
        item-props
    >
        <template #prepend>
            <v-avatar size="24" color="grey-lighten-2">
                {{ index + 1 }}
            </v-avatar>
        </template>

        <v-list-item-title class="text-wrap" v-if="!editting">
            {{ violation.comment }}
        </v-list-item-title>
        <ViolationForm
            :loading="editHttp.processing"
            v-model="editHttp.comment"
            v-else
            @cancel="editting=false"
            :error-messages="editHttp.errors.comment"
            @save="editViolation"
        />
        
        <template v-slot:append> 
            <v-btn
                size="x-small"
                color="grey-lighten-1"
                icon="mdi-pencil-outline"
                variant="text"
                @click="editting = true"
            ></v-btn>
        
            <v-btn
                v-if="!deleteHttp.processing"
                size="x-small"
                icon="mdi-delete"
                variant="text"
                @click="deleteViolation"
            ></v-btn>
            <AppProgressCircular 
                size="20"
                v-else
            />
        </template>
        
    </v-list-item>
</template>