<script setup lang="ts">
import AddButton from '../../../Components/AddButton/AddButton.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { reactive, ref, watch } from 'vue';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import { useApi } from '../../../Composables/Api/useApi';
import axios from 'axios';

const isOpen = defineModel<boolean>()
const isAvailable=ref<boolean | null>(null)

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
const form = reactive({
    examDate:'',
    success:null
})

const  download = async () => {
    window.location.href = `/reports/frdo?examDate=${form.examDate}&success=${form.success ? 1: 0}`;
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

const checkAvailableApi = useApi()

watch(() => form.examDate, async () => {
    if(form.examDate && form.success !== null){
        await checkAvailableApi.request(() => axios.get(`/reports/frdo/available?examDate=${form.examDate}`))
        if(!checkAvailableApi.error.value){
            isAvailable.value = checkAvailableApi.data?.value.available
        }
    }
})

</script>

<template>
    
    <BaseDialog 
        v-model="isOpen"
        title="Отчеты ФИС ФРДО"
        width="500"
        @before-close="(done) => canClose(done)"
    >
        <v-select
            label="Тип"
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
        
        <template #actions>
            <div v-if="checkAvailableApi.loading.value">
                <v-progress-circular
                    color="primary"
                    indeterminate ></v-progress-circular>
                    Идет проверка, подождите
            </div>
            <div class="text-red" v-if="isAvailable === false">Отчет недоступен</div>
            <AddButton
                @click="download"
                text="Скачать"
                :disabled="(!form.examDate || form.success === null) || isAvailable === false || checkAvailableApi.loading.value"
            />
        </template>
    </BaseDialog>
</template>