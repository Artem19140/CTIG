<script setup lang="ts">
const props = defineProps<{
    center : any | null
}>()
</script>

<template>
    <v-list>
        <v-list-item>
            <v-list-item-subtitle>Название</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.name }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ОГРН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.ogrn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ИНН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.inn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес центра</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.address }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес выдачи сертификатов</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.certificatesIssueAddress }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle class="text-wrap">Директор</v-list-item-subtitle>
            <v-list-item-title>{{ center?.data?.directorFio }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Название в родительском падеже(для документов)</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.nameGenitive }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Временная зона</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.data?.timeZone }}</v-list-item-title>
        </v-list-item>
    </v-list>
</template>