<script setup lang="ts">
import { ref, watch } from 'vue';
import AppCheckbox from '../AppCheckbox/AppCheckbox.vue';
import AppInput from '../AppInput/AppInput.vue';

const props = defineProps<{
    inputAttr:Object,
    checkboxAttr:Object
}>()

const input = defineModel<string | null>('input')
const checkbox = defineModel<boolean>('checkbox', {default:false})

const disabled = ref<boolean>(false)
watch(() => checkbox.value, () => {
    if(checkbox.value){
        input.value = null
        disabled.value = true
    }else{
        disabled.value = false
    }
})
</script>

<template>
    <div class="flex items-center">
        <div class="w-49/100">
            <AppInput 
                :disabled="disabled"
                v-model="input"
                v-bind="inputAttr"
            />
        </div>
        <AppCheckbox 
            v-model="checkbox"
            v-bind="checkboxAttr"
        />
    </div>
</template>