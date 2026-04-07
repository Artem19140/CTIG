<script setup lang="ts">
import AppInput from '../../../Components/AppInput/AppInput.vue';
import AppTextarea from '../../../Components/AppTextarea/AppTextarea.vue';
const props = defineProps<{
    center : any | null
}>()
</script>

<template>
    <AppInput 
                v-model="center.data.name"
                label="Название"
            />
    <AppInput 
        v-model="center.data.ogrn"
        label="ОГРН"
    />

    <AppInput 
        v-model="center.data.inn"
        label="ИНН"
    />


    <AppTextarea
        v-model="center.data.address"
        label="Адрес центра"
    />

    <AppTextarea
        v-model="center.data.certificatesIssueAddress"
        label="Адрес выдачи сертификатов"
    />

    <AppTextarea
        v-model="center.data.directorFio"
        label="Директор"
    />

    <AppTextarea
        v-model="center.data.nameGenitive"
        label="Название в родительском падеже(для документов)"
    />
    <AppInput 
        v-model="center.data.timeZone"
        label="Временная зона"
    />
    
</template>