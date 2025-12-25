<script setup lang="ts">
  import NavigationPanel from './components/NavigationPanel.vue';  
</script>

<template> 
  <v-app>
    <v-app-bar app>
      <v-toolbar-title>ЦТИГ УдГУ</v-toolbar-title>
    </v-app-bar>
    <NavigationPanel />
      <v-main>
        <router-view v-slot="{ Component }">  
            <v-fade-transition hide-on-leave>
              <component :is="Component" />
            </v-fade-transition>
        </router-view>
      </v-main> 
  </v-app>
</template>

<style scoped></style>
