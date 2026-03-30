<script setup lang="ts">
import countries from '../../../../../storage/app/public/countries.json'
import AppAutocomplete from '../../../Components/AppAutocomplete/AppAutocomplete.vue';
import AppInput from '../../../Components/AppInput/AppInput.vue';
import AppFileInput from '../../../Components/AppFileInput/AppFileInput.vue';
import AppTextarea from '../../../Components/AppTextarea/AppTextarea.vue';
const props = defineProps<{
    form: any,
    mode?:string
}>()

const edit = props.mode === 'edit'
</script>

<template>
    <v-card title="Персональные данные" class="mb-4">
        <v-card-text>
            <v-container fluid>
                <v-row density="comfortable">
                    <v-col cols="12"  class="subtitle" >
                        Нотариальный перевод
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput
                            label="Фамилия на русском"
                            v-model="form.surname"
                            :error-messages="form.errors.surname"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                    label="Имя на русском"
                    v-model="form.name"
                    :error-messages="form.errors.name"
                />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput
                            label="Отчество на русском"
                            v-model="form.patronymic"
                            :error-messages="form.errors.patronymic"
                            :disabled="form.noPatronymic"
                        />
                    </v-col>
                    
                    <v-col cols="12" md="6">
                        <v-checkbox
                            v-if="!edit"
                            v-model="form.noPatronymic" 
                            label="Нет отчества"
                            :error-messages="form.errors.noPatronymic"
                        ></v-checkbox>
                    </v-col>


                    <v-col cols="12" class="subtitle">
                        Паспортные данные
                    </v-col>

                    
                    <v-col cols="12" md="6">
                        <AppInput  
                            label="Фамилия на латинице"
                            v-model="form.surnameLatin"
                            :error-messages="form.errors.surnameLatin"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput  
                            label="Имя на латинице"
                            v-model="form.nameLatin"
                            :error-messages="form.errors.nameLatin"
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                            label="Отчество на латинице"
                            v-model="form.patronymicLatin"
                            :error-messages="form.errors.patronymicLatin"
                            :disabled="form.noPatronymic"
                        />  
                    </v-col>

                    <v-col cols="12" md="6">
                            <AppInput 
                                label="Дата рождения"
                                v-model="form.dateBirth"
                                :error-messages="form.errors.dateBirth"
                                type="date"
                            /> 
                    </v-col>

                    

                    <v-col md="6" cols="12">
                        <AppAutocomplete
                            label="Гражданство"
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

                    <v-col cols="12" md="6">
                        <AppInput  
                            label="Серия паспорта"
                            v-model="form.passportSeries"
                            :error-messages="form.errors.passportSeries"
                            :disabled="form.noPassportSeries"
                        />  
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-checkbox
                            v-if="!edit"
                            v-model="form.noPassportSeries" 
                            label="Нет серии"
                            :error-messages="form.errors.noPassportSeries"
                        ></v-checkbox>
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput 
                            label="Номер паспорта"
                            v-model="form.passportNumber"
                            :error-messages="form.errors.passportNumber"
                            :disabled="form.noPassportNumber"
                        />  
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-checkbox
                            v-if="!edit"
                            v-model="form.noPassportNumber" 
                            label="Нет номера"
                            :error-messages="form.errors.noPassportNumber"
                        ></v-checkbox>
                    </v-col>

                    <v-col cols="12" md="6">  
                        <AppInput
                            label="Кем выдан"
                            v-model="form.issuedBy"
                            :error-messages="form.errors.issuedBy"
                            clearable
                        />
                    </v-col>

                    <v-col cols="12" md="6">
                        <AppInput
                            label="Дата выдачи"
                            v-model="form.issuedDate"
                            :error-messages="form.errors.issuedDate"
                            type="date"
                        /> 
                    </v-col>

                        <v-col cols="12" class="subtitle">
                            Миграционная карта
                        </v-col>

                        <v-col cols="12" md="6">
                            <AppInput  
                                label="Реквизиты миграционной карты"
                                v-model="form.migrationCardRequisite"
                                :error-messages="form.errors.migrationCardRequisite"
                                :disabled="form.noMigrationCard"
                            /> 
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-checkbox
                                v-if="!edit"
                                v-model="form.noMigrationCard" 
                                label="Нет миграционной карты"
                                :error-messages="form.errors.noMigrationCard"
                                
                            ></v-checkbox>
                        </v-col>
                        <v-col cols="12" class="subtitle">
                            Адрес регистрации
                        </v-col>

                    
                        <AppInput  
                            label="Адрес регистрации"
                            v-model="form.addressReg"
                            :error-messages="form.errors.addressReg"
                        /> 
                        <v-col cols="12" md="6"></v-col>
                        <v-col cols="12"  class="subtitle">
                            Контакты    
                        </v-col>

                        <v-col cols="12" md="6">
                            <AppInput 
                                label="Номер телефона"
                                v-model="form.phone"
                                :error-messages="form.errors.phone"
                            /> 
                        </v-col>
                </v-row>
            </v-container>
        </v-card-text>
    </v-card>

    

    <v-card title="Документы" class="mb-4">
        <v-card-text>
            <v-container fluid>
                <v-row density="comfortable">
                    <AppFileInput 
                        label="Скан перевода паспорта PDF"
                        v-model="form.passportTranslateScan"
                        clearable
                        accept=".pdf,application/pdf"
                        :error-messages="form.errors.passportTranslateScan"
                    />

                    <AppFileInput 
                        label="Скан паспорта PDF"
                        clearable
                        v-model="form.passportScan"
                        accept=".pdf,application/pdf"
                        :error-messages="form.errors.passportScan"
                    />
                    
                </v-row>
                <v-row>
                    
                </v-row>
            </v-container>
        </v-card-text>
        
    </v-card>

    <v-card title="Дополнительная информация" class="mb-4">
        <v-card-text>
            <v-container fluid>
                <v-row density="comfortable"  class="subtitle mb-4">
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