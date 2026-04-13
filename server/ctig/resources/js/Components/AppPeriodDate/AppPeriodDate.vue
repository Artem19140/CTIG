<script setup lang="ts">

</script>

<template>
    период
    <div class="d-flex align-center gap-2">
    <AppInput 
        type="date"
        v-model="http.dateFrom"
        :error-messages="http.errors.dateFrom"
    />
    <AppInput 
        v-model="http.dateTo"
        :error-messages="http.errors.dateTo"
        type="date"
    />
    </div>
</template>