<script setup lang="ts">
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';
const props = defineProps<{
    center : any | null
}>()
</script>

<template>
    <AppInput 
        v-model="center.name"
        label="Название"
    />
    <AppInput 
        v-model="center.ogrn"
        label="ОГРН"
    />

    <AppInput 
        v-model="center.inn"
        label="ИНН"
    />

    <AppTextarea
        v-model="center.address"
        label="Адрес центра"
    />

    <AppTextarea
        v-model="center.certificatesIssueAddress"
        label="Адрес выдачи сертификатов"
    />

    <AppTextarea
        v-model="center.directorFio"
        label="Директор"
    />

    <AppTextarea
        v-model="center.nameGenitive"
        label="Название в родительском падеже(для документов)"
    />
    <AppInput 
        v-model="center.timeZone"
        label="Временная зона"
    />
    
</template>