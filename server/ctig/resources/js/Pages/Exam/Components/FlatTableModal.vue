<script setup lang="ts">
import { ref } from 'vue';
import AddButton from '../../../Components/AddButton/AddButton.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';

const isOpen = defineModel<boolean>({default:false})

const form = ref({
    dateFrom:'',
    dateTo:''
})

const donwload = () => {
    window.location.href = `/reports/flat-table?dateFrom=${form.value.dateFrom}&dateTo=${form.value.dateTo}`
}

</script>

<template>
    <BaseDialog 
        width="500"
        title="Плоская таблица"
        v-model="isOpen"
        @before-close="(done) => done()"
    >
        <AppInput
            v-model="form.dateFrom"
            label="Дата с"
            type="date"
        />
        <AppInput
            v-model="form.dateTo"
            label="Дата по"
            type="date"
        />
        <template #actions>
            <AddButton 
                :disabled="!form.dateFrom || !form.dateTo"
                text="Скачать"
                @click="donwload"
            />
        </template>
    </BaseDialog>
</template>