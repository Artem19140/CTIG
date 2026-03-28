<script setup lang="ts">
import axios from 'axios';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { router } from '@inertiajs/vue3';
import { Exam } from '../../../../interfaces/interfaces';
import { useModals } from '../../../../Composables/useModals';
import { attemptResultStatus } from '../../../../Helpers/heplers';
import AppStatusChip from '../../../../Components/AppStatusChip/AppStatusChip.vue';

const props = defineProps<{
    foreignNationals : any,
    exam: Exam
}>()

function foreignNationalShowModal(event:Event, {item}: any) {
    const {open} = useModals()
    open('foreignNationalShow', {foreignNationalId:item.id})  
}

const headers = [
    {title : "ФИО",sortable: false, key: 'fullName', align: 'start' },
    {title : "Паспорт",sortable: false, key: 'fullPassport', align: 'start' },
    {title : "Результаты",sortable: false, key: 'results', align: 'center' },
    {title : "Оплата",sortable: false, key: 'hasPayment', align: 'center' },
    {title : "",sortable: false, key: 'actions', align: 'end' },
]

const download = (foreignNationalId :number) => {
    window.open(`foreignNationals/${foreignNationalId}/application-forms?examId=${props.exam?.id}`)
}

const transfer = () => {
    axios.post(``)
}
const {confirmOpen} = useConfirmDialog()
const cancell = async (foreignNationalId :number) => {
    const ok = await confirmOpen('Отменить запись на экзамен?')
    if(!ok){
        return
    }
    router.delete(`exams/${props.exam?.id}/foreign-nationals/${foreignNationalId}`,{
        onSuccess: (page) =>{
            const success = page.flash.success
        }
    })
}
</script>

<template>
    <v-data-table 
        :items="foreignNationals"
        hide-default-footer
        :headers="headers"
        fixed-header
        hover
        @click:row="foreignNationalShowModal"
    >
        <template #item.hasPayment="{ item }">
                <v-icon :color="item ? 'green' : 'red'">
                    {{ item ? 'mdi-check-circle' : 'mdi-close-circle' }}
                </v-icon>
            </template>
        <template #item.actions="{item}">
            <ThreeDotDropdown>
                <AppListDropDownItem 
                    title="Скачать заявление" 
                    @click="download(item.id)"
                />
                <AppListDropDownItem 
                    title="Перенести запись" 
                    @click="transfer"
                    v-if="!exam?.isGoing && !exam?.isPast"
                />
                <AppListDropDownItem 
                    title="Подтвердить оплату" 
                    @click="transfer"
                    v-if="!exam?.isGoing && !exam?.isPast"
                />

                <AppListDropDownItem 
                    v-if="!exam?.isGoing && !exam?.isPast"
                    title="Отменить запись" 
                    @click="cancell(item.id)"
                    color="text-red"
                />
            </ThreeDotDropdown>
        </template>
        <template #item.results="{ item }">
            <AppStatusChip
                :color="attemptResultStatus(item?.attempts?.[0] ?? null, exam?.isPast).color"
                :text="attemptResultStatus(item?.attempts?.[0] ?? null, exam?.isPast).text"
            />
        </template>
    </v-data-table>
</template>