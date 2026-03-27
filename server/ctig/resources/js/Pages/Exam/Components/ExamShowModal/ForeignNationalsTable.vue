<script setup lang="ts">
import axios from 'axios';
import { useConfirmDialog } from '../../../../Composables/useConfirmDialog';
import ThreeDotDropdown from '../../../../Components/ThreeDotDropdown/ThreeDotDropdown.vue';
import AppListDropDownItem from '../../../../Components/AppListDropDownItem/AppListDropDownItem.vue';
import { router } from '@inertiajs/vue3';
import { Exam } from '../../../../interfaces/interfaces';
import { useModals } from '../../../../Composables/useModals';

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

const getAttemptResultLabel = (result: boolean) => {
    return result ? 'Пройдено' : 'Не пройдено'
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
                    v-if="!exam?.isGoing && !exam?.isPast"
                    title="Отменить запись" 
                    @click="cancell(item.id)"
                    color="text-red"
                />
            </ThreeDotDropdown>
        </template>
        <template #item.results="{item}">
            <span
            :class="!item?.attempts[0]
                ? 'text-gray-400'
                : item?.attempts[0]?.isPassed
                ? 'text-green-500'
                : 'text-red-500'"
            >
            {{ (item?.attempts[0] && exam?.isPast)
                ? getAttemptResultLabel(item?.attempts[0].isPassed)
                : '-' }}
            </span>
        </template>
    </v-data-table>
</template>