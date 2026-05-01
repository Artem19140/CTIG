<script setup lang="ts">
import { ref, watch } from 'vue';
import AppAddButton from '@/components/UI/AppAddButton/AppAddButton.vue';
import { useModals } from '@/composables/useModals';
import AddressCard from './AddressCard.vue';
import { Address } from '@/interfaces/Address';

const props = defineProps<{
    addresses:Address[]
}>()
watch(() => props.addresses, (value) => {
    addresses.value = value
})

const addresses = ref<Address[]>(props.addresses)

addresses.value.map(v => v.loading = false)

const add = () => {
    const {open} = useModals()
    open('addressCreate')
}

</script>

<template>
    <v-toolbar color="white">
        <v-spacer />
        <div class="flex gap-4">
            <AppAddButton 
                @click="add"
            />
        </div>
    </v-toolbar>
    
    <div class="mt-4 p-4" >
        <AddressCard 
            v-for="address in addresses" 
            :key="address.id"
            :address="address"
        />
    </div>
</template>