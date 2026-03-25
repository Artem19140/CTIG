<script setup lang="ts">
import { ref } from 'vue';
import BaseDialog from '../../Components/BaseDialog/BaseDialog.vue';
import { useForm } from '@inertiajs/vue3';
import AddButton from '../../Components/AddButton/AddButton.vue';

const isOpen = defineModel<boolean>({default:false})
const form = useForm({
    comment:''
})

const send = () => {
    alert('Отправка комментария')
}
</script>

<template>
    <BaseDialog 
        width="500"
        v-model="isOpen"
        @before-close="(done) => done()"
        title="Комментарий"
    >
        <v-textarea
            v-model="form.comment"
            rows="4"
            auto-grow
            label="Введите комментарий или нарушение"
        />
        <template #actions>
            <AddButton
                text="Добавить"
                @click="send"
            />
        </template>
    </BaseDialog>
</template>