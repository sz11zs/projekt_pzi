<template>
  <div class="pg-wrap">
    <div class="pg-grid">
      <div v-for="p in products" :key="p.id" class="pg-item">
        <v-tooltip location="bottom" content-class="green-tooltip">
          <template #activator="{ props }">
            <div v-bind="props">
              <v-btn class="item-btn" variant="text" @click="$emit('select', p)">
                <v-card class="item-card" rounded="lg" elevation="0">
                  <v-img :src="getImageUrl(p.image)" class="item-img" contain />
                </v-card>
              </v-btn>
            </div>
          </template>

          <span>{{ p.description }}</span>
        </v-tooltip>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

  export type ProductGridItem = {
    id: number | string;
    name: string;
    description: string;
    image: string;
    unit: string | null; // "kg" | "kom" | "l" (dolazi iz API-ja)
    price: number | null;
    quantity: number | null;
    porijeklo: string | null;
    klasa: string | null;
  };

  defineProps<{
    products: ProductGridItem[];
  }>();

  defineEmits<{
    (e: "select", product: ProductGridItem): void;
  }>();

  function getImageUrl(image: string) {
    return new URL(`../assets/products/${image}`, import.meta.url).href;
  }
</script>

<style>
.pg-wrap {
  padding-top: 120px;
  padding-left: 32px;
  padding-right: 32px;
}

/* DESKTOP */
.pg-grid {
  display: grid;
  grid-template-columns: repeat(8, 120px); /*koliko ikona u jednom redu*/
  justify-content: center;
  column-gap: 20px; /* lijevo-desno */
  row-gap: 100px;    /* gore-dolje */
}

/* TABLET */
@media (max-width: 960px) {
  .pg-grid {
    grid-template-columns: repeat(3, 120px);
    column-gap: 20px; /* lijevo-desno */
    row-gap: 100px;    /* gore-dolje */
  }
}

/* MOBILE */
@media (max-width: 600px) {
  .pg-wrap {
    padding-top: 48px;
    padding-left: 16px;
    padding-right: 16px;
  }

  .pg-grid {
    grid-template-columns: repeat(2, 40px);
    gap: 100px; /*razmak vertikalni na mobile*/
  }
}

.pg-item {
  display: flex;
  justify-content: center;
}

.item-btn {
  padding: 0;
  min-width: unset;
}

.item-card {
  width: 110px;
  height: 110px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.item-img {
  width: 80%;
  height: 80%;
}

.v-overlay__content.green-tooltip {
  background: #f1f3ed !important;
  color: #455a1f !important;
  padding: 8px 12px !important;
  border-radius: 10px !important;
  font-size: 14px;
}

</style>
