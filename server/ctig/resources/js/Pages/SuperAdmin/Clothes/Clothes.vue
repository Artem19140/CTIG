<template>
  <v-app>
    <!-- Шапка (оставляем как есть) -->
    <v-app-bar color="#785F21" dark flat>
      <v-toolbar-title>Fashion Analyzer</v-toolbar-title>
      <v-spacer />
      <v-text-field v-model="search" prepend-inner-icon="mdi-magnify"
        label="Поиск одежды" class="max-w-xs mr-3" variant="solo-filled" density="compact" hide-details />
    </v-app-bar>

    <v-navigation-drawer 
      permanent 
      width="400" 
      color="#F5F5DC" 
      border="none"
    >
      <div class="pa-4 pt-8">
        <v-card rounded="xl" elevation="2" class="pa-4">
          <h3 class="text-h6 mb-4">Фильтры</h3>
          <v-select variant="outlined" v-model="selectedTrend" :items="trendOptions" label="Стиль" clearable />
          <v-select variant="outlined" v-model="selectedAge" :items="ageOptions" label="Возрастная группа" clearable />
          <div class="mt-2">Цена</div>
          <div class="flex gap-2 mt-2">
            <v-text-field variant="outlined" label="От ₽" density="compact" />
            <v-text-field variant="outlined" label="До ₽" density="compact" />
          </div>
          <v-card-actions>
            <v-btn>Очистить фильтры</v-btn>
          </v-card-actions>
          
        </v-card>
      </div>
    </v-navigation-drawer>

    <!-- ОСНОВНОЙ КОНТЕНТ -->
    <v-main style="background-color: #F5F5DC;">
      <v-container class="py-6">
        <!-- Убираем пустую колонку md="3", так как drawer сам заберет это место -->
        <v-row>
          <v-col
            v-for="item in filteredItems"
            :key="item.id"
            cols="12"
            sm="6"
            lg="4"
            @click="isOpen = true"
          >
            <v-card rounded="2xl" elevation="4" class="h-100">
              <v-img :src="item.image" height="240" cover />
              <v-card-title>{{ item.name }}</v-card-title>
              
              <v-card-text>
                <div class="text-grey mb-2 text-xs">{{ item.description }}</div>
                <div class="mb-2">
                  <v-chip :color="item.trend === 'Тренд' ? 'pink' : 'indigo'" size="small">
                    {{ item.trend }}
                  </v-chip>
                </div>
                <div class="font-weight-bold">{{ item.price }} ₽</div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
      <clothes-modal v-model="isOpen" />
    </v-main>
  </v-app>
</template>


<script setup>
import { ref, computed } from 'vue'
import ClothesModal from './ClothesModal.vue'

const isOpen = ref(false)
const search = ref('')
const selectedTrend = ref(null)
const selectedAge = ref(null)

const trendOptions = ['Тренд', 'Классика']
const ageOptions = ['18-25', '26-35', '36-50', '50+']

