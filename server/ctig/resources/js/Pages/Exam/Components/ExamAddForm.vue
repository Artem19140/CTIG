<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3'
import AppInput from '../../../Components/UI/AppInput/AppInput.vue';
import axios from 'axios'
import { router } from '@inertiajs/vue3'

defineProps({ addresses: Array, examTypes:Array })

const form = useForm({
    examType: null,
    capacity: null,
    address:null
})
</script>

<template>
    <form @submit.prevent="form.post('/login', {preserveScroll: true})">
        <v-autocomplete 
            label="Тип экзамена"
            item-title="name"
            :items="examTypes"
            v-model="form.examType"
            clearable
        />

        <v-number-input
          :min="0"
          label="Количество студентов"
          v-model="form.capacity"
          clearable
        ></v-number-input>
        
        <v-autocomplete 
            label="Адрес"
            item-title="address"
            :items="addresses"
            v-model="form.address"
            clearable
        />
    </form>            
</template>