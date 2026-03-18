<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    const props = defineProps<{
        obj : any
    }>()

    const goTo = (url? :string) => {
        if(!url) return
        router.get(url, {}, {
        })
    }
</script>

<template>

    <div class="text-center pt-2"> 
                <v-btn
                    variant="text"
                    icon
                    :disabled="!obj.links.prev"
                    @click="goTo(obj.links.prev)"
                >
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
                <span class="px-1">  {{obj.meta.current_page}} </span>

                <v-btn
                    variant="text"
                    icon
                    :disabled="!obj.links.next"
                    @click="goTo(obj.links.next)"
                >
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
            </div>
</template>