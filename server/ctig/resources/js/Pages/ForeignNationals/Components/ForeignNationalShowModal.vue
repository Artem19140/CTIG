<script setup lang="ts">
import BaseDialog from '../../../Components/BaseDialog/BaseDialog.vue';
import ForeignNationalExamsList from './ForeignNationalExamsList.vue';
import ForeignNationalActionsDropdown from './ForeignNationalActionsDropdown.vue';
import { onMounted, ref } from 'vue';
import type { ForeignNational } from '../../../interfaces/interfaces';
import { useHttp } from '@inertiajs/vue3'

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
            />
        </template>

        <v-card-text>
            <v-card-text>
                <div class="flex">
                    <v-avatar color="surface-variant cursor-pointer hover:opacity-80 transition-opacity"  size="150" >
                        <v-img 
                            :src="foreignNational?.photoPath"
                            cover 
                            @click="showDocument('')"
                        />
                    </v-avatar>
                <div class="flex flex-col justify-center ml-8">
                    <div class="text-headline-small">{{`${foreignNational?.surname} ${foreignNational?.name} ${foreignNational?.patronymic}`}}</div>
                    <div class="text-subtitle-1">{{`${foreignNational?.surnameLatin} ${foreignNational?.nameLatin} ${foreignNational?.patronymicLatin}`}}</div>
                    <div class="text-subtitle-2">{{foreignNational?.dateBirth}}</div> 
                </div>
                </div>
            </v-card-text>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Паспорт</v-list-item-subtitle>
                    <v-list-item-title>{{`${foreignNational?.fullPassport ?? ''}, выдан ${foreignNational?.issuedDate ?? ''} (${foreignNational?.issuedBy ?? ''})`}}</v-list-item-title>
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
            <v-list>
                <v-list-item>
                    <v-list-item-subtitle>Скан паспорта</v-list-item-subtitle>
                    <v-list-item-title v-if="!foreignNational?.passportScan">-</v-list-item-title>
                    <v-img
                        v-else 
                        :width="50"
                        class="mt-4 cursor-pointer hover:opacity-80 transition-opacity"
                        src="https://cdn-icons-png.flaticon.com/512/9034/9034536.png"
                        @click="showDocument(foreignNational?.passportScan)"
                    ></v-img>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text>
            <ForeignNationalExamsList :exams="foreignNational?.exams ?? []" />
        </v-card-text>
    </BaseDialog>
</template>