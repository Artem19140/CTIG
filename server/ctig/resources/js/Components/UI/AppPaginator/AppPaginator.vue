<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    meta:{
        current_page:number,
        from:number,
        per_page:number,
        to:number
    },
    links:{
        first:string,
        last:string | null,
        prev:string | null,
        next:string
    },
    loading:boolean
}>()

const loading = defineModel<boolean>({default:false})

const visit = (url:string | null) => {
    if(!url) return
    loading.value = true
    router.visit(url,{
        preserveScroll:true,
        preserveState:true,
        replace:true,
        onFinish:() => {
            loading.value = false
        }
    })
}
</script>

<template>
    <div class="flex items-center justify-between px-4 py-2">
  <!-- Пагинация -->
  <div class="flex items-center gap-2">
    <v-btn
      variant="text"
      icon
      :disabled="!links.first"
      @click="visit(links.first)"
    >
      <v-icon size="20">mdi-page-first</v-icon>
    </v-btn>

    <v-btn
      variant="text"
      icon
      :disabled="!links.prev || loading"
      @click="visit(links.prev)"
    >
      <v-icon size="20">mdi-chevron-left</v-icon>
    </v-btn>

    <div class="px-3 py-1 text-sm font-medium bg-grey-lighten-4 rounded">
      {{ meta.current_page }}
    </div>

    <v-btn
      variant="text"
      icon
      :disabled="!links.next || loading"
      @click="visit(links.next)"
    >
      <v-icon size="20">mdi-chevron-right</v-icon>
    </v-btn>
  </div>

  <div class="flex items-center gap-4 text-sm text-grey-darken-1">
    <span class="bg-grey-lighten-4 px-3 py-1 rounded">
      {{ meta.from }} – {{ meta.to }}
    </span>

    <span>
      Записей на странице:
      <span class="font-medium text-black">
        {{ meta.per_page }}
      </span>
    </span>
  </div>
</div>
</template>