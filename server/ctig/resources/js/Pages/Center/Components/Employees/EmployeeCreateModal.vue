<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AppAddButton from '@components/UI/AppAddButton/AppAddButton.vue';
import BaseDialog from '@components/BaseComponents/BaseDialog/BaseDialog.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import { useForm } from '@inertiajs/vue3';
import { useConfirmDialog } from '@composables/useConfirmDialog';
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import {useHttp} from '@inertiajs/vue3';
import AppPasswordConfirmation from '@/components/UI/AppPasswordConfirmation/AppPasswordConfirmation.vue';

const isOpen = defineModel<boolean>({default:false})
const roles = ref()

const form = useForm({
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
    if(form.isDirty){
        const {confirmOpen} = useConfirmDialog()
        const ok = await confirmOpen('Отменить добавление?')
        if(!ok) return
    }
    form.resetAndClearErrors()
    fn()
}

const create = () => {
    form.post('/employees',{
        onSuccess:(page) => {
            if(!page.flash.success) return
            isOpen.value=false
            form.resetAndClearErrors()
        }
    })
}
const http = useHttp()
onMounted(() => {
    http.get('/roles', {
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
            v-model="form.surname"
            :error-messages="form?.errors?.surname"
        />
        <AppInput 
            label="Имя"
            v-model="form.name"
            :error-messages="form?.errors?.name"
        />
        <AppInput 
            label="Отчество"
            v-model="form.patronymic"
            :error-messages="form?.errors?.patronymic"
        />

        <AppInput 
            label="Должность"
            v-model="form.jobTitle"
            :error-messages="form?.errors?.jobTitle"
        />

        <AppAutocomplete 
            label="Роли"
            :loading="http.processing"
            v-model="form.roles"
            :items="roles"
            item-title="label"
            item-value="id"
            multiple
            :error-messages="form?.errors?.roles"
        />

        <AppInput 
            label="email"
            v-model="form.email"
            :error-messages="form?.errors?.email"
        />

        <AppPasswordConfirmation
            v-model:password="form.password"
            v-model:password-confirmation="form.password_confirmation"
            :password-attr="{'error-messages':form?.errors?.password}"
            :password-confirmation-attr="{'error-messages':form?.errors?.password_confirmation}"
        />
        <template #actions>
            <div>
                <AppAddButton 
                    text="Добавить"
                    @click="create"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </div>
        </template>

    </BaseDialog>
</template>