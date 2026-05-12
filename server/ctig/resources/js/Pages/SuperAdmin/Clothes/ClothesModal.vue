<template>
  <v-dialog
    v-model="isOpen"
    width="500"
  >
    <v-card rounded="xl" elevation="8">
  <v-img
    :src="clothe.image"
    height="320"
    cover
    class="rounded-t-xl"
  >
    <div class="d-flex justify-space-between pa-4">
      <v-chip
        color="pink"
        size="small"
        variant="elevated"
      >
        {{ clothe.trend }}
      </v-chip>

      <v-chip
        color="black"
        text-color="white"
        size="small"
      >
        {{ clothe.ageGroup }}
      </v-chip>
    </div>
  </v-img>

  <v-card-item>
    <v-card-title class="text-h5 font-weight-bold">
      {{ clothe.name }}
    </v-card-title>

    <v-card-subtitle class="text-medium-emphasis">
      {{ clothe.category }}
    </v-card-subtitle>
  </v-card-item>

  <v-card-text>
    <div class="text-body-1 mb-4 text-medium-emphasis">
      {{ clothe.description }}
    </div>

    <div class="d-flex align-center justify-space-between">
      <div class="text-h4 font-weight-bold text-primary">
        {{ clothe.price.toLocaleString() }} ₽
      </div>

      <v-rating
        model-value="4.8"
        color="amber"
        density="compact"
        readonly
        half-increments
        size="small"
      />
    </div>
  </v-card-text>

  <v-divider />

  <v-card-actions class="pa-4">
    <v-btn
      variant="outlined"
      prepend-icon="mdi-cart-outline"
      color="primary"
      rounded="lg"
      block
      class="mr-2"
    >
      В корзину
    </v-btn>

    <v-btn
      color="primary"
      prepend-icon="mdi-lightning-bolt"
      rounded="lg"
      block
    >
      Заказать
    </v-btn>
  </v-card-actions>
</v-card>
  </v-dialog>
</template>

<script setup>
const isOpen = defineModel({default:false})
const clothe = {
    id: 15,
    name: 'Structured Handbag',
    category: 'Сумка',
    trend: 'Тренд',
    ageGroup: '26-35',
    price: 16700,
    image: 'https://images.unsplash.com/photo-1584917865442-de89df76afd3',
    description: 'Элегантная сумка с жёсткой формой для делового стиля.'
  }
</script>
