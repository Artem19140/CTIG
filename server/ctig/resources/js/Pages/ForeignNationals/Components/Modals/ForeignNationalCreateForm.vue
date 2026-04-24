<script setup lang="ts">
import AppAutocomplete from '@components/UI/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '@components/UI/AppInput/AppInput.vue';
import AppFileInput from '@components/UI/AppFileInput/AppFileInput.vue';
import AppTextarea from '@components/UI/AppTextarea/AppTextarea.vue';
import { computed } from 'vue';
import countries from '@data/countries.json'
import AppOptionalInput from '@/components/UI/AppOptionalInput/AppOptionalInput.vue';
import AppDateInput from '@/components/UI/AppDateInput/AppDateInput.vue';

const props = defineProps<{
    form: any,
    mode?:string
}>()

const edit = computed(() => props.mode === 'edit')
const readonly = computed(() => props.form.processing)
function required (v:any) {
    return !!v || 'Поле обязательно'
}
</script>

<template>
    
    <v-card title="Персональные данные" class="mb-4">
        <v-card-text>
            <v-container fluid>
                <v-row class="mb-2">
                    <v-col cols="12">
                        <div class="text-subtitle-1 font-weight-medium">
                            Нотариальный перевод 
                        </div>
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput
                            :required="true && !edit"
                            label="Фамилия на русском"
                            :rules="[required]"
                            v-model="form.surname"
                            :readonly="readonly"
                            :error-messages="form.errors.surname"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                        :required="true && !edit"
                        label="Имя на русском"
                        v-model="form.name"
                        :readonly="readonly"
                        :error-messages="form.errors.name"
                />
                    </v-col>

                    <v-col cols="6" md="12">
                        <AppOptionalInput
                            :form="form"
                            v-model:input="form.patronymic"
                            v-model:checkbox="form.noPatronymic"
                            :input-attr="{label:'Отчество кириллица', 'error-messages':form.errors.patronymic}"
                            :checkbox-attr="{label:'Нет отчества кириллица', 'error-messages':form.errors.noPatronymic}"
                        />
                    </v-col>
                    <v-divider class="my-4" />


                    <v-col cols="12">
                        <div class="text-subtitle-1 font-weight-medium">
                            Паспортные данные
                        </div>
                    </v-col>

                    
                    <v-col cols="12" md="6">
                        <AppInput  
                            label="Фамилия на латинице"
                            :required="true && !edit"
                            v-model="form.surnameLatin"
                            :readonly="readonly"
                            :error-messages="form.errors.surnameLatin"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput  
                            label="Имя на латинице"
                            :required="true && !edit"
                            v-model="form.nameLatin"
                            :readonly="readonly"
                            :error-messages="form.errors.nameLatin"
                        />
                    </v-col>
                    <v-col cols="6" md="12">
                        <AppOptionalInput
                            :form="form"
                            v-model:input="form.patronymicLatin"
                            v-model:checkbox="form.noPatronymicLatin"
                            :input-attr="{label:'Отчество на латинице', 'error-messages':form.errors.patronymicLatin}"
                            :checkbox-attr="{label:'Нет отчества латиница', 'error-messages':form.errors.noPatronymicLatin}"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppDateInput 
                            :readonly="readonly"
                            v-model="form.dateBirth"
                            :error-messages="form.errors.dateBirth"
                            label="Дата рождения"
                        />
                    </v-col>

                    
                    <v-col cols="12" md="6"></v-col>
                    <v-col md="6" cols="12">
                        <AppAutocomplete
                            label="Гражданство"
                            :required="true && !edit"
                            :readonly="readonly"
                            item-title="text"
                            :items="countries"
                            item-value="value"
                            v-model="form.citizenship"
                            :error-messages="form.errors.citizenship"
                            clearable
                        />
                    </v-col>

                    <v-col md="6" cols="12">
                        <v-radio-group
                            v-model="form.gender"
                            :required="true && !edit"
                            :readonly="readonly"
                            inline
                            label="Пол"
                            :error-messages="form.errors.gender"
                        >
                            <v-radio
                                label="М"
                                value="M"
                            ></v-radio>
                            <v-radio
                                label="Ж"
                                value="F"
                            ></v-radio>
                        </v-radio-group>
                    </v-col>

                    <v-col cols="6" md="12">
                        <AppOptionalInput
                            :form="form"
                            v-model:input="form.passportSeries"
                            v-model:checkbox="form.noPassportSeries"
                            :input-attr="{label:'Серия паспорта', 'error-messages':form.errors.passportSeries}"
                            :checkbox-attr="{label:'Нет серии', 'error-messages':form.errors.noPassportSeries}"
                        />
                    </v-col>

                    <v-col cols="6" md="12">
                        <AppOptionalInput
                            :form="form"
                            v-model:input="form.passportNumber"
                            v-model:checkbox="form.noPassportNumber"
                            :input-attr="{label:'Номер паспорта', 'error-messages':form.errors.passportNumber}"
                            :checkbox-attr="{label:'Нет номера', 'error-messages':form.errors.noPassportNumber}"
                        />
                    </v-col>

                    <v-col cols="12" md="6">  
                        <AppInput
                            label="Кем выдан"
                            :required="true && !edit"
                            :readonly="readonly"
                            v-model="form.issuedBy"
                            :error-messages="form.errors.issuedBy"
                            clearable
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppDateInput 
                            label="Дата выдачи"
                            :readonly="readonly"
                            v-model="form.issuedDate"
                            :error-messages="form.errors.issuedDate"
                        />  
                    </v-col>

                    <v-divider class="my-4" />

                    <v-col cols="12">
                        <div class="text-subtitle-1 font-weight-medium">
                            Адрес регистрации  
                        </div>
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                            label="Адрес"
                            :readonly="readonly"
                            v-model="form.addressReg"
                            :error-messages="form.errors.addressReg"
                        /> 
                    </v-col>
                    
                    <v-divider class="my-4" />


                    <v-col cols="12">
                        <div class="text-subtitle-1 font-weight-medium">
                            Контакты
                        </div>
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                            label="Номер телефона"
                            :readonly="readonly"
                            v-model="form.phone"
                            :error-messages="form.errors.phone"
                        /> 
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>

    

    <v-card title="Документы" class="mb-4" variant="flat">
        <v-card-text>
            <v-container fluid>
                <v-row class="mb-2">
                    <v-col cols="12" md="6">
                        <AppFileInput
                        label="Скан паспорта PDF"
                        clearable
                        v-model="form.passportScan"
                        :readonly="readonly"
                        accept=".pdf,application/pdf"
                        :error-messages="form.errors.passportScan"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppFileInput
                        label="Скан перевода паспорта PDF"
                        clearable
                        v-model="form.passportTranslateScan"
                        :readonly="readonly"
                        accept=".pdf,application/pdf"
                        :error-messages="form.errors.passportTranslateScan"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>

    <v-card title="Дополнительная информация" class="mb-4" variant="flat" >
        <v-card-text>
            <v-container fluid>
                <v-row class="subtitle mb-4">
                    (например, лицо с ограниченными возможностями здоровья)
                </v-row>
                <v-row>
                    <AppTextarea
                        label="Введите комментарий"
                        auto-grow
                        rows="1"
                        v-model="form.comment"
                        :error-messages="form.errors.comment"
                    />
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>
    
</template>