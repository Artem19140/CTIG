<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
    citizenship: '', 
})

const citizenships = [
    {name:'Узбекистан', value:'UZ'},
    {name:'Казахстан', value:'KZ'}
]

const menu = ref(false)

const search = () => {
    form.get('/students', {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
        console.log('Форма успешно отправлена!', page)
        menu.value = false
    },
    onError: (errors) => {
        console.log('Ошибки валидации', errors)
    }
    })
}

const close = () => {
    form.reset()
    form.clearErrors()
    menu.value = false
    form.get('/students', {
    preserveState: true,
    preserveScroll: true,
    
    onSuccess: (page) => {
        console.log('Форма успешно отправлена!', page)
        menu.value = false
    },
    onError: (errors) => {
        console.log('Ошибки валидации', errors)
    }
})
}

</script>

<template>
    <v-menu
        :close-on-content-click="false"
        v-model="menu"
    >
      <template v-slot:activator="{ props }">
        <v-btn
          color="primary"
          v-bind="props"
        >
          Фильтры
        </v-btn>
      </template>
      <v-card
        width="500"
      >
        <v-card-text>
            <v-list>
                <v-list-item>
                    <v-autocomplete 
                        label="Гражданство"
                        item-title="name"
                        :items="citizenships"
                        item-value="value"
                        v-model="form.citizenship"
                        :error-messages="form.errors.citizenship"
                        clearable
                    />
                </v-list-item>
            </v-list>
        </v-card-text>
        <v-card-actions class="flex justify-center">
                <v-btn
                    color="primary"
                    variant="flat"
                    @click="search"
                >Поиск</v-btn>
                <v-btn
                    @click="close"
                >Сбросить</v-btn>
        </v-card-actions>
      </v-card>
      
      
    </v-menu>
</template>