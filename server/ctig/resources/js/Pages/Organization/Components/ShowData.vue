<script setup lang="ts">
const props = defineProps<{
    organization : any | null
}>()
</script>

<template>
    <v-list>
        <v-list-item>
            <v-list-item-subtitle>Название</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.name }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ОГРН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.ogrn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ИНН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.inn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.address }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес выдачи сертификатов</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.certificatesIssueAddress }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle class="text-wrap">Директор</v-list-item-subtitle>
            <v-list-item-title>{{ organization?.data?.directorFio }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Название в родительском падеже(для документов)</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.nameGenitive }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Временная зона</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ organization?.data?.timeZone }}</v-list-item-title>
        </v-list-item>
    </v-list>
</template>