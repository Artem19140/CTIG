<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import { ForeignNational } from '@interfaces/interfaces';
import ForeignNationalCreateForm from './ForeignNationalCreateForm.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppPrimaryButton from '@components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { DateFormatter } from '@helpers/DateFormatter';


const props = defineProps<{
    foreignNational: ForeignNational,
    onEdit: (foreignNational:ForeignNational) => void
}>()

const isOpen = defineModel<boolean>({default:false})

const form = useForm({
    surname: props.foreignNational?.surname, 
    name: props.foreignNational?.name,
    patronymic: props.foreignNational?.patronymic ?? "",
    surnameLatin: props.foreignNational?.surnameLatin,
    nameLatin: props.foreignNational?.nameLatin,
    patronymicLatin: props.foreignNational?.patronymicLatin ?? "",
    passportNumber: props.foreignNational?.passportNumber,
    passportSeries: props.foreignNational?.passportSeries,
    issuedBy: props.foreignNational?.issuedBy,
    issuedDate: new DateFormatter(props.foreignNational?.issuedDate).format('Y-m-d') ?? '',
    citizenship: props.foreignNational?.citizenship ,
    phone: props.foreignNational?.phone,
    dateBirth: new DateFormatter(props.foreignNational?.dateBirth).format('Y-m-d') ?? '',
    gender: props.foreignNational?.gender,
    comment: props.foreignNational?.comment,
    passportTranslateScan:null,
    passportScan:null,
})

const edit = () => {
    form.put(`foreign-nationals/${props.foreignNational.id}`,{
        onSuccess:(page) => {
            if(page.flash.foreignNational){
                props.onEdit(page.flash.foreignNational)
                isOpen.value=false
            }
        }
    })
}

const {confirmOpen} = useConfirmDialog()
const beforeClose = async (fn: () => void) => {
    if(form.isDirty){
        const ok = await confirmOpen('Отменить редактирование?')
        if(!ok) return
    }
    form.resetAndClearErrors()
    fn()
}

</script>

<template>
    <BaseDialog
        width="1000"
        height="100%"
        v-model="isOpen"
        title="Редактирование ИГ"
        @before-close="(done) => beforeClose(done)"
    >
        <ForeignNationalCreateForm 
            :form="form"
            :mode="'edit'"
        />

        <template #actions>
            <AppPrimaryButton
                text="Сохранить"
                @click="edit"
                :loading="form.processing"
                :disabled="form.processing || !form.isDirty"
            />
        </template>
    </BaseDialog>
</template>