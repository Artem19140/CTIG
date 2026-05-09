<script setup lang="ts">
import AppNumberInput from '@/components/UI/AppNumberInput/AppNumberInput.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppTextarea from '@/components/UI/AppTextarea/AppTextarea.vue';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { Address } from '@/interfaces/Address';
import { router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    address:Address,
    centerId:number
}>()

const editMode = ref<boolean>(false)

    
const http = useHttp()

const deleteAddress = async () => {
    const {open} = useConfirmationOptionsDialog() 
    const ok = await open('Деактивировать адрес')
    if(!ok) return

    http.delete(`/centers/${props.centerId}/addresses/${props.address.id}`,{
        onSuccess:() => {
            router.reload()
        },
    })
}

const editHttp = useHttp({
    address:props.address.address ?? '',
    maxCapacity:props.address.maxCapcity
})


const edit = () => {
    editHttp.patch(`/centers/${props.centerId}/addresses/${props.address.id}`,{
        onSuccess:() => {
            router.reload({
                onSuccess:() => {
                    editMode.value = false
                },
            })
        }
    })
}

const cancellEdit = async () => {
    if(editHttp.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить редактирование?')
        if(!ok) return
    }
    editHttp.resetAndClearErrors()
    editMode.value = false
}
</script>

<template>
    <v-card class="mb-10">
        <v-card-title  v-if="!editMode">
              <div class="whitespace-pre-wrap break-words">
                {{ address.address }}
            </div>
        </v-card-title>
        <v-card-subtitle v-if="!editMode">Вместимость: {{ address.maxCapcity }} человек</v-card-subtitle>
        <div class="flex flex-column w-75 ml-4 mt-4">
            <div>
                <div class="mb-3" v-if="address.examsExists && editMode">
                    Почему редактирование адреса невозможно?
                    <AppTooltip 
                        text="Редактирование возможно до привязки экзаменов"
                        v-if="address.examsExists" 
                    />
                </div>
                <AppTextarea
                    v-model="editHttp.address"
                    :error-messages="editHttp.errors.address"
                    :disabled="address.examsExists" 
                    v-if="editMode" 
                    label="Адрес"
                    hint="Максимум 256 символов"
                    maxlength="256"
                />
                
            </div>
            

            <AppNumberInput
                v-model="editHttp.maxCapacity"
                :error-messages="editHttp.errors.maxCapacity"
                v-if="editMode" 
                label="Вместимость"
                :min="1"
            />
        </div>
        
        <v-card-actions>
            
            <v-btn
                v-if="!editMode"
                @click="editMode = true"
            >Редактировать</v-btn>
            <div v-else>
                <AppPrimaryButton
                    text="Сохранить"
                    :disabled="!editHttp.isDirty"
                    :loading="editHttp.processing"
                    @click="edit"
                />
                <v-btn
                    @click="cancellEdit"
                >Отмена</v-btn>
            </div>
           
            <v-btn 
                v-if="!editMode"
                color="red"
                @click="deleteAddress"
                :loading="http.processing"
                :disabled="http.processing"
            >
                Деактивировать
            </v-btn>

        </v-card-actions>
    </v-card>
</template>