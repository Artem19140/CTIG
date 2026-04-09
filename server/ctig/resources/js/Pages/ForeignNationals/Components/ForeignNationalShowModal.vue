<script setup lang="ts">
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import ForeignNationalExamsList from './ForeignNationalExamsList.vue';
import ForeignNationalActionsDropdown from './ForeignNationalActionsDropdown.vue';
import { onMounted, ref } from 'vue';
import type { ForeignNational } from '../../../interfaces/interfaces';
import { useHttp } from '@inertiajs/vue3'
import { DateFormatter } from '../../../Helpers/DateFormatter';

const props = defineProps<{
    foreignNationalId?:number
}>()

const http = useHttp()

const isOpen = defineModel<boolean>({default:false})
const foreignNational = ref<ForeignNational | null>(null)


const getForeignNational = async () => {
    http.get(`/foreign-nationals/${props.foreignNationalId}?profile=true`,{
        onSuccess:(response : any)=>{
            foreignNational.value = response.data
        }
    })
}

onMounted(async() => {
    if(!props.foreignNationalId) return
    getForeignNational()
})

const showDocument = (url :string) => {
    if(!url) return
    window.open(`/files?path=${url}`)
}
const edit = (value:ForeignNational) => {
    foreignNational.value = value
}
</script>

<template>
    <BaseDialog 
        width="700"
        height="900"
        :title="`Карточка ИГ (ID ${foreignNational?.id ?? ''})`"
        :loading="http.processing"
        v-model="isOpen"
        :error="http.hasErrors"
        :onRetry="getForeignNational"
        @before-close="(done) => done()"
        skeleton="avatar, heading, paragraph, paragraph, divider, list-item-two-line, list-item-two-line, list-item-two-line, list-item-two-line, divider, image, divider, table"
    >
        <template #titleActions>
            <ForeignNationalActionsDropdown 
                :foreignNational="foreignNational"
                @edit="edit"
            />
        </template>

        <v-card-text>
            <div class="text-headline-small">{{foreignNational?.fullName }}</div>
            <div class="text-subtitle-1">{{foreignNational?.fullNameLatin}}</div>
            <div class="text-subtitle-2">{{new DateFormatter(foreignNational?.dateBirth ?? '').format('d.m.Y')}}</div> 
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <v-list-item-title>{{`${foreignNational?.fullPassport ?? ''}, выдан ${new DateFormatter(foreignNational?.issuedDate ?? '').format('d.m.Y')} (${foreignNational?.issuedBy ?? ''})`}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Миграционная карта</v-list-item-subtitle>
                    <v-list-item-title>{{foreignNational?.migrationCardRequisite ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>  
                    <v-list-item-subtitle>Адрес регистрации</v-list-item-subtitle>
                    <v-list-item-title>{{foreignNational?.addressReg ?? ''}}</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <v-list-item-subtitle>Номер телефона</v-list-item-subtitle>
                    <v-list-item-title>{{foreignNational?.phone ?? ''}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-row comfortable>
                <v-col cols="6" class="">
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <div v-if="!foreignNational?.passportScan">-</div>
                    <v-img
                        v-else
                        width="50"
                        src="https://cdn-icons-png.flaticon.com/512/9034/9034536.png"
                        class="mt-2 cursor-pointer hover:opacity-80 transition-opacity"
                        @click="showDocument(foreignNational?.passportScan)"
                    />
                </v-col>
                <v-col cols="6" class="">
                    <v-list-item-subtitle>Перевод паспорта</v-list-item-subtitle>
                    <div v-if="!foreignNational?.passportTranslateScan">-</div>
                    <v-img
                        v-else
                        width="50"
                        src="https://cdn-icons-png.flaticon.com/512/9034/9034536.png"
                        class="mt-2 cursor-pointer hover:opacity-80 transition-opacity"
                        @click="showDocument(foreignNational?.passportTranslateScan)"
                    />
                </v-col>
            </v-row>
            </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <ForeignNationalExamsList :exams="foreignNational?.exams ?? []" />
        </v-card-text>
    </BaseDialog>
</template>