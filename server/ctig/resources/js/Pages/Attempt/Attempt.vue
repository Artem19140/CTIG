<script setup lang="ts">
import SingleChoiceTask from './Components/SingleChoiceTask.vue';
import SidePanel from './Components/SidePanel.vue';

const props = defineProps<{
    attempt:any,
    tasks: any
}>()
</script>

<template>
    <!-- <h1>{{ tasks }}</h1> -->
    <v-app>
        <v-main style="background:#f1f5f9; min-height: 100vh;">
            <v-container>
                    <div class="flex flex-column items-center gap-10">
                        <SingleChoiceTask
                            v-for="task in tasks.data"
                            :task="task"
                        />
                    </div>
                    <div class="flex flex-column items-center ">
                        <v-btn
                            @click=""
                            variant="flat"
                            color="primary"
                        >
                            Завершить
                        </v-btn>
                    </div>

        </v-container>
    </v-main>

    <v-navigation-drawer
      location="right"
      permanent
      width="300"
    >
        <SidePanel :attempt="attempt" />
    </v-navigation-drawer>
</v-app>
</template>