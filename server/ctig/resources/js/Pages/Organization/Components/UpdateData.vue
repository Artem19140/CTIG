<script setup lang="ts">
import AppInput from '../../../Components/AppInput/AppInput.vue';
import AppTextarea from '../../../Components/AppTextarea/AppTextarea.vue';
const props = defineProps<{
    organization : any | null
}>()
</script>

<template>
    <AppInput 
                v-model="organization.data.name"
                label="Название"
            />
    <AppInput 
        v-model="organization.data.ogrn"
        label="ОГРН"
    />

    <AppInput 
        v-model="organization.data.inn"
        label="ИНН"
    />


    <AppTextarea
        v-model="organization.data.address"
        label="Адрес организации"
    />

    <AppTextarea
        v-model="organization.data.certificatesIssueAddress"
        label="Адрес выдачи сертификатов"
    />

    <AppTextarea
        v-model="organization.data.directorFio"
        label="Директор"
    />

    <AppTextarea
        v-model="organization.data.nameGenitive"
        label="Название в родительском падеже(для документов)"
    />
    <AppInput 
        v-model="organization.data.timeZone"
        label="Временная зона"
    />
    
</template>