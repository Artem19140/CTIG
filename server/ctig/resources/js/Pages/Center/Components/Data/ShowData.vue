<script setup lang="ts">
const props = defineProps<{
    center : any | null
}>()
</script>

<template>
    <v-list>
        <v-list-item>
            <v-list-item-subtitle>Название</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.name }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ОГРН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.ogrn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>  
            <v-list-item-subtitle>ИНН</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.inn }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес центра</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.address }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Адрес выдачи сертификатов</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.certificatesIssueAddress }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle class="text-wrap">Директор</v-list-item-subtitle>
            <v-list-item-title>{{ center?.directorFio }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Название в родительском падеже(для документов)</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.nameGenitive }}</v-list-item-title>
        </v-list-item>
        <v-list-item>
            <v-list-item-subtitle>Временная зона</v-list-item-subtitle>
            <v-list-item-title class="text-wrap">{{ center?.timeZone }}</v-list-item-title>
        </v-list-item>
    </v-list>
</template>