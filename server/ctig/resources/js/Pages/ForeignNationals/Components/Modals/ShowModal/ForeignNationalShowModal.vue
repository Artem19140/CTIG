<script setup lang="ts">
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import ForeignNationalEnrollmentsList from './ForeignNationalEnrollmentsList.vue';
import ForeignNationalActionsDropdown from './ForeignNationalActionsDropdown.vue';
import { onMounted, ref } from 'vue';
import type { Enrollment, ForeignNational } from '@interfaces/Interfaces';
import { useHttp } from '@inertiajs/vue3'
import { DateFormatter } from '@helpers/DateFormatter';

const props = defineProps<{
    foreignNationalId?:number
}>()

const http = useHttp()

const isOpen = defineModel<boolean>({default:false})
const foreignNational = ref<ForeignNational | null>(null)


const getForeignNational = async () => {
    http.get(`/foreign-nationals/${props.foreignNationalId}`,{
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

const enroll = (value:Enrollment) => {
    if (!foreignNational.value) return
    foreignNational.value.enrollments = [value, ... foreignNational.value.enrollments]
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
        skeleton="heading, paragraph, paragraph, divider, list-item-two-line, divider, image, divider, table"
    >
        <template #titleActions>
            <ForeignNationalActionsDropdown 
                :foreignNational="foreignNational"
                @enroll="enroll"
                @edit="edit"
            />
        </template>

        <v-card-text class="ml-4">
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
                    <v-list-item-subtitle>Номер телефона</v-list-item-subtitle>
                    <v-list-item-title>{{foreignNational?.phone ?? ''}}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-text class="ml-4">
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
            <ForeignNationalEnrollmentsList v-if="foreignNational" :foreignNational="foreignNational" />
        </v-card-text>
    </BaseDialog>
</template>