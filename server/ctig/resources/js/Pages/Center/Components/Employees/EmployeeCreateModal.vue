<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';
import { useSnackbarQueue } from '@/composables/useSnackbarQueue';

const isOpen = defineModel<boolean>({default:false})
const roles = ref()

const httpCreate = useHttp({
    surname:'',
    name:'',
    patronymic:'',
    jobTitle:'',
    roles:[],
    email:'',
    password:'',
    password_confirmation:''
})

const canClose = async (fn: () => void) => {
    if(httpCreate.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить добавление?')
        if(!ok) return
    }
    httpCreate.resetAndClearErrors()
    fn()
}

const create = () => {
    httpCreate.post('/employees',{
        onSuccess:() => {
            isOpen.value=false
            httpCreate.resetAndClearErrors()
            const {add} = useSnackbarQueue()
            add('Сотрудник добавлен', 'green')
        }
    })
}
const httpRoles = useHttp()
onMounted(() => {
    httpRoles.get('/roles', {
        onSuccess:(response : any) => {
            roles.value = response.data
        }
    })
})
</script>

<template>
    <BaseDialog 
        width="500"
        title='Добавить'
        v-model="isOpen"
        @before-close="(done) => canClose(done)"
    >
        <AppInput 
            label="Фамилия"
            v-model="httpCreate.surname"
            :error-messages="httpCreate?.errors?.surname"
        />
        <AppInput 
            label="Имя"
            v-model="httpCreate.name"
            :error-messages="httpCreate?.errors?.name"
        />
        <AppInput 
            label="Отчество"
            v-model="httpCreate.patronymic"
            :error-messages="httpCreate?.errors?.patronymic"
        />

        <AppInput 
            label="Должность"
            v-model="httpCreate.jobTitle"
            :error-messages="httpCreate?.errors?.jobTitle"
        />

        <AppAutocomplete 
            label="Роли"
            :loading="httpCreate.processing"
            v-model="httpCreate.roles"
            :items="roles"
            item-title="label"
            item-value="id"
            multiple
            :error-messages="httpCreate?.errors?.roles"
        />

        <AppInput 
            label="e-mail@"
            v-model="httpCreate.email"
            :error-messages="httpCreate?.errors?.email"
        />

        <AppPasswordConfirmation
            v-model:password="httpCreate.password"
            v-model:password-confirmation="httpCreate.password_confirmation"
            :password-attr="{'error-messages':httpCreate?.errors?.password}"
            :password-confirmation-attr="{'error-messages':httpCreate?.errors?.password_confirmation}"
        />
        <template #actions>
            <div>
                <AppAddButton 
                    text="Добавить"
                    @click="create"
                    :loading="httpCreate.processing"
                    :disabled="httpCreate.processing"
                />
            </div>
        </template>

    </BaseDialog>
</template>