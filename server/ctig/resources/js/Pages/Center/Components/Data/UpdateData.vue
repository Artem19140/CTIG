<script setup lang="ts">
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { Center } from '@/interfaces/Center';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';
import { router, useHttp } from '@inertiajs/vue3';
const props = defineProps<{
    center : Center
}>()

const emit = defineEmits<{
    (e:'modeShow'):void
}>()

const http = useHttp<Omit<Center, 'id'>>({
  name: props.center.name ?? '',
  ogrn: props.center.ogrn ?? '',
  inn: props.center.inn ?? '',
  address: props.center.address ?? '',
  certificatesIssueAddress: props.center.certificatesIssueAddress ?? '',
  directorFio: props.center.directorFio ?? '',
  commissionChairman: props.center.commissionChairman ?? '',
  nameGenitive: props.center.nameGenitive ?? '',
})

const edit = () => {
    http.put(`/centers/${props.center.id}`,{
        onSuccess(response, httpResponse) {
            router.reload()
            emit('modeShow')
        },
    })
}

const beforeClose = async () => {
    if(http.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить редактирование?')
        if(!ok) return 
    }
    http.resetAndClearErrors()
    emit('modeShow')
}
</script>

<template>
    <div v-if=center>
        <AppTextarea
            v-model="http.name"
            label="Название"
        />
        <AppInput 
            v-model="http.ogrn"
            label="ОГРН"
        />

        <AppInput 
            v-model="http.inn"
            label="ИНН"
        />

        <AppTextarea
            v-model="http.address"
            label="Адрес центра"
        />

        <AppTextarea
            v-model="http.certificatesIssueAddress"
            label="Адрес выдачи сертификатов"
        />

        <AppTextarea
            v-model="http.directorFio"
            label="Директор"
        />

        <AppTextarea
            v-model="http.commissionChairman"
            label="Председатель комиссии"
        />

        <AppTextarea
            v-model="http.nameGenitive"
            label="Название в родительском падеже(для документов)"
        />
        <AppPrimaryButton
            text="Обновить"
            @click="edit"
            :disabled="http.processing"
            :loading="http.processing"
            class="mr-4"
        />
        <v-btn @click="beforeClose">
            Отмена
        </v-btn>
    </div>
</template>