const items = ref([
  {
    id: 1,
    name: 'Oversized Blazer',
    category: 'Пиджак',
    trend: 'Тренд',
    ageGroup: '26-35',
    price: 12900,
    image: 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b',
    description: 'Современный оверсайз пиджак для стильных образов.'
  },
  {
    id: 2,
    name: 'Classic Trench Coat',
    category: 'Пальто',
    trend: 'Классика',
    ageGroup: '36-50',
    price: 15890,
    image: 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246',
    description: 'Элегантный классический тренч на все времена.'
  },
  {
    id: 3,
    name: 'Minimal White Sneakers',
    category: 'Обувь',
    trend: 'Классика',
    ageGroup: '18-25',
    price: 8900,
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
    description: 'Универсальные белые кеды для любого гардероба.'
  },
  {
    id: 4,
    name: 'Cargo Pants',
    category: 'Брюки',
    trend: 'Тренд',
    ageGroup: '18-25',
    price: 7400,
    image: 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f',
    description: 'Популярные карго брюки для casual образов.'
  },
  {
    id: 5,
    name: 'Silk Midi Dress',
    category: 'Платье',
    trend: 'Классика',
    ageGroup: '26-35',
    price: 14450,
    image: 'https://images.unsplash.com/photo-1496747611176-843222e1e57c',
    description: 'Изящное шелковое платье для особых случаев.'
  },
  {
    id: 6,
    name: 'Denim Jacket',
    category: 'Куртка',
    trend: 'Классика',
    ageGroup: '18-25',
    price: 9900,
    image: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518',
    description: 'Универсальная джинсовая куртка для любого сезона.'
  },
  {
    id: 7,
    name: 'Chunky Knit Sweater',
    category: 'Свитер',
    trend: 'Тренд',
    ageGroup: '26-35',
    price: 6200,
    image: 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105',
    description: 'Объёмный уютный свитер для холодной погоды.'
  },
  {
    id: 8,
    name: 'Leather Chelsea Boots',
    category: 'Обувь',
    trend: 'Классика',
    ageGroup: '36-50',
    price: 13200,
    image: 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77',
    description: 'Кожаные челси для элегантного повседневного образа.'
  },
  {
    id: 9,
    name: 'Oversized Hoodie',
    category: 'Худи',
    trend: 'Тренд',
    ageGroup: '18-25',
    price: 5400,
    image: 'https://images.unsplash.com/photo-1503341504253-dff4815485f1',
    description: 'Комфортное худи oversize в актуальном стиле streetwear.'
  },
  {
    id: 10,
    name: 'Pleated Skirt',
    category: 'Юбка',
    trend: 'Классика',
    ageGroup: '26-35',
    price: 7200,
    image: 'https://images.unsplash.com/photo-1483985988355-763728e1935b',
    description: 'Плиссированная юбка для утончённых образов.'
  },
  {
    id: 11,
    name: 'Bomber Jacket',
    category: 'Куртка',
    trend: 'Тренд',
    ageGroup: '18-25',
    price: 11350,
    image: 'https://images.unsplash.com/photo-1507679799987-c73779587ccf',
    description: 'Современный бомбер для динамичного городского стиля.'
  },
  {
    id: 12,
    name: 'Linen Shirt',
    category: 'Рубашка',
    trend: 'Классика',
    ageGroup: '26-35',
    price: 5800,
    image: 'https://images.unsplash.com/photo-1603252109303-2751441dd157',
    description: 'Лёгкая льняная рубашка для стильных летних образов.'
  },
  {
    id: 13,
    name: 'Wide Leg Jeans',
    category: 'Джинсы',
    trend: 'Тренд',
    ageGroup: '18-25',
    price: 8300,
    image: 'https://images.unsplash.com/photo-1542272604-787c3835535d',
    description: 'Широкие джинсы в актуальном современном стиле.'
  },
  {
    id: 14,
    name: 'Cashmere Scarf',
    category: 'Аксессуары',
    trend: 'Классика',
    ageGroup: '36-50',
    price: 4900,
    image: 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9',
    description: 'Мягкий кашемировый шарф для холодного сезона.'
  },
  {
    id: 15,
    name: 'Structured Handbag',
    category: 'Сумка',
    trend: 'Тренд',
    ageGroup: '26-35',
    price: 16700,
    image: 'https://images.unsplash.com/photo-1584917865442-de89df76afd3',
    description: 'Элегантная сумка с жёсткой формой для делового стиля.'
  }
])

const filteredItems = computed(() => {
  return items.value.filter(item => {
    const matchesSearch = item.name
      .toLowerCase()
      .includes(search.value.toLowerCase())

    const matchesTrend = selectedTrend.value
      ? item.trend === selectedTrend.value
      : true

    const matchesAge = selectedAge.value
      ? item.ageGroup === selectedAge.value
      : true

    return matchesSearch && matchesTrend && matchesAge
  })
})
</script>
