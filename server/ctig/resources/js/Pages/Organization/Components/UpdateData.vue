<script setup lang="ts">
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
const props = defineProps<{
    organization : any | null
}>()
</script>

<template>
    <v-list>
        <v-list-item>
            <AppInput 
                v-model="organization.data.name"
                label="Название"
            />
        </v-list-item>

        <v-list-item>
            <AppInput 
                v-model="organization.data.ogrn"
                label="ОГРН"
            />
        </v-list-item>

        <v-list-item>  
            <AppInput 
                v-model="organization.data.inn"
                label="ИНН"
            />
        </v-list-item>

        <v-list-item>
            <v-textarea
                auto-grow
                rows="1"
                v-model="organization.data.address"
                label="Адрес"
            />
        </v-list-item>

        <v-list-item>
            <v-textarea
                auto-grow
                rows="1"
                v-model="organization.data.certificatesIssueAddress"
                label="Адрес выдачи сертификатов"
            />
        </v-list-item>

        <v-list-item>
            <v-textarea
                auto-grow
                rows="1"
                v-model="organization.data.directorFio"
                label="Директор"
            />
        </v-list-item>
        <v-list-item>
            <v-textarea
                auto-grow
                rows="1"
                v-model="organization.data.nameGenitive"
                label="Название в родительском падеже(для документов)"
            />
        </v-list-item>

        <v-list-item>
            <AppInput 
                v-model="organization.data.timeZone"
                label="Временная зона"
            />
        </v-list-item>
    </v-list>
    
</template>