<script setup lang="js">
import { ref } from 'vue'
import calendarData from '../../calendarData.json'
import TextField from '@/components/ui/TextField.vue'

const isOpen = ref(false)
const events = ref([])
const time = ref("")
const date = ref("")
const event = ref({
  name:"",
  color:"blue",
  timed:true
})
events.value = calendarData
const addEvent = () => {
  if(!date.value || !time.value) return
  const splitDate = date.value.split("-")
  const stplitTime = time.value.split(":")
  const eventCopy = {
    ...event.value,
    "start" : new Date(parseInt(splitDate[0]), 
                              parseInt(splitDate[1]) - 1, 
                              parseInt(splitDate[2]), 
                              parseInt(stplitTime[0]), 
                              parseInt(stplitTime[1])
                            )
  }
  console.log(1)
  events.value.push(eventCopy)
}

const close =() => { 
  event.value = {name:"",color:"blue",timed:true}
  date.value = ""
  time.value = ""
  isOpen.value = false
}
</script>

<template>
  <v-btn @click="isOpen = true">Добавить событие</v-btn>
  <v-calendar :key="events.length" :events="events" />
  <v-dialog
  
      v-model="isOpen"
      width="auto"
    >
      <v-card
        max-width="400"
        title="Добавление экзамена"
      >
      <TextField label="Тип экзамена" :rules="['requried']" v-model="event.name" />
      <TextField type="date" :rules="['requried']"  label="Дата экзамена" v-model="date" />
      <TextField type="time" :rules="['requried']"  label="Время начала экзамена" v-model="time" />
      <TextField type="time"  label="Время конца экзамена" v-model="time" />
        <template v-slot:actions>
          <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            text="Close"
            variant="plain"
            @click="close"
          ></v-btn>

          <v-btn
            color="primary"
            text="Save"
            variant="tonal"
            @click="addEvent"
          ></v-btn>
        </v-card-actions>
        </template>
      </v-card>
    </v-dialog>
</template>
