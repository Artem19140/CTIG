<script setup lang="ts">

</script>

<template>
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn 
            icon 
            variant="text" 
            v-bind="props" 
            size="small"
        >
            <v-icon>mdi-eye</v-icon>
        </v-btn>
      </template>
      <v-card>
        <v-card-text>
            <slot />
        </v-card-text>
       
      </v-card>
      
    </v-menu>
</template>