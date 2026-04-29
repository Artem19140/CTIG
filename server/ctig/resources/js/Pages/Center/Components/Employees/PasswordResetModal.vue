<script setup lang="ts">
import BaseDialog from '@/components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import AppPasswordInput from '@/components/UI/AppPasswordInput/AppPasswordInput.vue';
import AppPrimaryButton from '@/components/UI/AppPrimaryButton/AppPrimaryButton.vue';
import AppTooltip from '@/components/UI/AppTooltip/AppTooltip.vue';
import { useConfirmDialog } from '@/composables/useConfirmDialog';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';
import { User } from '@/interfaces/Interfaces';
import { useHttp } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    user:User
}>()

const isOpen = defineModel<boolean>({default:false})

const http = useHttp({
    password:null,
    password_confirmation:null,
    adminPassword:null
})

const reset = () => {
    http.patch(`/employees/${props.user.id}/password`,{
        onSuccess(response, httpResponse) {
            isOpen.value = false
            const {add} = useSnackbarQueue()
            add('Пароль успешно сброшен', 'green')
        },
    })
}

const disabled = computed(() => http.processing || (!http.adminPassword || !http.password || !http.password_confirmation))
</script>

<template>
    <BaseDialog
        v-model="isOpen"
        title="Сброс пароля"
        width="500"
        @before-close="async (close) =>  {
            if(http.isDirty){
                const ok = await useConfirmDialog().confirmOpen('Отменить сброс пароля?')
                if(!ok) return
            }
            http.resetAndClearErrors()
            close()
        }"
    >
        <div class="flex items-center mb-2 gap-1">
            <div>Введите новый пароль сотрудника</div>
            <AppTooltip 
                
                text="Пароль отображается один раз при сбросе и не сохраняется"
            />
        </div>
        <AppPasswordConfirmation
            v-model:password="http.password"
            v-model:password-confirmation="http.password_confirmation"
            :password-attr="{'error-messages':http.errors.password, label:'Новый пароль'}"
            :password-confirmation-attr="{'error-messages':http.errors.password_confirmation}"
        />
        <div class="flex items-center mb-2 gap-1">
            <div>Введите свой пароль</div>
            <AppTooltip 
                text="Для сброса пароля сотрудника нужно ввести свой пароль"
            />
        </div>
        

        <AppPasswordInput 
            v-model="http.adminPassword"
            :error-messages="http.errors.adminPassword"
        />
        <template #actions>
            <AppPrimaryButton
                text="Сбросить"
                @click="reset"
                :loading="http.processing"
                :disabled="disabled"
            />
        </template>
    </BaseDialog>
    
</template>