<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import { reactive, ref, watch } from 'vue';

const model = defineModel<boolean>()
const loading = ref<boolean>(false)
const isAvailable=ref<boolean>(false)

const items = [
    {
        name: 'Сертификаты',
        success : true
    },
    {
        name: 'Справки',
        success : false
    }
]

const form = reactive({
    examDate:'',
    success:null
})


const  download = async () => {
    loading.value=true
    window.location.href = `/reports/frdo?examDate=${form.examDate}&success=${form.success ? 1: 0}`;
}

</script>

<template>
    
    <BaseDialog 
        v-model="model"
        title="Отчеты ФИС ФРДО"
        width="500"
        @before-close="(done) => (done)"
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
        <template #actions>
            <v-btn 
                @click="download"
                color="green"
                variant="flat"
            >Скачать</v-btn>
        </template>
        
        
    </BaseDialog>
</template>