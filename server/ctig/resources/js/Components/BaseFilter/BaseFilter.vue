<script setup lang="ts">
import { computed } from 'vue';
import PrimaryButton from '../PrimaryButton/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    form : any,
    url:string,
    appliedFilters:any
}>()


const filledCount = computed(() => {
  const formData = props.form.data()

  return Object.entries(props.appliedFilters)
    .filter(([key, value]) => {
      if (!(key in formData)) return false

      if (typeof value === 'string') return value.trim() !== ''
      return value != null
    })
    .length
})

const find = () => {
    console.log(props.form)
    props.form.get(props.url, {
        preserveState: true,
        preserveScroll: true
    })
}

const clean = () => {
    props.form.reset()
    router.get(props.url,{})
}
</script>

<template>
    <v-menu
        width="350"
        :close-on-content-click="false"
    >
        <template v-slot:activator="{ props }">
            <v-btn 
                icon 
                variant="text"
                v-bind="props"
            >
                <v-badge :content="filledCount" color="red" :model-value="filledCount > 0">
                    <v-icon>mdi-filter-menu</v-icon>
                </v-badge>
            </v-btn>
        </template>
        <v-card
            title="Фильтры"
        >
            <v-card-text>
                <slot />
            </v-card-text>
            <v-card-actions class="flex justify-center">
                <PrimaryButton
                    prepend-icon="mdi-magnify"
                    text="Найти"
                    @click="find"
                    :disabled="form.processing"
                    :loading="form.processing"
                />
                <v-btn
                    @click="clean"
                >
                    Очистить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-menu>
</template>