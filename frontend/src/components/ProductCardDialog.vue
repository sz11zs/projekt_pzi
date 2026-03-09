<template>
  <v-dialog v-model="model" max-width="760" persistent>
    <v-card class="pc-card" rounded="xl" elevation="0">
      <div class="pc-wrap">
        <!-- GORNJI DIO: slika + meta -->
        <div class="pc-top">
          <!-- LIJEVO: slika -->
          <div class="pc-imgBox">
            <v-img
              v-if="product?.image"
              :src="getImageUrl(product.image)"
              class="pc-img"
              cover
            />
          </div>

          <!-- DESNO: tekst -->
          <div v-if="product" class="pc-meta">
            <div class="row">
              <div class="label">NAZIV:</div>
              <div class="value">{{ product.name }}</div>
            </div>

            <div class="row">
              <div class="label">ZEMLJA PODRIJETLA:</div>
              <div class="value">{{ product.porijeklo ?? "-" }}</div>
            </div>

            <div class="row">
              <div class="label">KLASA:</div>
              <div class="value">{{ product.klasa ?? "-" }}</div>
            </div>

            <div class="row">
              <div class="label">DOSTUPNO:</div>
              <div class="value">
                {{ product.quantity ?? "-" }} {{ unitLabel }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- SREDINA: naslov + cijena -->
      <div class="pc-mid">
        <div class="pc-title">{{ (product?.name ?? "-").toUpperCase() }}</div>
        <div class="pc-sub">
          <span class="pc-qty">1 {{ unitLabel }}</span>
          <span class="pc-price">
            {{ formatPrice(product?.price) }} € / {{ unitLabel }}
          </span>
        </div>
      </div>

      <div class="pc-actions">
        <div class="pc-inline">
          <v-text-field
            v-model="amount"
            class="pc-amount"
            type="number"
            min="0.1"
            step="0.1"
            :placeholder="`Unesi željenu količinu u ${unitLabel}`"
            hide-details
            variant="solo"
          />
          <button class="pc-cart" type="button" @click="addToCart">
            <v-icon icon="mdi-cart" />
          </button>
        </div>

        <v-btn class="pc-continue" rounded="pill" size="large" @click="close">
          NASTAVI KUPNJU
        </v-btn>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
  import { computed, ref } from "vue";
import CartCard from "./CartCard.vue";

  const amount = ref<number | string | null>(null);

  type ProductGridItem = {
    id: number | string;
    name: string;
    description: string;
    image: string;
    price?: number;
    quantity?: number;
    porijeklo?: string | null;
    klasa?: string | null;
    unit?: string | null; // "kg" ili "kom"
  };

  const props = defineProps<{
    modelValue: boolean;
    product: ProductGridItem | null;
  }>();

  const emit = defineEmits<{
    (e: "update:modelValue", v: boolean): void;
    (e: "add", payload: { product: ProductGridItem; amountKg: number }): void;
    (e: "open-cart"): void;
  }>();

  const model = computed({
    get: () => props.modelValue,
    set: (v: boolean) => emit("update:modelValue", v),
  });

  // za prikaz u templateu (default "kg")
  const unitLabel = computed(() => props.product?.unit || "kg");

  function close() {
    model.value = false;
  }

  function addToCart() {
    if (!props.product) return;

    const raw = String(amount.value ?? "").trim();
    const qty = parseFloat(raw.replace(",", "."));

    if (!Number.isFinite(qty) || qty <= 0) return;

    // payload ostaje amountKg (kompatibilnost), ali vrijednost je "količina" neovisno o jedinici
    emit("add", { product: props.product, amountKg: qty });

    amount.value = null;
  }

  function formatPrice(p?: number) {
    const n = Number(p ?? 0);
    return n.toFixed(2).replace(".", ",");
  }

  function getImageUrl(image: string) {
    return new URL(`../assets/products/${image}`, import.meta.url).href;
  }

  // export za template
  defineExpose({ unitLabel, formatPrice, getImageUrl, addToCart, close });
</script>

<style scoped>
.pc-card {
  background: #ffffff;
}

.pc-wrap {
  padding: 34px 46px 36px;
}

/* TOP: slika + meta (2 stupca) */
.pc-top {
  display: grid;
  grid-template-columns: 220px 1fr;
  gap: 34px;
  align-items: start;
}

.pc-imgBox {
  width: 100%;
  max-width: 280px;
  height: 200px;
  border: 2px solid #455a1f;
  border-radius: 6px;
  overflow: hidden;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pc-img {
  width: 100%;
  height: 100%;
}

.pc-meta {
  padding-top: 10px;
}

.row {
  display: grid;
  grid-template-columns: 170px 1fr;
  gap: 16px;
  align-items: baseline;
  margin-bottom: 10px;
}

.label {
  font-size: 12px;
  color: #455a1f;
  opacity: 0.85;
  letter-spacing: 0.3px;
  text-transform: uppercase;
}

.value {
  font-size: 18px;
  color: #455a1f;
  text-transform: uppercase;
}

/* MID */
.pc-mid {
  margin-top: 18px;
  text-align: center;
}

.pc-title {
  font-size: 38px;
  color: #455a1f;
  line-height: 1.1;
}

.pc-sub {
  margin-top: 8px;
  display: flex;
  justify-content: center;
  gap: 14px;
  align-items: baseline;
  color: #455a1f;
}

.pc-qty {
  font-weight: 600;
  font-size: 18px;
}

.pc-price {
  font-weight: 700;
  font-size: 22px;
}

/* input+kosarica + nastavi kupnju (jedan ispod drugog) */
.pc-actions {
  width: 340px;
  margin: 22px auto 18px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
}

.pc-inline {
  width: 100%;
  height: 52px;
  display: flex;
  overflow: hidden;
  border-radius: 999px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}
.pc-amount {
  flex: 1;
}

.pc-amount :deep(.v-field) {
  height: 52px;
  border-radius: 999px 0 0 999px;
  background: #d8d48a;
  box-shadow: none;
}

.pc-amount :deep(.v-field__outline),
.pc-amount :deep(.v-field__overlay) {
  display: none;
}

.pc-amount :deep(.v-field__input) {
  padding: 10;
}
.pc-amount :deep(input[type="number"]::-webkit-outer-spin-button),
.pc-amount :deep(input[type="number"]::-webkit-inner-spin-button) {
  -webkit-appearance: none;
  margin: 0;
}
.pc-amount :deep(input[type="number"]) {
  -moz-appearance: textfield;
}

/*tekst input*/
.pc-amount :deep(input) {
  height: 52px;
  padding: 18px;
  color: #455a1f;
  font-weight: 700;
}

.pc-amount :deep(input::placeholder) {
  color: #455a1f;
  font-weight: 600;
}

.pc-cart {
  width: 92px;
  height: 52px;
  border: 0;
  background: #455a1f;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.pc-cart :deep(.v-icon) {
  font-size: 28px;
  color: #fff;
}

.pc-continue {
  width: 100%;
  height: 52px;
  background: #d8d48a;
  color: #455a1f;
  font-weight: 800;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

/* samo na mobitelu slaži ispod */
@media (max-width: 380px) {
  .pc-wrap {
    padding: 22px 15px;
  }

  .pc-top {
    grid-template-columns: 1fr;
    justify-items: center;
    gap: 15px;
  }

  .row {
    grid-template-columns: 1fr;
    gap: 2px;
    margin-bottom: 12px;
    text-align: left;
    width: 100%;
  }

  .pc-title {
    font-size: 30px;
  }

  .pc-actions {
    width: 100%;
    max-width: 360px;
  }
  .label {
    font-size: 11px;
  }

  .value {
    font-size: 16px;
  }

  .pc-title {
    font-size: 28px;
    padding: 0 10px;
  }

  .pc-actions {
    width: 90%;
    max-width: none;
  }

  .pc-amount :deep(input) {
    font-size: 14px;
    padding: 10px;
  }

}
</style>
