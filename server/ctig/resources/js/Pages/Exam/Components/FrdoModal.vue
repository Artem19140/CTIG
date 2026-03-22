<script setup lang="ts">
import AddButton from '../../../Components/AddButton/AddButton.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { reactive, ref, watch } from 'vue';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';

const isOpen = defineModel<boolean>()
const loading = ref<boolean>(false)
const isAvailable=ref<boolean>(false)

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
const form = reactive({
    examDate:'',
    success:null
})

const  download = async () => {
    loading.value=true
    window.location.href = `/reports/frdo?examDate=${form.examDate}&success=${form.success ? 1: 0}`;
    loading.value=false
}

const canClose = async (fn : () => void) => {
    const {confirmOpen} = useConfirmDialog()
    if(form.examDate || form.success){
        const ok = await confirmOpen('Закрыть окно? Выбор не сохранится')
        if(!ok) return
    }
    form.examDate = ''
    form.success = null
    fn()
}

</script>

<template>
    
    <BaseDialog 
        v-model="isOpen"
        title="Отчеты ФИС ФРДО"
        width="500"
        @before-close="(done) => canClose(done)"
    >
        <v-select
            label=""
            :items=items
            item-value="success"
            item-title="name"
            clearable
            :rules="[form.success  === !!form.success]"
            v-model="form.success"
        />

        <AppInput
            label="Дата"
            v-model="form.examDate"
            type="date"
            :disabled="form.success === null"
        />
        <!-- <span v-if="!isAvailable && form.examDate && form.success !== null">
            Отчет не доступен
        </span> -->
        <!-- :loading="loading" -->
        <template #actions="{close}">
            <AddButton
                @click="download"
                text="Скачать"
            />
            <v-btn
                @click="close"
            >
                Отмена
            </v-btn>
        </template>
    </BaseDialog>
</template>