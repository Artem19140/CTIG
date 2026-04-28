<script setup lang="ts">
import AppInput from '@/components/UI/AppInput/AppInput.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppProgressCircular from '@/components/UI/AppProgressCircular/AppProgressCircular.vue';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';
import { Address } from '@/interfaces/Interfaces';
import { router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    address:Address
}>()

const editMode = ref<boolean>(false)
const http = useHttp()

const toggleAddressActivity = async () => {
    const {open} = useConfirmationOptionsDialog()
    const message = props.address.isActive ?  'Активировать адрес' : 'Деактивировать адрес' 
    const ok = await open(message)
    if(!ok) return
    props.address.loading = true
    http.patch(`/addresses/${props.address.id}/active`,{
        onSuccess:() => {
            props.address.isActive = !props.address.isActive
        },
        onFinish:() =>{
            props.address.loading = false
        }
    })
}

const editHttp = useHttp({
    address:props.address.address ?? ''
})

const editLoading = ref<boolean>(false)

const edit = () => {
    editLoading.value = true
    editHttp.patch(`/addresses/${props.address.id}`,{
        onSuccess:() => {
            router.reload({
                onSuccess:() => {
                    editMode.value = false
                },
            })
        },
        onFinish:()=>{
            editLoading.value = false
        }
    })
}
</script>

<template>
    <v-card class="mb-10">
        <v-card-title v-if="!editMode">{{ address.address }}</v-card-title>
        <div class="flex w-75 ml-4 mt-4">
            <AppInput
                v-model="editHttp.address"
                :error-messages="editHttp.errors.address"
                v-if="editMode" 
                label="Адрес"
            />
        </div>
        
        <v-card-actions>
            <v-tooltip text="Редактирование возможно до привязки экзаменов" v-if="address.examsExists">
                <template #activator="{ props }">
                    <v-icon v-bind="props" size="small" class="ml-1">
                        mdi-information-outline
                    </v-icon>
                </template>
            </v-tooltip>
            <v-btn
                v-if="!editMode"
                :disabled="address.examsExists"
                @click="editMode = true"
            >Редактировать</v-btn>
            <div v-else>
                <AppPrimaryButton
                    text="Сохранить"
                    @click="edit"
                />
                <v-btn
                    @click="editMode = false"
                >Отмена</v-btn>
                <AppProgressCircular v-if="editLoading" />
            </div>
           
            <v-btn 
                v-if="!editMode"
                :color="address.isActive ? 'green' : 'red'"
                @click="() => toggleAddressActivity()"
            >
                {{address.isActive ?  'Активировать' : 'Деактивировать' }}
            </v-btn>
            <AppProgressCircular v-if="address.loading" size="32" />
        </v-card-actions>
    </v-card>
</template>