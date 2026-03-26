<script setup lang="ts">
import AddButton from '../../../Components/AddButton/AddButton.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { reactive, ref, watch } from 'vue';
import { useConfirmDialog } from '../../../Composables/useConfirmDialog';
import { useHttp } from '@inertiajs/vue3';

const isOpen = defineModel<boolean>()
const isAvailable=ref<boolean | null>(null)

const items = [
    { name: 'Сертификаты', success : true},
    { name: 'Справки', success : false}
]
const  download = async () => {
    window.location.href = `/reports/frdo?examDate=${http.examDate}&success=${http.success ? 1: 0}`;
}

const http = useHttp({
    examDate:null,
    success:null
})

const canClose = async (fn : () => void) => {
    const {confirmOpen} = useConfirmDialog()
    if(http.isDirty){
        const ok = await confirmOpen('Закрыть окно? Выбор не сохранится')
        if(!ok) return
    }
    http.examDate = null
    http.success = null
    fn()
}


watch(() => http.examDate, async () => {
    if(http.examDate !== null ){
        http.get('/reports/frdo/available',{
            onSuccess:(response :any) =>{
                isAvailable.value = response.available
            }
        })
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
            :error-messages="http.errors.success"
            :rules="[http.success  === !!http.success]"
            v-model="http.success"
        />

        <AppInput
            label="Дата"
            v-model="http.examDate"
            type="date"
            :error-messages="http.errors.examDate"
            :disabled="http.success === null"
        />
        
        <template #actions>
            <div v-if="http.processing">
                <v-progress-circular
                    color="primary"
                    indeterminate ></v-progress-circular>
                    Идет проверка, подождите
            </div>
            <div class="text-red" v-if="isAvailable === false">Отчет недоступен</div>
            <AddButton
                @click="download"
                text="Скачать"
                :disabled="(!http.examDate || http.success === null) || isAvailable === false || http.processing"
            />
        </template>
    </BaseDialog>
</template>