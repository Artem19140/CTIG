<script setup lang="ts">
import RenderBlocks from './RenderBlocks.vue';

const props = defineProps<{
    value : string
}>()
</script>

<template>
    
    <v-table class="table-bordered mt-5">
        <tbody>
            <tr
                v-for="(row, index) in value"
                :key="index"
            >
                <td 
                    v-for="(data,i) in row" :key="i"
                >
                    <RenderBlocks :content="[data]" />
                </td>
            </tr>
        </tbody>
    </v-table>
</template>

<style lang="css" scoped>
    .table-bordered td {
        border: 1px solid rgba(0, 0, 0, 0.12);
    }

</style>