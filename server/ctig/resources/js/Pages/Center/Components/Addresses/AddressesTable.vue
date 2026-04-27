<script setup lang="ts">
import { Address } from '@/interfaces/Interfaces';
import { useConfirmationOptionsDialog } from '@/composables/useConfirmationOptionsDialog';

const props = defineProps<{
    addresses:Address[]
}>()

const toggleAddressActivity = async (address : Address) => {
    const {open} = useConfirmationOptionsDialog()
    const message = address.isActive ?  'Активировать адрес' : 'Деактивировать адрес' 
    const ok = await open(message)
    if(!ok) return
    alert('Тут будет смена статуса адреса')
}
</script>

<template>
    <v-toolbar>

    </v-toolbar>
    <div class="mt-4 p-4">
        <v-card v-for="address in addresses" :key="address.id" class="mb-10">
            <v-card-title>{{ address.address }}</v-card-title>
            <v-card-text>
                {{ address.address }}
            </v-card-text>
            <v-card-actions>
                <v-btn 
                    :color="address.isActive ? 'green' : 'red'"
                    @click="() => toggleAddressActivity(address)"
                >
                    {{address.isActive ?  'Активировать' : 'Деактивировать' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>